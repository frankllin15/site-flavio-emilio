<?php
/**
 * E-mail (SMTP) e anti-spam do formulário, configurados fora do banco.
 *
 * Produção: constantes no wp-config.php (ver wordpress/README.md, seção "Produção").
 * Docker local: variáveis de ambiente do docker-compose.yml (SMTP → Mailpit, chaves de teste do Turnstile).
 *
 * Substitui o plugin WP Mail SMTP: menos um plugin e a senha do SMTP não fica no banco.
 */

defined( 'ABSPATH' ) || exit;

/** Lê FEC_* de uma constante (wp-config.php) ou, na falta dela, do ambiente. */
function fec_config( string $name, string $default = '' ): string {
	if ( defined( $name ) ) {
		return (string) constant( $name );
	}
	$env = getenv( $name );
	return false === $env ? $default : (string) $env;
}

/* -------------------------------------------------------------------------
 * SMTP
 *   FEC_SMTP_HOST, FEC_SMTP_PORT (587), FEC_SMTP_SECURE (tls|ssl|vazio),
 *   FEC_SMTP_USER, FEC_SMTP_PASS
 * ---------------------------------------------------------------------- */

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) {
		$host = fec_config( 'FEC_SMTP_HOST' );
		if ( '' === $host ) {
			return; // sem SMTP configurado: usa o envio padrão do servidor
		}

		$phpmailer->isSMTP();
		$phpmailer->Host        = $host;
		$phpmailer->Port        = (int) fec_config( 'FEC_SMTP_PORT', '587' );
		$phpmailer->SMTPSecure  = fec_config( 'FEC_SMTP_SECURE' );
		// Mesmo sem FEC_SMTP_SECURE, usa STARTTLS quando o servidor oferece (porta 587): a senha
		// nunca vai em texto puro. Servidores sem TLS (Mailpit) continuam funcionando.
		$phpmailer->SMTPAutoTLS = true;

		$user = fec_config( 'FEC_SMTP_USER' );
		if ( '' !== $user ) {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $user;
			$phpmailer->Password = fec_config( 'FEC_SMTP_PASS' );
		} else {
			$phpmailer->SMTPAuth = false;
		}
	}
);

/* -------------------------------------------------------------------------
 * Remetente: FEC_MAIL_FROM (endereço autorizado no SMTP/SPF) e FEC_MAIL_FROM_NAME.
 * Vale para todo e-mail do site, inclusive o do Contact Form 7 (o "responder para"
 * continua sendo quem preencheu o formulário).
 * ---------------------------------------------------------------------- */

add_filter(
	'wp_mail_from',
	static function ( $from ) {
		$custom = fec_config( 'FEC_MAIL_FROM' );
		return is_email( $custom ) ? $custom : $from;
	}
);

add_filter(
	'wp_mail_from_name',
	static function ( $name ) {
		$custom = fec_config( 'FEC_MAIL_FROM_NAME' );
		return '' !== $custom ? $custom : $name;
	}
);

/* -------------------------------------------------------------------------
 * E-mail HTML do Contact Form 7: mantém as quebras de linha das áreas de texto
 * (o valor já chega escapado pelo CF7; como no route.ts do Next, \n vira <br>).
 * ---------------------------------------------------------------------- */

add_filter(
	'wpcf7_mail_tag_replaced',
	static function ( $replaced, $submitted, $html, $mail_tag ) {
		if ( ! $html || ! is_object( $mail_tag ) || ! method_exists( $mail_tag, 'corresponding_form_tag' ) ) {
			return $replaced;
		}
		$form_tag = $mail_tag->corresponding_form_tag();
		return ( $form_tag && 'textarea' === $form_tag->basetype ) ? nl2br( $replaced, false ) : $replaced;
	},
	10,
	4
);

/* -------------------------------------------------------------------------
 * Cloudflare Turnstile no Contact Form 7: FEC_TURNSTILE_SITEKEY e FEC_TURNSTILE_SECRET.
 * Sem as duas, vale o que estiver salvo em Contato → Integração (ou nada).
 * ---------------------------------------------------------------------- */

add_filter(
	'wpcf7_turnstile_sitekey',
	static function ( $sitekey ) {
		$custom = fec_config( 'FEC_TURNSTILE_SITEKEY' );
		return ( '' !== $custom && '' !== fec_config( 'FEC_TURNSTILE_SECRET' ) ) ? $custom : $sitekey;
	}
);

add_filter(
	'wpcf7_turnstile_secret',
	static function ( $secret ) {
		$custom = fec_config( 'FEC_TURNSTILE_SECRET' );
		return ( '' !== $custom && '' !== fec_config( 'FEC_TURNSTILE_SITEKEY' ) ) ? $custom : $secret;
	}
);
