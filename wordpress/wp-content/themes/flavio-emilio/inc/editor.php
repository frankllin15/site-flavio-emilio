<?php
/**
 * Editor de blocos na identidade do site (docs/plano-blog.md, fase B3):
 * o que o Flávio vê ao escrever é o que sai no site, e só com recursos que não quebram o visual.
 * Cores e fontes do editor: theme.json.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/dist/editor.css' );

		// Sem os padrões genéricos do WordPress (e sem buscar padrões no diretório online).
		remove_theme_support( 'core-block-patterns' );
	}
);
add_filter( 'should_load_remote_block_patterns', '__return_false' );

/*
 * O theme.json existe para o EDITOR. No site, os estilos globais gerados a partir dele (ex.:
 * `a:where(...) { color }` fora das camadas do Tailwind) venceriam as classes utilitárias e
 * mudariam a cor de todos os links/botões. O WordPress os enfileira no <head> e de novo no rodapé
 * (temas clássicos com CSS de blocos sob demanda), então os dois ganchos saem no front.
 */
add_action(
	'init',
	static function () {
		if ( is_admin() ) {
			return;
		}
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
		remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	}
);

// O theme.json declara as fontes para o editor; no site elas já vêm do main.css.
add_action(
	'wp_head',
	static function () {
		remove_action( 'wp_head', 'wp_print_font_faces', 50 );
	},
	1
);

/* -------------------------------------------------------------------------
 * Estilos de bloco (visual em assets/src/blocks.css)
 * ---------------------------------------------------------------------- */

add_action(
	'init',
	static function () {
		register_block_style(
			'core/quote',
			array(
				'name'  => 'fec-destaque',
				'label' => 'Destaque',
			)
		);
		register_block_style(
			'core/group',
			array(
				'name'  => 'fec-dica',
				'label' => 'Dica prática',
			)
		);

		/* ---------- Padrões prontos ("Inserir" → Padrões → Flávio Emílio) ---------- */
		register_block_pattern_category( 'fec', array( 'label' => 'Flávio Emílio' ) );

		register_block_pattern(
			'fec/dica-pratica',
			array(
				'title'       => 'Dica prática',
				'description' => 'Caixa azul com o rótulo "Dica prática".',
				'categories'  => array( 'fec' ),
				'content'     => '<!-- wp:group {"className":"is-style-fec-dica"} --><div class="wp-block-group is-style-fec-dica"><!-- wp:paragraph --><p>Escreva aqui uma ação simples que o leitor pode aplicar hoje.</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
			)
		);

		register_block_pattern(
			'fec/destaque',
			array(
				'title'       => 'Frase em destaque',
				'description' => 'Citação grande, centralizada, entre linhas douradas.',
				'categories'  => array( 'fec' ),
				'content'     => '<!-- wp:quote {"className":"is-style-fec-destaque"} --><blockquote class="wp-block-quote is-style-fec-destaque"><!-- wp:paragraph --><p>Uma frase forte que resume a ideia do artigo.</p><!-- /wp:paragraph --></blockquote><!-- /wp:quote -->',
			)
		);

		register_block_pattern(
			'fec/resumo',
			array(
				'title'       => 'Resumo em tópicos',
				'description' => 'Subtítulo "Em resumo" + lista com ✓.',
				'categories'  => array( 'fec' ),
				'content'     => '<!-- wp:heading --><h2 class="wp-block-heading">Em resumo</h2><!-- /wp:heading --><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Primeiro ponto principal.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Segundo ponto principal.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Terceiro ponto principal.</li><!-- /wp:list-item --></ul><!-- /wp:list -->',
			)
		);

		register_block_pattern(
			'fec/chamada-contato',
			array(
				'title'       => 'Chamada para contato',
				'description' => 'Parágrafo + botão para a seção de contato do site.',
				'categories'  => array( 'fec' ),
				'content'     => '<!-- wp:group {"className":"is-style-fec-dica"} --><div class="wp-block-group is-style-fec-dica"><!-- wp:paragraph --><p><strong>Quer levar esse tema para a sua equipe?</strong> Converse comigo sobre palestras e treinamentos in-company.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/#contato">Fale comigo</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
			)
		);
	}
);

/* -------------------------------------------------------------------------
 * Blocos permitidos nos posts: o essencial para artigos, sem colunas/capas/estilos livres.
 * ---------------------------------------------------------------------- */

add_filter(
	'allowed_block_types_all',
	static function ( $allowed, $context ) {
		if ( empty( $context->post ) || 'post' !== $context->post->post_type ) {
			return $allowed;
		}
		return array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/quote',
			'core/image',
			'core/gallery',
			'core/embed',
			'core/video',
			'core/separator',
			'core/table',
			'core/group',
			'core/buttons',
			'core/button',
			'core/details',
			'core/block',   // blocos reutilizáveis (padrões sincronizados)
			'core/pattern',
		);
	},
	10,
	2
);

// Subtítulos só de H2 a H4: o H1 é o título do post (e o índice usa os H2).
add_filter(
	'register_block_type_args',
	static function ( $args, $name ) {
		if ( 'core/heading' === $name ) {
			$args['attributes']['levelOptions']['default'] = array( 2, 3, 4 );
		}
		return $args;
	},
	10,
	2
);
