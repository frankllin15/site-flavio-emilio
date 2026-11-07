<?php
/**
 * Helpers de template.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lê um campo da página de opções "Conteúdo do Site" (SCF/ACF), com fallback seguro
 * caso o plugin de campos esteja desativado.
 */
function fec_option( string $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, 'option' );
	return ( null === $value || '' === $value || false === $value ) ? $default : $value;
}

/**
 * Lê um campo de um post, com fallback.
 */
function fec_field( string $name, $post_id = null, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, $post_id ?? get_the_ID() );
	return ( null === $value || '' === $value || false === $value ) ? $default : $value;
}

/**
 * Menu do header como links simples (<a>), mantendo as classes do layout original.
 */
function fec_menu_links( string $location, string $link_class = '' ): void {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return;
	}
	$items = wp_get_nav_menu_items( $locations[ $location ] );
	foreach ( (array) $items as $item ) {
		printf(
			'<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( $item->url ),
			esc_attr( $link_class ),
			esc_html( $item->title )
		);
	}
}
