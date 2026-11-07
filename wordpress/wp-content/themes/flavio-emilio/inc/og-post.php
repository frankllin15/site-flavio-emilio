<?php
/**
 * Imagem de compartilhamento (Open Graph) gerada para cada post (docs/plano-blog.md, B4).
 *
 * Ao publicar ou atualizar um post, gera um JPEG 1200×630 na identidade do site: fundo azul
 * (com a imagem destacada escurecida, se houver), categoria, título em Montserrat, foto e nome do
 * autor. Vira a imagem do LinkedIn/WhatsApp/Facebook pelo Yoast, a não ser que uma imagem tenha
 * sido escolhida manualmente na aba "Social" do Yoast.
 *
 * Precisa do Imagick com suporte a JPEG no servidor (Hostinger/Locaweb costumam ter).
 * Sem ele, nada é gerado e vale a imagem destacada ou a imagem padrão do site.
 */

defined( 'ABSPATH' ) || exit;

const FEC_OG_VERSION = 1; // aumente para regenerar todas as imagens após mudar o layout

add_action(
	'wp_after_insert_post',
	static function ( $post_id, $post ) {
		if ( 'post' === $post->post_type && 'publish' === $post->post_status && ! wp_is_post_revision( $post_id ) ) {
			fec_og_generate( (int) $post_id );
		}
	},
	20,
	2
);

// Post apagado: apaga a imagem gerada junto.
add_action(
	'before_delete_post',
	static function ( $post_id ) {
		$og = (int) get_post_meta( $post_id, '_fec_og_id', true );
		if ( $og ) {
			wp_delete_attachment( $og, true );
		}
	}
);

function fec_og_supported(): bool {
	return class_exists( 'Imagick' ) && Imagick::queryFormats( 'JPEG' );
}

/**
 * Gera (ou regera, se título/imagem/categoria mudaram) a imagem do post. Devolve o ID do anexo.
 */
