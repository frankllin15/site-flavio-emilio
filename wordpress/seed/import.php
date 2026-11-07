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

	$source = FEC_SEED_IMAGES . '/' . $relative;
	if ( ! is_readable( $source ) ) {
		WP_CLI::warning( "Imagem não encontrada: public/images/{$relative}" );
		++$fec_stats['avisos'];
		return null;
	}

	$tmp = wp_tempnam( basename( $relative ) );
	copy( $source, $tmp );
	$id = media_handle_sideload( array( 'name' => basename( $relative ), 'tmp_name' => $tmp ), $parent );
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
		$postarr['post_date'] = $item['date'];
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

$fec_menu = wp_get_nav_menu_object( 'Principal' );
if ( ! $fec_menu || $fec_update ) {
	if ( $fec_menu ) {
		wp_delete_nav_menu( $fec_menu->term_id );
	}
	$fec_menu_id = wp_create_nav_menu( 'Principal' );
	foreach ( $fec_data['menu'] as $fec_pos => $fec_link ) {
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
	$fec_locations            = get_theme_mod( 'nav_menu_locations', array() );
	$fec_locations['primary'] = $fec_menu_id;
	set_theme_mod( 'nav_menu_locations', $fec_locations );
}

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
