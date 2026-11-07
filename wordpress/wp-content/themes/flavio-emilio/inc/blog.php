<?php
/**
 * Blog: helpers dos templates (docs/plano-blog.md, fases B1–B2).
 */

defined( 'ABSPATH' ) || exit;

/** Nome do blog (Conteúdo do Site → Blog). */
function fec_blog_title(): string {
	return (string) fec_option( 'blog_titulo', 'Blog' );
}

function fec_blog_url(): string {
	$page = (int) get_option( 'page_for_posts' );
	return $page ? get_permalink( $page ) : home_url( '/' );
}

/** Foto do autor (campo "Foto do autor" no perfil), com as iniciais como alternativa. */
function fec_author_avatar( int $user_id, string $class = 'w-12 h-12', string $size = 'thumbnail' ): string {
	$photo = function_exists( 'get_field' ) ? (int) get_field( 'fec_foto', 'user_' . $user_id ) : 0;
	if ( $photo ) {
		return wp_get_attachment_image( $photo, $size, false, array( 'class' => "{$class} rounded-full object-cover shrink-0", 'alt' => '' ) );
	}
	$name = get_the_author_meta( 'display_name', $user_id );
	return sprintf(
		'<span class="%s rounded-full bg-brand-blue-500 text-white font-bold flex items-center justify-center shrink-0" aria-hidden="true">%s</span>',
		esc_attr( $class ),
		esc_html( fec_initials( $name ) )
	);
}

/** Categoria principal do post (a primeira, ignorando "Sem categoria"). */
function fec_post_category( $post = null ): ?WP_Term {
	foreach ( get_the_category( get_post( $post )->ID ) as $cat ) {
		if ( (int) $cat->term_id !== (int) get_option( 'default_category' ) ) {
			return $cat;
		}
	}
	return null;
}

/**
 * Conteúdo do post renderizado + lista de subtítulos (H2) para o índice.
 * Garante um id em cada H2 (âncora do índice) sem depender de plugin.
 *
 * @return array{html:string, toc:array<int, array{id:string, text:string}>}
 */
function fec_post_content_with_toc(): array {
	$html = apply_filters( 'the_content', get_the_content() );
	$toc  = array();
	$used = array();

	$html = preg_replace_callback(
		'#<h2([^>]*)>(.*?)</h2>#is',
		static function ( $m ) use ( &$toc, &$used ) {
			$attrs = $m[1];
			$text  = trim( wp_strip_all_tags( $m[2] ) );
			if ( preg_match( '/\bid=["\']([^"\']+)["\']/', $attrs, $idm ) ) {
				$id = $idm[1];
			} else {
				$base = sanitize_title( $text ) ?: 'secao';
				$id   = $base;
				for ( $n = 2; isset( $used[ $id ] ); $n++ ) {
					$id = "{$base}-{$n}";
				}
				$attrs .= ' id="' . esc_attr( $id ) . '"';
			}
			$used[ $id ] = true;
			$toc[]       = array(
				'id'   => $id,
				'text' => $text,
			);
			return "<h2{$attrs}>{$m[2]}</h2>";
		},
		$html
	);

	return array(
		'html' => $html,
		'toc'  => $toc,
	);
}

/**
 * Posts relacionados: mesma categoria primeiro, completando com os mais recentes.
 *
 * @return WP_Post[]
 */
function fec_related_posts( int $post_id, int $limit = 3 ): array {
	$cats    = wp_get_post_categories( $post_id );
	$related = $cats ? get_posts(
		array(
			'category__in'   => $cats,
			'post__not_in'   => array( $post_id ),
			'posts_per_page' => $limit,
			'no_found_rows'  => true,
		)
	) : array();

	if ( count( $related ) < $limit ) {
		$related = array_merge(
			$related,
			get_posts(
				array(
					'post__not_in'   => array_merge( array( $post_id ), wp_list_pluck( $related, 'ID' ) ),
					'posts_per_page' => $limit - count( $related ),
					'no_found_rows'  => true,
				)
			)
		);
	}
	return $related;
}

/** Post em destaque do topo do blog: o "Post em destaque" mais recente, ou o mais recente de todos. */
function fec_featured_post(): ?WP_Post {
	$flagged = get_posts(
		array(
			'posts_per_page' => 1,
			'meta_key'       => 'destaque', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'no_found_rows'  => true,
		)
	);
	if ( $flagged ) {
		return $flagged[0];
	}
	$latest = get_posts( array( 'posts_per_page' => 1, 'no_found_rows' => true ) );
	return $latest[0] ?? null;
}

/*
 * Breadcrumb do post: Início › Blog › Categoria › Post. O Yoast só monta a categoria quando os
 * "indexables" dele existem (em produção); aqui garantimos o mesmo resultado em qualquer ambiente.
 */
add_filter(
	'wpseo_breadcrumb_links',
	static function ( array $links ): array {
		if ( ! is_singular( 'post' ) ) {
			return $links;
		}
		$cat = fec_post_category();
		if ( ! $cat ) {
			return $links;
		}
		$cat_url = get_category_link( $cat );
		foreach ( $links as $link ) {
			if ( ( $link['url'] ?? '' ) === $cat_url ) {
				return $links;
			}
		}
		array_splice( $links, count( $links ) - 1, 0, array( array( 'url' => $cat_url, 'text' => $cat->name ) ) );
		return $links;
	}
);

/*
 * Consultas do blog: 9 posts por página (grade de 3 colunas) e busca restrita a posts.
 */
add_action(
	'pre_get_posts',
	static function ( WP_Query $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_home() || $q->is_category() || $q->is_tag() || $q->is_search() ) {
			$q->set( 'posts_per_page', 9 );
		}
		if ( $q->is_search() ) {
			$q->set( 'post_type', 'post' );
		}
	}
);
