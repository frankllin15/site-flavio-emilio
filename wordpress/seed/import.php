<?php
/**
 * Importa seed/data.json para o WordPress. Roda dentro do container WP-CLI:
 *
 *   npm run seed:import                      → cria o que falta, não mexe no que já existe
 *   npm run seed:import -- atualizar         → sobrescreve itens/opções já importados com os dados do seed
 *
 * Cada item guarda a meta _fec_seed_key e cada imagem _fec_seed_source, então rodar de novo não duplica nada.
 * Imagens vêm de public/images do app Next (montado em /seed-images).
 *
 * @var array $args Argumentos posicionais do `wp eval-file`.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}
if ( ! function_exists( 'update_field' ) ) {
	WP_CLI::error( 'Secure Custom Fields/ACF não está ativo. Rode `npm run setup` primeiro.' );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

const FEC_SEED_IMAGES = '/seed-images';

// `wp eval-file` executa este arquivo dentro de uma função: declara as globais usadas pelos helpers.
global $fec_update, $fec_stats;

$fec_update = in_array( 'atualizar', $args ?? array(), true );
$fec_json   = file_get_contents( __DIR__ . '/data.json' );
if ( false === $fec_json ) {
	WP_CLI::error( 'seed/data.json não encontrado. Rode `npm run seed:export` antes.' );
}
$fec_data  = json_decode( $fec_json, true, 512, JSON_THROW_ON_ERROR );
$fec_stats = array( 'criados' => 0, 'atualizados' => 0, 'mantidos' => 0, 'imagens' => 0, 'avisos' => 0 );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/**
 * Nome de arquivo sem colisão de NOME BASE na pasta de uploads do mês.
 *
 * As miniaturas são gravadas em WebP (fec-core/media.php), então "hero.png" e "hero.jpg" virariam
 * os dois "hero.webp" e um sobrescreveria o outro (aconteceu com hero.* e hospital-rio-grande.*).
 * Se já existe qualquer "<base>.*", usa "<base>-2", "<base>-3"...
 */
function fec_seed_unique_name( string $relative ): string {
	$dir  = wp_upload_dir()['path'];
	$ext  = pathinfo( $relative, PATHINFO_EXTENSION );
	$base = pathinfo( $relative, PATHINFO_FILENAME );
	$name = $base;
	for ( $n = 2; glob( $dir . '/' . fec_glob_escape( $name ) . '.*' ); $n++ ) {
		$name = "{$base}-{$n}";
	}
	return "{$name}.{$ext}";
}

/** Escapa os curingas do glob() num nome de arquivo. */
function fec_glob_escape( string $name ): string {
	return preg_replace( '/([*?\[\]])/', '[$1]', $name );
}

/** Importa (uma vez) uma imagem de public/images e devolve o ID do anexo. */
function fec_seed_image( ?string $relative, int $parent = 0 ): ?int {
	global $fec_stats;
	if ( ! $relative ) {
		return null;
	}

	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_fec_seed_source',
			'meta_value'  => $relative,
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	// Caminho absoluto (ex.: /seed/generated/og-image.jpg) ou relativo a public/images.
	$source = str_starts_with( $relative, '/' ) ? $relative : FEC_SEED_IMAGES . '/' . $relative;
	if ( ! is_readable( $source ) ) {
		WP_CLI::warning( "Imagem não encontrada: {$source}" );
		++$fec_stats['avisos'];
		return null;
	}

	$tmp = wp_tempnam( basename( $relative ) );
	copy( $source, $tmp );
	$id = media_handle_sideload( array( 'name' => fec_seed_unique_name( $relative ), 'tmp_name' => $tmp ), $parent );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		WP_CLI::warning( "Falha ao importar {$relative}: " . $id->get_error_message() );
		++$fec_stats['avisos'];
		return null;
	}

	update_post_meta( $id, '_fec_seed_source', $relative );
	++$fec_stats['imagens'];
	return (int) $id;
}

/** Texto simples → parágrafo do editor de blocos (HTML já pronto passa direto). */
function fec_seed_blocks( string $text ): string {
	if ( str_contains( $text, '<p>' ) ) {
		return preg_replace( '#<p>(.*?)</p>#s', "<!-- wp:paragraph -->\n<p>$1</p>\n<!-- /wp:paragraph -->", $text );
	}
	return "<!-- wp:paragraph -->\n<p>" . esc_html( $text ) . "</p>\n<!-- /wp:paragraph -->";
}

function fec_seed_find( string $type, string $key ): ?WP_Post {
	$found = get_posts(
		array(
			'post_type'   => $type,
			'post_status' => 'any',
			'meta_key'    => '_fec_seed_key',
			'meta_value'  => $key,
			'numberposts' => 1,
		)
	);
	return $found[0] ?? null;
}

/** Campos de imagem por tipo: o seed traz o caminho, o ACF guarda o ID do anexo. */
const FEC_IMAGE_FIELDS = array(
	'servico'    => array( 'logo' ),
	'palestra'   => array( 'logo' ),
	'cliente'    => array( 'logo' ),
	'depoimento' => array( 'avatar' ),
);

