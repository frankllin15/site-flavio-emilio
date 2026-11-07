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
		$font = FEC_THEME_URI . '/assets/fonts/geist-latin-wght-normal.woff2';
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $font ) );
	},
	1
);

require FEC_THEME_DIR . '/inc/template-tags.php';
