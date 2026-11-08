<?php
/**
 * Newsletter "Drops de Carreira" (MailPoet), docs/plano-blog.md fase B5.
 * Incluído no fim de seed/import.php. Roda uma vez (opção fec_newsletter_seeded);
 * `npm run seed:import -- atualizar` aplica de novo.
 *
 * O envio (SMTP) NÃO é configurado aqui: fec-core/newsletter.php copia as constantes FEC_SMTP_*
 * para o MailPoet, como faz para o resto do site.
 */

defined( 'ABSPATH' ) || exit;

global $fec_update;

if ( ! class_exists( '\MailPoet\API\API' ) ) {
	WP_CLI::warning( 'MailPoet não está ativo: newsletter não configurada. Rode `npm run setup`.' );
	return;
}
if ( get_option( 'fec_newsletter_seeded' ) && ! $fec_update ) {
	WP_CLI::log( '> Newsletter: já configurada (use "atualizar" para reaplicar)' );
	return;
}

WP_CLI::log( '> Newsletter (MailPoet)' );

$fec_mp       = \MailPoet\DI\ContainerWrapper::getInstance();
$fec_settings = $fec_mp->get( \MailPoet\Settings\SettingsController::class );
$fec_from     = fec_config( 'FEC_MAIL_FROM' ) ?: get_option( 'admin_email' );
$fec_reply    = (string) get_field( 'email', 'option' );
$fec_blog     = (string) ( get_field( 'blog_titulo', 'option' ) ?: 'Drops de Carreira' );

$fec_settings->set( 'sender', array( 'name' => 'Flávio Emílio Cavalcanti', 'address' => $fec_from ) );
if ( is_email( $fec_reply ) ) {
	$fec_settings->set( 'reply_to', array( 'name' => 'Flávio Emílio Cavalcanti', 'address' => $fec_reply ) );
}

// Confirmação dupla (LGPD): só recebe quem clicar no link do e-mail de confirmação.
$fec_confirm             = (array) $fec_settings->get( 'signup_confirmation' );
$fec_confirm['enabled']  = true;
$fec_confirm['subject']  = "Confirme sua inscrição no {$fec_blog}";
$fec_settings->set( 'signup_confirmation', $fec_confirm );

// Aberturas e cliques contados sem cookies no site (assim o site continua sem banner de cookies).
$fec_settings->set( 'tracking', array( 'level' => 'partial', 'consent' => array( 'subscriber_choice' => 'track_all' ) ) );

// Avisa o Flávio a cada nova inscrição.
if ( is_email( $fec_reply ) ) {
	$fec_settings->set( 'subscriber_email_notification', array( 'enabled' => true, 'address' => $fec_reply ) );
}

// Não mostrar a tela de boas-vindas/assistente do MailPoet no painel.
$fec_settings->set( 'version', defined( 'MAILPOET_VERSION' ) ? MAILPOET_VERSION : '5' );
$fec_settings->set( 'show_congratulate_after_first_newsletter', false );
$fec_settings->set( 'welcome_wizard_current_step', '' );
$fec_settings->set( '3rd_party_libs', array( 'enabled' => '0' ) );
$fec_settings->set( 'analytics', array( 'enabled' => false ) );

// Lista única: renomeia a lista padrão do MailPoet.
$fec_api   = \MailPoet\API\API::MP( 'v1' );
$fec_lists = $fec_api->getLists();
$fec_list  = null;
foreach ( $fec_lists as $l ) {
	if ( $l['name'] === $fec_blog ) {
		$fec_list = $l;
	}
}
if ( ! $fec_list && $fec_lists ) {
	$fec_list = $fec_api->updateList(
		array(
			'id'          => $fec_lists[0]['id'],
			'name'        => $fec_blog,
			'description' => 'Avisos de novos artigos do blog ' . $fec_blog . '.',
		)
	);
} elseif ( ! $fec_list ) {
	$fec_list = $fec_api->addList( array( 'name' => $fec_blog, 'description' => 'Avisos de novos artigos do blog ' . $fec_blog . '.' ) );
}
update_option( 'fec_newsletter_list_id', (int) $fec_list['id'] );

// Página de gerenciar inscrição/descadastro criada pelo MailPoet: título em português.
$fec_pages = (array) ( $fec_settings->get( 'subscription' )['pages'] ?? array() );
if ( ! empty( $fec_pages['manage'] ) ) {
	wp_update_post( array( 'ID' => (int) $fec_pages['manage'], 'post_title' => 'Sua inscrição' ) );
}

/* -------------------------------------------------------------------------
 * E-mail automático de novo post ("Post Notification" do MailPoet), na identidade do site.
 * Editável depois em MailPoet → E-mails → Notificações de posts.
 * ---------------------------------------------------------------------- */

$fec_blog_url = get_permalink( (int) get_option( 'page_for_posts' ) ) ?: home_url( '/' );
$fec_linkedin = (string) get_field( 'linkedin', 'option' );