function fec_seed_item( string $type, array $item, ?int $author = null ): void {
	global $fec_update, $fec_stats;

	$key      = $item['key'] ?? $item['slug'];
	$existing = fec_seed_find( $type, $key );

	if ( $existing && ! $fec_update ) {
		++$fec_stats['mantidos'];
		return;
	}

	$postarr = array(
		'post_type'    => $type,
		'post_title'   => $item['title'],
		'post_content' => isset( $item['content'] ) ? fec_seed_blocks( $item['content'] ) : '',
		'post_excerpt' => $item['excerpt'] ?? '',
		'post_status'  => $item['status'] ?? 'publish',
		'menu_order'   => $item['order'] ?? 0,
		'meta_input'   => array( '_fec_seed_key' => $key ),
	);
	if ( ! empty( $item['slug'] ) ) {
		$postarr['post_name'] = $item['slug'];
	}
	if ( ! empty( $item['date'] ) ) {
		// Data fixa também em GMT: sem isso o rascunho fica com data "flutuante" e ganha a data do dia ao ser publicado.
		$postarr['post_date']     = $item['date'];
		$postarr['post_date_gmt'] = get_gmt_from_date( $item['date'] );
		$postarr['edit_date']     = true;
	}
	if ( $author ) {
		$postarr['post_author'] = $author;
	}

	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
		$id            = wp_update_post( $postarr, true );
		++$fec_stats['atualizados'];
	} else {
		$id = wp_insert_post( $postarr, true );
		++$fec_stats['criados'];
	}
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "{$type} \"{$item['title']}\": " . $id->get_error_message() );
		++$fec_stats['avisos'];
		return;
	}

	if ( ! empty( $item['thumbnail'] ) ) {
		$thumb = fec_seed_image( $item['thumbnail'], $id );
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}
	}

	if ( ! empty( $item['category'] ) ) {
		$term = term_exists( $item['category'], 'category' ) ?: wp_insert_term( $item['category'], 'category' );
		if ( ! is_wp_error( $term ) ) {
			wp_set_post_categories( $id, array( (int) $term['term_id'] ) );
		}
	}

	foreach ( $item['fields'] ?? array() as $name => $value ) {
		if ( in_array( $name, FEC_IMAGE_FIELDS[ $type ] ?? array(), true ) ) {
			$value = fec_seed_image( $value, $id );
		}
		// Sempre pela chave: vários tipos têm campos com o mesmo nome (logo, topicos...).
		update_field( "field_fec_{$type}_{$name}", $value, $id );
	}
}

/* -------------------------------------------------------------------------
 * 1. Conteúdo do Site (página de opções)
 * ---------------------------------------------------------------------- */

WP_CLI::log( '> Conteúdo do Site (opções)' );
$fec_opt_images = array( 'hero_imagem_desktop', 'hero_imagem_mobile', 'sobre_imagem', 'proficient_logo' );
foreach ( $fec_data['options'] as $name => $value ) {
	$current = get_field( $name, 'option' );
	if ( ! $fec_update && ! empty( $current ) ) {
		continue;
	}
	if ( in_array( $name, $fec_opt_images, true ) ) {
		$value = fec_seed_image( $value );
	}
	update_field( "field_fec_opt_{$name}", $value, 'option' );
}

/* -------------------------------------------------------------------------
 * 2. Tipos de conteúdo
 * ---------------------------------------------------------------------- */

// Autor dos posts do blog. Conta local sem senha conhecida (a pessoa entra pelo admin).
$fec_author = get_user_by( 'login', 'flavio' );
if ( ! $fec_author ) {
	$fec_author_id = wp_insert_user(
		array(
			'user_login'   => 'flavio',
			'user_pass'    => wp_generate_password( 32 ),
			'user_email'   => 'flavio@flavioemilio.test',
			'display_name' => 'Flávio Emílio Cavalcanti',
			'first_name'   => 'Flávio Emílio',
			'last_name'    => 'Cavalcanti',
			'role'         => 'editor',
		)
	);
} else {
	$fec_author_id = $fec_author->ID;
}

foreach ( array( 'servico', 'palestra', 'evento', 'livro', 'cliente', 'depoimento', 'post' ) as $fec_type ) {
	WP_CLI::log( "> {$fec_type}: " . count( $fec_data[ $fec_type ] ) );
	foreach ( $fec_data[ $fec_type ] as $fec_item ) {
		fec_seed_item( $fec_type, $fec_item, 'post' === $fec_type && ! is_wp_error( $fec_author_id ) ? (int) $fec_author_id : null );
	}
}

// Remove o "Olá, mundo!" e a página de exemplo da instalação.
foreach ( array( 'ola-mundo', 'hello-world' ) as $fec_slug ) {
	$fec_p = get_page_by_path( $fec_slug, OBJECT, 'post' );
	if ( $fec_p ) {
		wp_delete_post( $fec_p->ID, true );
	}
}
foreach ( array( 'pagina-exemplo', 'sample-page' ) as $fec_slug ) {
	$fec_p = get_page_by_path( $fec_slug );
	if ( $fec_p ) {
		wp_delete_post( $fec_p->ID, true );
	}
}

