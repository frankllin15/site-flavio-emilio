<?php
/**
 * Tema Flávio Emílio.
 *
 * Tipos de conteúdo e campos ficam no mu-plugin fec-core (não aqui), para que o conteúdo
 * continue existindo mesmo se o tema for trocado.
 */

defined( 'ABSPATH' ) || exit;

define( 'FEC_THEME_DIR', get_template_directory() );
define( 'FEC_THEME_URI', get_template_directory_uri() );

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' ); // anuncia o RSS do blog no <head> (leitores de feed acham sozinhos)
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'responsive-embeds' );

		register_nav_menus(
			array(
				'primary' => 'Menu principal (header)',
				'footer'  => 'Menu do rodapé',
			)
		);

		add_image_size( 'fec-card', 800, 600, true );
		add_image_size( 'fec-logo', 400, 160, false );
	}
);

add_action(
	'wp_enqueue_scripts',
	static function () {
		$css = '/assets/dist/main.css';
		$js  = '/assets/dist/main.js';

		if ( file_exists( FEC_THEME_DIR . $css ) ) {
			wp_enqueue_style( 'fec-main', FEC_THEME_URI . $css, array(), (string) filemtime( FEC_THEME_DIR . $css ) );
		}
		if ( file_exists( FEC_THEME_DIR . $js ) ) {
			wp_enqueue_script(
				'fec-main',
				FEC_THEME_URI . $js,
				array(),
				(string) filemtime( FEC_THEME_DIR . $js ),
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
		}

		// Sem Gutenberg no front além do conteúdo de posts: remove CSS global de blocos não usado.
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
);

add_action(
	'wp_head',
	static function () {
		// Fonte do texto (Geist) e dos títulos (Montserrat): baixar cedo evita troca visível de fonte.
		foreach ( array( 'geist-latin-wght-normal.woff2', 'montserrat-latin-wght-normal.woff2' ) as $font ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( FEC_THEME_URI . '/assets/fonts/' . $font ) );
		}
	},
	1
);

// Eventos não têm página própria (como no app Next): o link individual leva à lista em /eventos.
add_action(
	'template_redirect',
	static function () {
		if ( is_singular( 'evento' ) ) {
			wp_safe_redirect( get_post_type_archive_link( 'evento' ), 301 );
			exit;
		}
	}
);

// Resumo automático mais curto e sem "[...]".
add_filter( 'excerpt_length', static fn() => 30 );
add_filter( 'excerpt_more', static fn() => '…' );

require FEC_THEME_DIR . '/inc/template-tags.php';
require FEC_THEME_DIR . '/inc/performance.php';
require FEC_THEME_DIR . '/inc/blog.php';
require FEC_THEME_DIR . '/inc/editor.php';
require FEC_THEME_DIR . '/inc/og-post.php';
