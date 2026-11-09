<?php
/**
 * Somente Docker local: acesso pelo Cloudflare Tunnel (`npm run share`).
 *
 * Incluído pelo wp-config.php (WORDPRESS_CONFIG_EXTRA no docker-compose.yml), antes de o WordPress
 * carregar. Quando a requisição chega por um endereço *.trycloudflare.com:
 * - o site passa a gerar links com esse endereço (https), e não com http://localhost:8080;
 * - links gravados no conteúdo com o endereço local são trocados na saída;
 * - login, painel e XML-RPC ficam bloqueados (o ambiente local usa admin/admin);
 * - as páginas vão com noindex, para o endereço temporário não aparecer no Google.
 * Pelo localhost, nada muda.
 */

$fec_share_host = strtolower( (string) ( $_SERVER['HTTP_HOST'] ?? '' ) );

if ( preg_match( '/^[a-z0-9-]+\.trycloudflare\.com$/', $fec_share_host ) ) {
	$_SERVER['HTTPS'] = 'on'; // o túnel entrega HTTPS ao visitante e HTTP ao Apache
	$fec_share_url    = 'https://' . $fec_share_host;

	define( 'WP_HOME', $fec_share_url );
	define( 'WP_SITEURL', $fec_share_url );
	define( 'FEC_SHARE', true );

	// Só leitura: nada de login ou painel pelo endereço público.
	$fec_share_path = (string) parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
	$fec_blocked    = preg_match( '#/(wp-login\.php|xmlrpc\.php)$#', $fec_share_path )
		|| ( preg_match( '#^/wp-admin(/|$)#', $fec_share_path ) && ! preg_match( '#/admin-ajax\.php$#', $fec_share_path ) );
	if ( $fec_blocked ) {
		http_response_code( 403 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		exit( "Acesso ao painel desativado no link de demonstração.\n" );
	}

	header( 'X-Robots-Tag: noindex, nofollow' );

	// Conteúdo salvo com o endereço local (imagens nos posts, links do menu etc.).
	$fec_local_url = rtrim( (string) getenv( 'FEC_LOCAL_URL' ), '/' );
	if ( '' !== $fec_local_url ) {
		ob_start(
			static function ( string $html ) use ( $fec_local_url, $fec_share_url ): string {
				return str_replace(
					array( $fec_local_url, str_replace( '/', '\/', $fec_local_url ) ),
					array( $fec_share_url, str_replace( '/', '\/', $fec_share_url ) ),
					$html
				);
			}
		);
	}
}
