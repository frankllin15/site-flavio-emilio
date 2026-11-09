<?php
/**
 * Política de Privacidade (docs/plano-blog.md, fase B7). Incluído no fim de seed/import.php.
 *
 * O texto (seed/privacy-policy.html) é um TEXTO-BASE descrevendo o que o site realmente coleta;
 * precisa ser revisado pelo cliente (idealmente por um advogado) antes de ir ao ar.
 * Roda uma vez (opção fec_privacy_seeded); `npm run seed:import -- atualizar` reaplica.
 */

defined( 'ABSPATH' ) || exit;

global $fec_update;

if ( get_option( 'fec_privacy_seeded' ) && ! $fec_update ) {
	WP_CLI::log( '> Privacidade: já configurada (use "atualizar" para reaplicar)' );
	return;
}

WP_CLI::log( '> Política de Privacidade' );

$fec_email   = (string) get_field( 'email', 'option' );
$fec_content = strtr(
	(string) file_get_contents( __DIR__ . '/privacy-policy.html' ),
	array(
		'{email}' => esc_html( $fec_email ),
		'{data}'  => esc_html( wp_date( 'j \d\e F \d\e Y' ) ),
	)
);

// Reaproveita a página de privacidade criada na instalação (rascunho em inglês) ou cria uma.
$fec_page_id = (int) get_option( 'wp_page_for_privacy_policy' );
$fec_page    = array(
	'post_type'    => 'page',
	'post_title'   => 'Política de Privacidade',
	'post_name'    => 'politica-de-privacidade',
	'post_content' => $fec_content,
	'post_status'  => 'publish',
);
if ( $fec_page_id && get_post( $fec_page_id ) ) {
	$fec_page['ID'] = $fec_page_id;
	wp_update_post( $fec_page );
} else {
	$fec_page_id = (int) wp_insert_post( $fec_page );
}
update_option( 'wp_page_for_privacy_policy', $fec_page_id );
update_post_meta( $fec_page_id, '_yoast_wpseo_metadesc', 'Como o site de Flávio Emílio Cavalcanti coleta, usa e protege seus dados pessoais, e como exercer seus direitos pela LGPD.' );

// Prazos citados na política: inscrições não confirmadas apagadas em 30 dias (inativos: 365, padrão).
if ( class_exists( '\MailPoet\DI\ContainerWrapper' ) ) {
	$fec_mp_settings = \MailPoet\DI\ContainerWrapper::getInstance()->get( \MailPoet\Settings\SettingsController::class );
	$fec_mp_settings->set( 'delete_unconfirmed_subscribers_after_days', '30' );
	$fec_mp_settings->set( 'deactivate_subscriber_after_inactive_days', '365' );
}

update_option( 'fec_privacy_seeded', time() );
WP_CLI::log( '  publicada: ' . get_permalink( $fec_page_id ) );