$fec_text_block = static fn( string $html ) => array(
	'type' => 'text',
	'text' => $html,
);
$fec_spacer = static fn( int $h, string $bg = 'transparent' ) => array(
	'type'   => 'spacer',
	'styles' => array( 'block' => array( 'backgroundColor' => $bg, 'height' => $h . 'px' ) ),
);
$fec_column = static fn( array $blocks, string $bg = 'transparent' ) => array(
	'type'        => 'container',
	'orientation' => 'horizontal',
	'styles'      => array( 'block' => array( 'backgroundColor' => $bg ) ),
	'blocks'      => array(
		array(
			'type'        => 'container',
			'orientation' => 'vertical',
			'styles'      => array( 'block' => array( 'backgroundColor' => 'transparent' ) ),
			'blocks'      => $blocks,
		),
	),
);

$fec_body = array(
	'content'      => array(
		'type'        => 'container',
		'orientation' => 'vertical',
		'styles'      => array( 'block' => array( 'backgroundColor' => 'transparent' ) ),
		'blocks'      => array(
			// "Ver no navegador"
			$fec_column(
				array(
					array(
						'type'   => 'header',
						'text'   => '<p><a href="[link:newsletter_view_in_browser_url]">Ver este e-mail no navegador</a></p>',
						'styles' => array(
							'block' => array( 'backgroundColor' => 'transparent' ),
							'text'  => array( 'fontColor' => '#6b7280', 'fontFamily' => 'Arial', 'fontSize' => '12px', 'textAlign' => 'center' ),
							'link'  => array( 'fontColor' => '#6b7280', 'textDecoration' => 'underline' ),
						),
					),
				),
				'#f3f4f6'
			),
			// Faixa da marca: azul profundo + linha dourada (como o hero do site)
			$fec_column(
				array(
					$fec_spacer( 28, '#172554' ),
					$fec_text_block( '<p style="text-align: center; letter-spacing: 2px;"><span style="color: #fbbf24;"><strong>FLÁVIO EMÍLIO CAVALCANTI</strong></span></p><h1 style="text-align: center;"><span style="color: #ffffff;">' . esc_html( $fec_blog ) . '</span></h1>' ),
					array(
						'type'   => 'divider',
						'styles' => array(
							'block' => array(
								'backgroundColor' => '#172554',
								'padding'         => '4px',
								'borderStyle'     => 'solid',
								'dividerWidth'    => '3px',
								'borderColor'     => '#fbbf24',
							),
						),
					),
					$fec_spacer( 20, '#172554' ),
				),
				'#172554'
			),
			$fec_column(
				array(
					$fec_spacer( 24 ),
					$fec_text_block( '<p>Olá, [subscriber:firstname | default:leitor(a)]!</p><p>Tem texto novo no ' . esc_html( $fec_blog ) . ', com ideias práticas para aplicar já:</p>' ),
				),
				'#ffffff'
			),
			// Post(s) publicado(s) desde o último envio
			array(
				'type'                     => 'automatedLatestContentLayout',
				'withLayout'               => true,
				'amount'                   => '1', // um post por e-mail (envio imediato a cada publicação)
				'contentType'              => 'post',
				'terms'                    => array(),
				'inclusionType'            => 'include',
				'displayType'              => 'excerpt',
				'titleFormat'              => 'h2',
				'titleAlignment'           => 'left',
				'titleIsLink'              => true,
				'imageFullWidth'           => true,
				'featuredImagePosition'    => 'aboveTitle',
				'fullPostFeaturedImagePosition' => 'aboveTitle',
				'showAuthor'               => 'no',
				'authorPrecededBy'         => 'Por:',
				'showCategories'           => 'no',
				'categoriesPrecededBy'     => 'Categorias:',
				'readMoreType'             => 'button',
				'readMoreText'             => 'Ler o texto completo',
				'readMoreButton'           => array(
					'type'   => 'button',
					'text'   => 'Ler o texto completo',
					'url'    => '[postLink]',
					'styles' => array(
						'block' => array(
							'backgroundColor' => '#1e40af',
							'borderColor'     => '#1e40af',
							'borderWidth'     => '0px',
							'borderRadius'    => '8px',
							'borderStyle'     => 'solid',
							'width'           => '240px',
							'lineHeight'      => '46px',
							'fontColor'       => '#ffffff',
							'fontFamily'      => 'Arial',
							'fontSize'        => '16px',
							'fontWeight'      => 'bold',
							'textAlign'       => 'left',
						),
					),
				),
				'sortBy'                   => 'newest',
				'showDivider'              => true,
				'divider'                  => array(
					'type'   => 'divider',
					'styles' => array(
						'block' => array(
							'backgroundColor' => 'transparent',
							'padding'         => '16px',
							'borderStyle'     => 'solid',
							'dividerWidth'    => '1px',
							'borderColor'     => '#e5e7eb',
						),
					),
				),
				'backgroundColor'          => '#ffffff',
				'backgroundColorAlternate' => '#ffffff',
			),
			$fec_column(
				array(
					$fec_text_block( '<p>Se o texto fizer sentido para alguém da sua equipe, encaminhe este e-mail. 🙂</p><p>Até o próximo,<br><strong>Flávio Emílio Cavalcanti</strong></p>' ),
					$fec_spacer( 16 ),
				),
				'#ffffff'
			),
			// Rodapé com descadastro (obrigatório) e links
			$fec_column(
				array(
					$fec_spacer( 16, '#f3f4f6' ),
					array(
						'type'   => 'footer',
						'text'   => '<p><a href="' . esc_url( $fec_blog_url ) . '">Todos os textos do ' . esc_html( $fec_blog ) . '</a>' . ( $fec_linkedin ? ' · <a href="' . esc_url( $fec_linkedin ) . '">LinkedIn</a>' : '' ) . '</p><p>Você recebe este e-mail porque se inscreveu no ' . esc_html( $fec_blog ) . ' em [site:homepage_link].</p><p><a href="[link:subscription_unsubscribe_url]">Cancelar inscrição</a> · <a href="[link:subscription_manage_url]">Gerenciar inscrição</a></p>',
						'styles' => array(
							'block' => array( 'backgroundColor' => 'transparent' ),
							'text'  => array( 'fontColor' => '#6b7280', 'fontFamily' => 'Arial', 'fontSize' => '12px', 'textAlign' => 'center' ),
							'link'  => array( 'fontColor' => '#1e40af', 'textDecoration' => 'underline' ),
						),
					),
					$fec_spacer( 16, '#f3f4f6' ),
				),
				'#f3f4f6'
			),
		),
	),
	'globalStyles' => array(
		'text'    => array( 'fontColor' => '#374151', 'fontFamily' => 'Arial', 'fontSize' => '16px', 'lineHeight' => '1.6' ),
		'h1'      => array( 'fontColor' => '#172554', 'fontFamily' => 'Trebuchet MS', 'fontSize' => '32px', 'lineHeight' => '1.3' ),
		'h2'      => array( 'fontColor' => '#111827', 'fontFamily' => 'Trebuchet MS', 'fontSize' => '24px', 'lineHeight' => '1.3' ),
		'h3'      => array( 'fontColor' => '#111827', 'fontFamily' => 'Trebuchet MS', 'fontSize' => '20px', 'lineHeight' => '1.3' ),
		'link'    => array( 'fontColor' => '#1e40af', 'textDecoration' => 'underline' ),
		'wrapper' => array( 'backgroundColor' => '#ffffff' ),
		'body'    => array( 'backgroundColor' => '#f3f4f6' ),
	),
);

