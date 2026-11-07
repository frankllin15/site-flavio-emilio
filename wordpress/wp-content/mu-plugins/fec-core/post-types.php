<?php
/**
 * Tipos de conteúdo personalizados.
 *
 * Slugs públicos iguais às rotas do app Next.js: /servicos/<slug>, /palestras/<slug>, /eventos.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'fec_register_post_types' );

function fec_register_post_types(): void {
	$types = array(
		'servico'    => array(
			'singular' => 'Serviço',
			'plural'   => 'Serviços',
			'icon'     => 'dashicons-portfolio',
			'public'   => true,
			'slug'     => 'servicos',
			'archive'  => false,
			'supports' => array( 'title', 'editor', 'page-attributes', 'revisions' ),
		),
		'palestra'   => array(
			'singular' => 'Palestra',
			'plural'   => 'Palestras',
			'icon'     => 'dashicons-megaphone',
			'public'   => true,
			'slug'     => 'palestras',
			'archive'  => false,
			'supports' => array( 'title', 'editor', 'page-attributes', 'revisions' ),
		),
		'evento'     => array(
			'singular' => 'Evento',
			'plural'   => 'Eventos',
			'icon'     => 'dashicons-calendar-alt',
			'public'   => true,
			'slug'     => 'eventos',
			'archive'  => 'eventos',
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		),
		'livro'      => array(
			'singular' => 'Livro',
			'plural'   => 'Livros',
			'icon'     => 'dashicons-book',
			'public'   => false,
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		),
		'cliente'    => array(
			'singular' => 'Cliente',
			'plural'   => 'Clientes',
			'icon'     => 'dashicons-building',
			'public'   => false,
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		),
		'depoimento' => array(
			'singular' => 'Depoimento',
			'plural'   => 'Depoimentos',
			'icon'     => 'dashicons-format-quote',
			'public'   => false,
			'supports' => array( 'title', 'page-attributes' ),
		),
	);

	foreach ( $types as $type => $t ) {
		$public = $t['public'];

		register_post_type(
			$type,
			array(
				'labels'              => array(
					'name'               => $t['plural'],
					'singular_name'      => $t['singular'],
					'add_new'            => 'Adicionar',
					'add_new_item'       => 'Adicionar ' . mb_strtolower( $t['singular'] ),
					'edit_item'          => 'Editar ' . mb_strtolower( $t['singular'] ),
					'new_item'           => 'Novo item',
					'view_item'          => 'Ver ' . mb_strtolower( $t['singular'] ),
					'search_items'       => 'Buscar ' . mb_strtolower( $t['plural'] ),
					'not_found'          => 'Nada encontrado',
					'not_found_in_trash' => 'Nada na lixeira',
					'all_items'          => 'Todos',
					'menu_name'          => $t['plural'],
				),
				'public'              => $public,
				'publicly_queryable'  => $public,
				'exclude_from_search' => ! $public,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => $public,
				'show_in_rest'        => true, // editor de blocos
				'menu_icon'           => $t['icon'],
				'menu_position'       => 21,
				'hierarchical'        => false,
				'has_archive'         => $t['archive'] ?? false,
				'rewrite'             => $public ? array( 'slug' => $t['slug'], 'with_front' => false ) : false,
				'query_var'           => $public,
				'supports'            => $t['supports'],
			)
		);
	}
}
