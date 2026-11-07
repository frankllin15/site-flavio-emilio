<?php
/**
 * Desempenho: só carregar o que a página usa.
 */

defined( 'ABSPATH' ) || exit;

/*
 * Contact Form 7 (CSS, JS e o script do Turnstile da Cloudflare): por padrão o CF7 carrega em
 * todas as páginas. Aqui só carrega quando o formulário é exibido (template-parts/sections/contact.php
 * chama fec_enqueue_contact_form()).
 */
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

add_action(
	'wp_enqueue_scripts',
	static function () {
		// O Turnstile do CF7 enfileira o api.js da Cloudflare por conta própria.
		if ( ! fec_contact_form_visible() ) {
			wp_dequeue_script( 'cloudflare-turnstile' );
			wp_dequeue_script( 'wpcf7-turnstile' );
		}
	},
	100
);

/** O formulário de contato vai aparecer nesta página? */
function fec_contact_form_visible(): bool {
	return is_front_page() && fec_option( 'contato_mostrar_formulario' ) && fec_option( 'contato_formulario' );
}

/** Carrega CSS/JS do CF7 (chamado pela seção de contato ao exibir o formulário). */
function fec_enqueue_contact_form(): void {
	if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
		wpcf7_enqueue_scripts();
	}
	if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
		wpcf7_enqueue_styles();
	}
}

/*
 * Lazy load por padrão. O WordPress só decide sozinho para imagens dentro do loop/conteúdo; as dos
 * templates (eventos, clientes, livros...) saíam sem `loading`. As imagens do topo da página
 * (hero, logo de serviço/palestra, capa do post) passam loading="eager" explicitamente.
 */
add_filter(
	'wp_get_attachment_image_attributes',
	static function ( array $attr ): array {
		if ( empty( $attr['loading'] ) && ( $attr['fetchpriority'] ?? '' ) !== 'high' ) {
			$attr['loading'] = 'lazy';
		}
		$attr['decoding'] ??= 'async';
		return $attr;
	},
	20
);

// CSS de blocos só dos blocos usados no conteúdo da página (em vez da biblioteca inteira).
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

// Emojis: o WordPress injeta script + CSS em toda página para desenhar emojis antigos.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );

// Links no <head> sem uso neste site.
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
