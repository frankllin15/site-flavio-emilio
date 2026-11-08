<?php
/**
 * Newsletter "Drops de Carreira" (MailPoet), docs/plano-blog.md fase B5.
 *
 * - Envio: o MailPoet usa o próprio mecanismo de envio (não passa pelo phpmailer_init do WordPress).
 *   Quando FEC_SMTP_HOST está definido, copiamos o SMTP do site (constantes FEC_SMTP_*) para ele.
 *   Alternativa em produção: o "MailPoet Sending Service" (MailPoet → Configurações → Envio).
 * - Inscrição: endpoint POST /wp-json/fec/v1/newsletter usado pelo formulário do tema, com
 *   confirmação dupla, honeypot, tempo mínimo de preenchimento e limite por IP.
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * SMTP do site → MailPoet
 * ---------------------------------------------------------------------- */

add_action(
	'admin_init',
	static function () {
		fec_mailpoet_sync_smtp();
	}
);
add_action( 'mailpoet_initialized', 'fec_mailpoet_sync_smtp' );

function fec_mailpoet_sync_smtp(): void {
	static $done = false;
	$host = fec_config( 'FEC_SMTP_HOST' );
	if ( $done || '' === $host || ! class_exists( '\MailPoet\DI\ContainerWrapper' ) ) {
		return;
	}
	$done = true;

	$user = fec_config( 'FEC_SMTP_USER' );
	$want = array(
		'method'         => 'SMTP',
		'host'           => $host,
		'port'           => fec_config( 'FEC_SMTP_PORT', '587' ),
		'encryption'     => fec_config( 'FEC_SMTP_SECURE' ),
		'authentication' => '' !== $user ? '1' : '-1',
		'login'          => $user,
		'password'       => fec_config( 'FEC_SMTP_PASS' ),
	);

	try {
		$settings = \MailPoet\DI\ContainerWrapper::getInstance()->get( \MailPoet\Settings\SettingsController::class );
	} catch ( Throwable $e ) {
		return;
	}
	$current = (array) $settings->get( 'mta' );

	// Respeita quem escolheu o serviço de envio do próprio MailPoet no painel.
	if ( ( $current['method'] ?? '' ) === 'MailPoet' ) {
		return;
	}
	if ( array_intersect_key( $current, $want ) == $want && $settings->get( 'mta_group' ) === 'smtp' ) { // phpcs:ignore Universal.Operators.StrictComparisons
		return;
	}

	$settings->set( 'mta_group', 'smtp' );
	$settings->set(
		'mta',
		array_merge(
			array( 'frequency' => array( 'emails' => 25, 'interval' => 5 ) ),
			$current,
			$want
		)
	);
}

/*
 * O MailPoet não envia newsletters fora de produção (WP_ENVIRONMENT_TYPE !== 'production'):
 * uma cópia em staging não dispara e-mails para os inscritos reais. Mantemos isso, e liberamos
 * só no Docker local (onde todo e-mail cai no Mailpit) com FEC_DEV_ALLOW_NEWSLETTER=1.
 */
add_filter(
	'mailpoet_allow_sending_in_non_production',
	static function ( $allowed ) {
		return $allowed || ( 'local' === wp_get_environment_type() && '1' === fec_config( 'FEC_DEV_ALLOW_NEWSLETTER' ) );
	}
);

/* -------------------------------------------------------------------------
 * Inscrição: POST /wp-json/fec/v1/newsletter
 * ---------------------------------------------------------------------- */

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'fec/v1',
			'/newsletter',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true', // inscrição pública
				'callback'            => 'fec_newsletter_subscribe',
				'args'                => array(
					'email'     => array( 'type' => 'string', 'required' => true ),
					'nome'      => array( 'type' => 'string' ),
					'aceite'    => array( 'type' => 'boolean' ),
					'site'      => array( 'type' => 'string' ), // honeypot: precisa vir vazio
					'iniciado'  => array( 'type' => 'integer' ), // timestamp (ms) de quando o form apareceu
					'origem'    => array( 'type' => 'string' ),
				),
			)
		);
	}
);

function fec_newsletter_subscribe( WP_REST_Request $req ) {
	$ok = static fn() => new WP_REST_Response(
		array(
			'ok'      => true,
			'message' => 'Quase lá! Enviamos um e-mail de confirmação. Clique no link dele para começar a receber os textos.',
		)
	);
	$fail = static fn( string $msg, int $status = 400 ) => new WP_REST_Response( array( 'ok' => false, 'message' => $msg ), $status );

	// Robôs: campo invisível preenchido ou envio rápido demais. Responde "ok" para não ensinar o robô.
	$elapsed = (int) ( microtime( true ) * 1000 ) - (int) $req['iniciado'];
	if ( '' !== trim( (string) $req['site'] ) || ( $req['iniciado'] && $elapsed < 2500 ) ) {
		return $ok();
	}

	$email = sanitize_email( (string) $req['email'] );
	if ( ! is_email( $email ) ) {
		return $fail( 'Confira o e-mail digitado.' );
	}
	if ( ! $req['aceite'] ) {
		return $fail( 'Para se inscrever, marque a caixa de concordância.' );
	}

	// Limite: 5 inscrições por IP a cada 10 minutos (evita usar o formulário para disparar e-mails).
	$ip_key = 'fec_nl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) ); // phpcs:ignore
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		return $fail( 'Muitas tentativas seguidas. Tente de novo em alguns minutos.', 429 );
	}
	set_transient( $ip_key, $count + 1, 10 * MINUTE_IN_SECONDS );

	$list_id = (int) get_option( 'fec_newsletter_list_id' );
	if ( ! $list_id || ! class_exists( '\MailPoet\API\API' ) ) {
		return $fail( 'A inscrição está indisponível no momento. Tente mais tarde.', 503 );
	}

	$api     = \MailPoet\API\API::MP( 'v1' );
	$options = array(
		'send_confirmation_email' => true,
		'schedule_welcome_email'  => true,
	);

	try {
		try {
			$subscriber = $api->getSubscriber( $email );
		} catch ( \MailPoet\API\MP\v1\APIException $e ) {
			$subscriber = null;
		}

		if ( $subscriber ) {
			// Já cadastrado: (re)inscreve na lista; se ainda não confirmou, o MailPoet reenvia a confirmação.
			$in_list = in_array( $list_id, array_map( 'intval', wp_list_pluck( $subscriber['subscriptions'] ?? array(), 'segment_id' ) ), true );
			if ( ! $in_list || 'subscribed' !== $subscriber['status'] ) {
				$api->subscribeToList( $subscriber['id'], $list_id, $options );
			}
		} else {
			$api->addSubscriber(
				array(
					'email'      => $email,
					'first_name' => sanitize_text_field( (string) $req['nome'] ),
				),
				array( $list_id ),
				$options
			);
		}
	} catch ( Throwable $e ) {
		error_log( 'fec_newsletter_subscribe: ' . $e->getMessage() ); // phpcs:ignore
		// Mensagem genérica: não revelar se o e-mail já estava cadastrado.
		return $ok();
	}

	return $ok();
}