function fec_og_generate( int $post_id, bool $force = false ): ?int {
	if ( ! fec_og_supported() ) {
		return null;
	}

	$post     = get_post( $post_id );
	$title    = html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' );
	$category = fec_post_category( $post );
	$thumb_id = (int) get_post_thumbnail_id( $post );
	$hash     = md5( implode( '|', array( FEC_OG_VERSION, $title, $category->name ?? '', $thumb_id, $post->post_author ) ) );
	$old_id   = (int) get_post_meta( $post_id, '_fec_og_id', true );

	if ( ! $force && $old_id && get_post( $old_id ) && get_post_meta( $post_id, '_fec_og_hash', true ) === $hash ) {
		// Imagem atual serve, mas o Yoast pode ter gravado os campos sociais vazios por cima
		// (o editor de blocos salva as caixas de metadados numa segunda requisição).
		fec_og_apply_to_yoast( $post_id, $old_id, $old_id );
		return $old_id;
	}

	$fonts = get_template_directory() . '/assets/fonts/';
	$bold  = $fonts . 'montserrat-latin-700-normal.woff2';
	$med   = $fonts . 'montserrat-latin-500-normal.woff2';
	if ( ! is_readable( $bold ) || ! is_readable( $med ) ) {
		return null; // rode `npm run fonts`
	}

	try {
		$jpeg = fec_og_render( $post, $title, $category, $thumb_id, $bold, $med );
	} catch ( Throwable $e ) {
		error_log( 'fec_og_generate: ' . $e->getMessage() ); // phpcs:ignore
		return null;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( 'og.jpg' );
	file_put_contents( $tmp, $jpeg ); // phpcs:ignore

	// A imagem já está no tamanho final: sem miniaturas e sem conversão para WebP
	// (algumas redes ainda leem melhor JPEG no og:image).
	$no_sizes  = static fn() => array();
	$keep_jpeg = static fn( $formats ) => array_diff_key( $formats, array( 'image/jpeg' => true ) );
	add_filter( 'intermediate_image_sizes_advanced', $no_sizes );
	add_filter( 'image_editor_output_format', $keep_jpeg, 20 );
	$new_id = media_handle_sideload(
		array(
			'name'     => 'compartilhar-' . $post->post_name . '.jpg',
			'tmp_name' => $tmp,
		),
		$post_id,
		'Imagem de compartilhamento: ' . $title
	);
	remove_filter( 'intermediate_image_sizes_advanced', $no_sizes );
	remove_filter( 'image_editor_output_format', $keep_jpeg, 20 );

	if ( is_wp_error( $new_id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return null;
	}
	update_post_meta( $new_id, '_fec_og_for', $post_id );
	update_post_meta( $new_id, '_wp_attachment_image_alt', $title );

	fec_og_apply_to_yoast( $post_id, (int) $new_id, $old_id );

	update_post_meta( $post_id, '_fec_og_id', $new_id );
	update_post_meta( $post_id, '_fec_og_hash', $hash );

	if ( $old_id && $old_id !== $new_id ) {
		wp_delete_attachment( $old_id, true );
	}
	return (int) $new_id;
}

/**
 * Usa a imagem gerada como imagem social do Yoast (Facebook/LinkedIn/WhatsApp e X), a não ser que
 * uma imagem tenha sido escolhida manualmente na aba "Social" do Yoast.
 */
function fec_og_apply_to_yoast( int $post_id, int $new_id, int $old_id ): void {
	$url = wp_get_attachment_url( $new_id );
	foreach ( array( 'opengraph', 'twitter' ) as $net ) {
		$current = (int) get_post_meta( $post_id, "_yoast_wpseo_{$net}-image-id", true );
		if ( ! $current || $current === $old_id || $current === $new_id ) {
			update_post_meta( $post_id, "_yoast_wpseo_{$net}-image", $url );
			update_post_meta( $post_id, "_yoast_wpseo_{$net}-image-id", $new_id );
		}
	}
}

/** Monta a imagem e devolve os bytes do JPEG. */
function fec_og_render( WP_Post $post, string $title, ?WP_Term $category, int $thumb_id, string $bold, string $med ): string {
	$w     = 1200;
	$h     = 630;
	$pad   = 80;
	$navy  = '#172554';
	$gold  = '#fbbf24';

	$canvas = new Imagick();
	$canvas->newImage( $w, $h, new ImagickPixel( $navy ) );

	// Fundo: imagem destacada cobrindo tudo, escurecida pelo azul da marca.
	$thumb_file = $thumb_id ? get_attached_file( $thumb_id ) : '';
	if ( $thumb_file && is_readable( $thumb_file ) ) {
		$bg = new Imagick( $thumb_file );
		$bg->cropThumbnailImage( $w, $h );
		$canvas->compositeImage( $bg, Imagick::COMPOSITE_OVER, 0, 0 );
		$veil = new Imagick();
		$veil->newImage( $w, $h, new ImagickPixel( 'rgba(23,37,84,0.86)' ) );
		$canvas->compositeImage( $veil, Imagick::COMPOSITE_OVER, 0, 0 );
	}

	// Faixa dourada à esquerda.
	$bar = new ImagickDraw();
	$bar->setFillColor( new ImagickPixel( $gold ) );
	$bar->rectangle( 0, 0, 11, $h );
	$canvas->drawImage( $bar );

	$text = static function ( string $font, float $size, string $color ): ImagickDraw {
		$d = new ImagickDraw();
		$d->setFont( $font );
		$d->setFontSize( $size );
		$d->setFillColor( new ImagickPixel( $color ) );
		$d->setTextAntialias( true );
		return $d;
	};

	// Categoria (ou o nome do blog).
	$kicker = mb_strtoupper( $category ? $category->name : fec_blog_title() );
	$kd     = $text( $bold, 24, $gold );
	$kd->setTextKerning( 2.5 );
	$canvas->annotateImage( $kd, $pad, 120, 0, $kicker );

	// Título: quebra em linhas pela largura real do texto; diminui a fonte se passar de 3 linhas.
	$max_w = $w - $pad * 2;
	foreach ( array( 64, 56, 48, 42 ) as $size ) {
		$td    = $text( $bold, $size, '#ffffff' );
		$lines = fec_og_wrap( $canvas, $td, $title, $max_w );
		if ( count( $lines ) <= 3 ) {
			break;
		}
	}
	if ( count( $lines ) > 4 ) {
		$lines    = array_slice( $lines, 0, 4 );
		$lines[3] = rtrim( $lines[3], ' .,;:' ) . '…';
	}
	$line_h = (int) round( $size * 1.18 );
	$y      = 120 + 40 + $size;
	foreach ( $lines as $line ) {
		$canvas->annotateImage( $td, $pad, $y, 0, $line );
		$y += $line_h;
	}

	// Linha dourada sob o título.
	$ul = new ImagickDraw();
	$ul->setFillColor( new ImagickPixel( $gold ) );
	$ul->rectangle( $pad, $y - $line_h + 34, $pad + 120, $y - $line_h + 40 );
	$canvas->drawImage( $ul );

	// Rodapé: foto redonda + nome + blog/domínio.
	$author_id = (int) $post->post_author;
	$photo_id  = function_exists( 'get_field' ) ? (int) get_field( 'fec_foto', 'user_' . $author_id ) : 0;
	$photo     = $photo_id ? get_attached_file( $photo_id ) : '';
	$name_x    = $pad;
	if ( $photo && is_readable( $photo ) ) {
		$size_px = 84;
		$av      = new Imagick( $photo );
		$av->cropThumbnailImage( $size_px, $size_px );
		$av->setImageFormat( 'png' );
		// Foto JPEG não tem transparência: ativa o canal alfa para a máscara redonda recortar os cantos.
		$av->setImageAlphaChannel( defined( 'Imagick::ALPHACHANNEL_SET' ) ? Imagick::ALPHACHANNEL_SET : Imagick::ALPHACHANNEL_ACTIVATE );
		$mask = new Imagick();
		$mask->newImage( $size_px, $size_px, new ImagickPixel( 'transparent' ) );
		$circle = new ImagickDraw();
		$circle->setFillColor( new ImagickPixel( 'white' ) );
		$circle->circle( $size_px / 2, $size_px / 2, $size_px / 2, 1 );
		$mask->drawImage( $circle );
		$av->compositeImage( $mask, Imagick::COMPOSITE_DSTIN, 0, 0 );
		$canvas->compositeImage( $av, Imagick::COMPOSITE_OVER, $pad, $h - $pad - $size_px + 10 );
		$name_x = $pad + $size_px + 22;
	}
	$canvas->annotateImage( $text( $bold, 28, '#ffffff' ), $name_x, $h - $pad - 30, 0, get_the_author_meta( 'display_name', $author_id ) );
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$canvas->annotateImage( $text( $med, 22, '#cbd5e1' ), $name_x, $h - $pad + 4, 0, fec_blog_title() . '  ·  ' . $host );

	$canvas->setImageFormat( 'jpeg' );
	$canvas->setImageCompressionQuality( 85 );
	$canvas->stripImage();
	return $canvas->getImageBlob();
}

/** Quebra o texto em linhas que caibam em $max_w pixels. */
function fec_og_wrap( Imagick $canvas, ImagickDraw $draw, string $text, int $max_w ): array {
	$lines = array();
	$line  = '';
	foreach ( preg_split( '/\s+/u', trim( $text ) ) as $word ) {
		$try = '' === $line ? $word : "$line $word";
		if ( '' !== $line && $canvas->queryFontMetrics( $draw, $try )['textWidth'] > $max_w ) {
			$lines[] = $line;
			$line    = $word;
		} else {
			$line = $try;
		}
	}
	if ( '' !== $line ) {
		$lines[] = $line;
	}
	return $lines;
}
