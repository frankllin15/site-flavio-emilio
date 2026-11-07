<?php
/**
 * Campos personalizados (Secure Custom Fields / ACF) registrados em código e versionados no Git.
 *
 * Chaves estáveis no formato field_fec_<grupo>_<campo>: não renomeie depois que houver
 * conteúdo em produção (o valor fica ligado à chave).
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Helpers para declarar campos de forma compacta
 * ---------------------------------------------------------------------- */

function fec_f( string $group, string $name, string $label, string $type, array $extra = array() ): array {
	return array_merge(
		array(
			'key'   => "field_fec_{$group}_{$name}",
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

function fec_tab( string $group, string $label ): array {
	return fec_f( $group, 'tab_' . sanitize_key( remove_accents( $label ) ), $label, 'tab', array( 'placement' => 'top' ) );
}

function fec_image( string $group, string $name, string $label, array $extra = array() ): array {
	return fec_f(
		$group,
		$name,
		$label,
		'image',
		array_merge(
			array(
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'library'       => 'all',
			),
			$extra
		)
	);
}

/** Repeater de uma coluna de texto (listas de tópicos, destaques...). */
function fec_list( string $group, string $name, string $label, string $item_label = 'Item', string $button = 'Adicionar item' ): array {
	return fec_f(
		$group,
		$name,
		$label,
		'repeater',
		array(
			'layout'       => 'table',
			'button_label' => $button,
			'sub_fields'   => array(
				fec_f( "{$group}_{$name}", 'texto', $item_label, 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
			),
		)
	);
}

function fec_location( string $post_type ): array {
	return array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => $post_type ) ) );
}

/* -------------------------------------------------------------------------
 * Página de opções + grupos de campos
 * ---------------------------------------------------------------------- */

add_action( 'acf/init', 'fec_register_fields' );

function fec_register_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => 'Conteúdo do Site',
				'menu_title' => 'Conteúdo do Site',
				'menu_slug'  => 'fec-conteudo',
				'capability' => 'edit_pages',
				'icon_url'   => 'dashicons-admin-home',
				'position'   => 3,
				'redirect'   => false,
			)
		);
	}

	/* ---------- Serviço ---------- */
	acf_add_local_field_group(
		array(
			'key'                   => 'group_fec_servico',
			'title'                 => 'Detalhes do serviço',
			'location'              => fec_location( 'servico' ),
			'position'              => 'normal',
			'instruction_placement' => 'field',
			'fields'                => array(
				array(
					'key'     => 'field_fec_servico_msg',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => 'A <strong>descrição</strong> do serviço é o texto do editor acima.',
				),
				fec_image( 'servico', 'logo', 'Logo', array( 'instructions' => 'Opcional. Sem logo, o card mostra o título sobre a cor abaixo.' ) ),
				fec_f(
					'servico',
					'cor',
					'Cor do card (sem logo)',
					'select',
					array(
						'choices'       => array(
							'blue'       => 'Azul',
							'gold'       => 'Dourado',
							'purple'     => 'Roxo',
							'terracotta' => 'Terracota',
							'gray'       => 'Cinza',
						),
						'default_value' => 'blue',
						'conditional_logic' => array( array( array( 'field' => 'field_fec_servico_logo', 'operator' => '==empty' ) ) ),
					)
				),
				fec_f( 'servico', 'subtitulo', 'Subtítulo da lista', 'text', array( 'instructions' => 'Ex.: "Atendemos casos como:"' ) ),
				fec_list( 'servico', 'topicos', 'Tópicos', 'Tópico', 'Adicionar tópico' ),
				fec_f(
					'servico',
					'pontos_aprendizado',
					'Pontos de aprendizado',
					'repeater',
					array(
						'instructions' => 'Use no lugar dos tópicos quando cada item tiver título e descrição.',
						'layout'       => 'block',
						'button_label' => 'Adicionar ponto',
						'sub_fields'   => array(
							fec_f( 'servico_pontos', 'titulo', 'Título', 'text' ),
							fec_f( 'servico_pontos', 'descricao', 'Descrição', 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
						),
					)
				),
				fec_f( 'servico', 'rodape', 'Texto de fechamento', 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
			),
		)
	);

	/* ---------- Palestra ---------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_palestra',
			'title'    => 'Detalhes da palestra',
			'location' => fec_location( 'palestra' ),
			'fields'   => array(
				array(
					'key'     => 'field_fec_palestra_msg',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => 'O texto do editor acima é a <strong>introdução</strong> da palestra.',
				),
				fec_image( 'palestra', 'logo', 'Logo da palestra', array( 'required' => 1 ) ),
				fec_f( 'palestra', 'subtitulo', 'Subtítulo da lista', 'text' ),
				fec_list( 'palestra', 'topicos', 'Tópicos', 'Tópico', 'Adicionar tópico' ),
				fec_f( 'palestra', 'conclusao', 'Conclusão', 'textarea', array( 'rows' => 4, 'new_lines' => '', 'required' => 1 ) ),
			),
		)
	);

	/* ---------- Evento ---------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_evento',
			'title'    => 'Detalhes do evento',
			'location' => fec_location( 'evento' ),
			'position' => 'side',
			'fields'   => array(
				fec_f( 'evento', 'local', 'Local', 'text', array( 'required' => 1 ) ),
				fec_f(
					'evento',
					'data',
					'Data',
					'date_picker',
					array(
						'required'       => 1,
						'display_format' => 'd/m/Y',
						'return_format'  => 'Ymd',
						'first_day'      => 0,
						'instructions'   => 'Usada para ordenar. No site aparece como "Novembro 2025".',
					)
				),
			),
		)
	);

	/* ---------- Livro ---------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_livro',
			'title'    => 'Detalhes do livro',
			'location' => fec_location( 'livro' ),
			'fields'   => array(
				array(
					'key'     => 'field_fec_livro_msg',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => 'Descrição no editor acima; a capa é a <strong>imagem destacada</strong>.',
				),
				fec_f( 'livro', 'subtitulo', 'Subtítulo', 'text' ),
				fec_f( 'livro', 'ano', 'Ano de publicação', 'number', array( 'min' => 1990, 'max' => 2100 ) ),
				fec_f( 'livro', 'link_compra', 'Link de compra', 'url' ),
				fec_list( 'livro', 'destaques', 'Destaques', 'Destaque', 'Adicionar destaque' ),
			),
		)
	);

	/* ---------- Cliente ---------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_cliente',
			'title'    => 'Dados do cliente',
			'location' => fec_location( 'cliente' ),
			'fields'   => array(
				fec_image( 'cliente', 'logo', 'Logo', array( 'instructions' => 'Sem logo, o nome aparece em texto.' ) ),
				fec_f( 'cliente', 'site', 'Site', 'url' ),
			),
		)
	);

	/* ---------- Depoimento ---------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_depoimento',
			'title'    => 'Depoimento',
			'location' => fec_location( 'depoimento' ),
			'fields'   => array(
				array(
					'key'     => 'field_fec_depoimento_msg',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => 'O <strong>título</strong> do post é o nome de quem deu o depoimento.',
				),
				fec_f( 'depoimento', 'citacao', 'Depoimento', 'textarea', array( 'required' => 1, 'rows' => 4, 'new_lines' => '' ) ),
				fec_f( 'depoimento', 'cargo', 'Cargo', 'text' ),
				fec_f( 'depoimento', 'empresa', 'Empresa', 'text' ),
				fec_image( 'depoimento', 'avatar', 'Foto' ),
			),
		)
	);

	/* ---------- Post do blog ---------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_post',
			'title'    => 'Blog',
			'location' => fec_location( 'post' ),
			'position' => 'side',
			'fields'   => array(
				fec_f( 'post', 'destaque', 'Destaque na home', 'true_false', array( 'ui' => 1 ) ),
			),
		)
	);

	/* ---------- Página de opções: Conteúdo do Site ---------- */
	$g = 'opt';
	acf_add_local_field_group(
		array(
			'key'      => 'group_fec_opcoes',
			'title'    => 'Conteúdo do Site',
			'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'fec-conteudo' ) ) ),
			'style'    => 'seamless',
			'fields'   => array(
				// Hero
				fec_tab( $g, 'Hero' ),
				fec_f( $g, 'hero_titulo', 'Título', 'text' ),
				fec_f( $g, 'hero_titulo_destaque', 'Título (parte em dourado)', 'text' ),
				fec_f( $g, 'hero_subtitulo', 'Subtítulo', 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
				fec_image( $g, 'hero_imagem_desktop', 'Imagem de fundo (desktop)' ),
				fec_image( $g, 'hero_imagem_mobile', 'Imagem de fundo (celular)' ),
				fec_f( $g, 'hero_cta1_texto', 'Botão 1: texto', 'text', array( 'wrapper' => array( 'width' => 50 ) ) ),
				fec_f( $g, 'hero_cta1_link', 'Botão 1: link', 'text', array( 'wrapper' => array( 'width' => 50 ) ) ),
				fec_f( $g, 'hero_cta2_texto', 'Botão 2: texto', 'text', array( 'wrapper' => array( 'width' => 50 ) ) ),
				fec_f( $g, 'hero_cta2_link', 'Botão 2: link', 'text', array( 'wrapper' => array( 'width' => 50 ) ) ),

				// Sobre
				fec_tab( $g, 'Sobre' ),
				fec_f( $g, 'sobre_titulo', 'Título', 'text' ),
				fec_f(
					$g,
					'sobre_texto',
					'Texto',
					'wysiwyg',
					array(
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Trechos em <strong>negrito</strong> aparecem em dourado no site.',
					)
				),
				fec_image( $g, 'sobre_imagem', 'Foto' ),
				fec_f(
					$g,
					'sobre_numeros',
					'Números em destaque',
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => 'Adicionar número',
						'max'          => 4,
						'sub_fields'   => array(
							fec_f( 'opt_numeros', 'numero', 'Número', 'text', array( 'instructions' => 'Ex.: 25+' ) ),
							fec_f( 'opt_numeros', 'rotulo', 'Rótulo', 'text' ),
						),
					)
				),

				// Proficient
				fec_tab( $g, 'Proficient' ),
				fec_image( $g, 'proficient_logo', 'Logo' ),
				fec_f( $g, 'proficient_missao', 'Missão', 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
				fec_f( $g, 'proficient_texto', 'Texto', 'textarea', array( 'rows' => 5, 'new_lines' => 'wpautop' ) ),
				fec_f( $g, 'proficient_valores_titulo', 'Título dos valores', 'text' ),
				fec_f(
					$g,
					'proficient_valores',
					'Valores',
					'repeater',
					array(
						'layout'       => 'table',
						'button_label' => 'Adicionar valor',
						'sub_fields'   => array(
							fec_f(
								'opt_valores',
								'icone',
								'Ícone',
								'select',
								array(
									'choices' => array(
										'shield'      => 'Escudo',
										'zap'         => 'Raio',
										'heart'       => 'Coração',
										'target'      => 'Alvo',
										'trending-up' => 'Gráfico subindo',
										'star'        => 'Estrela',
										'users'       => 'Pessoas',
									),
								)
							),
							fec_f( 'opt_valores', 'termo', 'Valor', 'text' ),
							fec_f( 'opt_valores', 'descricao', 'Descrição', 'text' ),
						),
					)
				),

				// Contato e redes
				fec_tab( $g, 'Contato' ),
				fec_f( $g, 'contato_titulo', 'Título', 'text' ),
				fec_f( $g, 'contato_texto', 'Texto', 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
				fec_f( $g, 'whatsapp_numero', 'WhatsApp (só números, com DDI)', 'text', array( 'instructions' => 'Ex.: 5584991056687' ) ),
				fec_f( $g, 'whatsapp_mensagem', 'Mensagem inicial do WhatsApp', 'text' ),
				fec_f( $g, 'email', 'E-mail', 'email' ),
				fec_f( $g, 'telefone', 'Telefone (como exibido)', 'text' ),
				fec_f( $g, 'localizacao', 'Localização', 'text' ),
				fec_f( $g, 'linkedin', 'LinkedIn', 'url' ),
				fec_f( $g, 'instagram', 'Instagram', 'url' ),
			),
		)
	);
}