$fec_nl_data = array(
	'type'             => 'notification',
	'subject'          => '[newsletter:post_title]',
	'preheader'        => 'Texto novo no ' . $fec_blog,
	'body'             => wp_json_encode( $fec_body ),
	'segments'         => array( array( 'id' => (int) $fec_list['id'] ) ),
	'sender_name'      => 'Flávio Emílio Cavalcanti',
	'sender_address'   => $fec_from,
	'reply_to_name'    => is_email( $fec_reply ) ? 'Flávio Emílio Cavalcanti' : '',
	'reply_to_address' => is_email( $fec_reply ) ? $fec_reply : '',
	'options'          => array(
		'intervalType' => 'immediately', // um e-mail por publicação
		'timeOfDay'    => 0,
		'weekDay'      => 0,
		'monthDay'     => 0,
		'nthWeekDay'   => 1,
		'schedule'     => '* * * * *',
	),
);

$fec_nl_id = (int) get_option( 'fec_newsletter_notification_id' );
$fec_repo  = $fec_mp->get( \MailPoet\Newsletter\NewslettersRepository::class );
if ( $fec_nl_id && $fec_repo->findOneById( $fec_nl_id ) ) {
	$fec_nl_data['id'] = $fec_nl_id;
}
$fec_nl = $fec_mp->get( \MailPoet\Newsletter\NewsletterSaveController::class )->save( $fec_nl_data );
$fec_nl->setStatus( \MailPoet\Entities\NewsletterEntity::STATUS_ACTIVE );
$fec_repo->flush();
update_option( 'fec_newsletter_notification_id', $fec_nl->getId() );
WP_CLI::log( '  e-mail de novo post: id ' . $fec_nl->getId() . ' (' . $fec_nl->getStatus() . ')' );

// Páginas de confirmação/descadastro fora do Google.
if ( class_exists( 'WPSEO_Options' ) ) {
	WPSEO_Options::set( 'noindex-mailpoet_page', true );
}

update_option( 'fec_newsletter_seeded', time() );
WP_CLI::log( "  lista \"{$fec_list['name']}\" (id {$fec_list['id']}), remetente {$fec_from}" );