/* -------------------------------------------------------------------------
 * 3. Páginas Início/Blog e menu
 * ---------------------------------------------------------------------- */

WP_CLI::log( '> Páginas e menu' );
$fec_pages = array();
foreach ( array( 'inicio' => 'Início', 'blog' => 'Blog' ) as $fec_slug => $fec_title ) {
	$fec_page               = get_page_by_path( $fec_slug );
	$fec_pages[ $fec_slug ] = $fec_page ? $fec_page->ID : wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_name'   => $fec_slug,
			'post_title'  => $fec_title,
			'post_status' => 'publish',
		)
	);
}
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $fec_pages['inicio'] );
update_option( 'page_for_posts', $fec_pages['blog'] );

$fec_locations = get_theme_mod( 'nav_menu_locations', array() );
foreach ( $fec_data['menus'] as $fec_location => $fec_menu_data ) {
	$fec_menu = wp_get_nav_menu_object( $fec_menu_data['name'] );
	if ( $fec_menu && ! $fec_update ) {
		$fec_locations[ $fec_location ] ??= $fec_menu->term_id;
		continue;
	}
	if ( $fec_menu ) {
		wp_delete_nav_menu( $fec_menu->term_id );
	}
	$fec_menu_id = wp_create_nav_menu( $fec_menu_data['name'] );
	foreach ( $fec_menu_data['items'] as $fec_pos => $fec_link ) {
		wp_update_nav_menu_item(
			$fec_menu_id,
			0,
			array(
				'menu-item-title'    => $fec_link['label'],
				'menu-item-url'      => $fec_link['href'],
				'menu-item-type'     => 'custom',
				'menu-item-status'   => 'publish',
				'menu-item-position' => $fec_pos + 1,
			)
		);
	}
	$fec_locations[ $fec_location ] = $fec_menu_id;
}
set_theme_mod( 'nav_menu_locations', $fec_locations );

/* -------------------------------------------------------------------------
 * 4. Formulário de contato (Contact Form 7)
 * ---------------------------------------------------------------------- */

if ( class_exists( 'WPCF7_ContactForm' ) ) {
	WP_CLI::log( '> Formulário de contato' );
	$fec_cf_def = require __DIR__ . '/contact-form.php';

	$fec_cf_existing = get_posts(
		array(
			'post_type'   => 'wpcf7_contact_form',
			'post_status' => 'any',
			'meta_key'    => '_fec_seed_key',
			'meta_value'  => 'contato-site',
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);

	if ( ! $fec_cf_existing || $fec_update ) {
		$fec_cf = $fec_cf_existing
			? WPCF7_ContactForm::get_instance( $fec_cf_existing[0] )
			: WPCF7_ContactForm::get_template( array( 'title' => $fec_cf_def['title'] ) );

		$fec_cf_def['mail']['recipient'] = $fec_data['options']['email'];

		$fec_cf->set_title( $fec_cf_def['title'] );
		$fec_cf->set_properties(
			array(
				'form'     => $fec_cf_def['form'],
				'mail'     => $fec_cf_def['mail'],
				'mail_2'   => array_merge( (array) $fec_cf->prop( 'mail_2' ), array( 'active' => false ) ),
				'messages' => array_merge( (array) $fec_cf->prop( 'messages' ), $fec_cf_def['messages'] ),
				'additional_settings' => $fec_cf_def['additional_settings'],
			)
		);
		$fec_cf_id = $fec_cf->save();
		update_post_meta( $fec_cf_id, '_fec_seed_key', 'contato-site' );
		$fec_stats[ $fec_cf_existing ? 'atualizados' : 'criados' ]++;
	} else {
		$fec_cf_id = $fec_cf_existing[0];
		++$fec_stats['mantidos'];
	}

	// Remove o "Formulário de contato 1" que o CF7 cria na ativação.
	foreach ( get_posts( array( 'post_type' => 'wpcf7_contact_form', 'numberposts' => -1, 'fields' => 'ids' ) ) as $fec_other ) {
		if ( (int) $fec_other !== (int) $fec_cf_id && ! get_post_meta( $fec_other, '_fec_seed_key', true ) ) {
			wp_delete_post( $fec_other, true );
		}
	}

	if ( $fec_update || ! get_field( 'contato_formulario', 'option' ) ) {
		update_field( 'field_fec_opt_contato_formulario', $fec_cf_id, 'option' );
	}
}

/* -------------------------------------------------------------------------
 * 5. SEO (Yoast)
 * ---------------------------------------------------------------------- */

require __DIR__ . '/seo.php';

flush_rewrite_rules();

WP_CLI::success(
	sprintf(
		'Importação concluída: %d criados, %d atualizados, %d já existiam, %d imagens, %d avisos.',
		$fec_stats['criados'],
		$fec_stats['atualizados'],
		$fec_stats['mantidos'],
		$fec_stats['imagens'],
		$fec_stats['avisos']
	)
);
