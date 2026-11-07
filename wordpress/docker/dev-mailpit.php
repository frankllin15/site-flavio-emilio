<?php
/**
 * Plugin Name: Dev — Mailpit SMTP
 * Description: Somente no Docker local. Envia todo e-mail do WordPress para o Mailpit (http://localhost:8025).
 */

if ( ! getenv( 'FEC_DEV_SMTP_HOST' ) || wp_get_environment_type() !== 'local' ) {
	return;
}

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) {
		$phpmailer->isSMTP();
		$phpmailer->Host     = getenv( 'FEC_DEV_SMTP_HOST' );
		$phpmailer->Port     = (int) getenv( 'FEC_DEV_SMTP_PORT' );
		$phpmailer->SMTPAuth = false;
		$phpmailer->SMTPAutoTLS = false;
	}
);
