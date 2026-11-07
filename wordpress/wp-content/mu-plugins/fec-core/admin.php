<?php
/**
 * Ajustes do painel para facilitar a edição pelo cliente.
 */

defined( 'ABSPATH' ) || exit;

// Aviso se o plugin de campos estiver desativado (sem ele os campos e a página de opções somem).
add_action(
	'admin_notices',
	static function () {
		if ( function_exists( 'acf_add_local_field_group' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		echo '<div class="notice notice-error"><p><strong>FEC Core:</strong> ative o plugin <em>Secure Custom Fields</em> (ou ACF). Sem ele os campos do site não aparecem.</p></div>';
	}
);

// Imagick sem suporte a JPEG (caso da imagem Alpine do WP-CLI no Docker): usa o GD para gerar as miniaturas.
add_filter(
	'wp_image_editors',
	static function ( $editors ) {
		if ( class_exists( 'Imagick' ) && ! Imagick::queryFormats( 'JPEG' ) ) {
			$editors = array_values( array_diff( $editors, array( 'WP_Image_Editor_Imagick' ) ) );
		}
		return $editors;
	}
);

// Títulos de exemplo mais claros no editor.
add_filter(
	'enter_title_here',
	static function ( $title, $post ) {
		$map = array(
			'depoimento' => 'Nome de quem deu o depoimento',
			'cliente'    => 'Nome da empresa',
			'evento'     => 'Título do evento',
		);
		return $map[ $post->post_type ] ?? $title;
	},
	10,
	2
);

// Coluna "Data do evento" e ordenação por ela na listagem de eventos.
add_filter(
	'manage_evento_posts_columns',
	static function ( $cols ) {
		$cols['fec_data'] = 'Data do evento';
		return $cols;
	}
);
add_action(
	'manage_evento_posts_custom_column',
	static function ( $col, $post_id ) {
		if ( 'fec_data' === $col ) {
			$raw = get_post_meta( $post_id, 'data', true );
			echo $raw ? esc_html( wp_date( 'd/m/Y', strtotime( $raw ) ) ) : '—';
		}
	},
	10,
	2
);

// Listagens do painel na mesma ordem do site.
add_action(
	'pre_get_posts',
	static function ( WP_Query $q ) {
		if ( ! is_admin() || ! $q->is_main_query() || $q->get( 'orderby' ) ) {
			return;
		}
		$type = $q->get( 'post_type' );
		if ( 'evento' === $type ) {
			$q->set( 'meta_key', 'data' );
			$q->set( 'orderby', 'meta_value' );
			$q->set( 'order', 'DESC' );
		} elseif ( in_array( $type, array( 'servico', 'palestra', 'livro', 'cliente', 'depoimento' ), true ) ) {
			$q->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		}
	}
);
