<?php
/**
 * Configuração de SEO (Yoast SEO) com os metadados do app Next (app/layout.tsx, páginas de serviço,
 * palestra e eventos). Incluído no fim de seed/import.php; usa os helpers e globais de lá.
 *
 * Roda uma vez (marca a opção fec_seo_seeded); `npm run seed:import -- atualizar` aplica de novo.
 * Depois disso, tudo é editável em SEO → Configurações e na caixa "Yoast SEO" de cada página.
 */

defined( 'ABSPATH' ) || exit;

global $fec_update;

if ( ! class_exists( 'WPSEO_Options' ) ) {
	WP_CLI::warning( 'Yoast SEO não está ativo: SEO não configurado. Rode `npm run setup`.' );
	return;
}
if ( get_option( 'fec_seo_seeded' ) && ! $fec_update ) {
	WP_CLI::log( '> SEO: já configurado (use "atualizar" para reaplicar)' );
	return;
}

WP_CLI::log( '> SEO (Yoast)' );

$fec_site    = 'Flávio Emílio Cavalcanti';
$fec_tagline = 'Transformando o potencial humano em resultados organizacionais através de liderança estratégica e gestão de carreira consciente.';
$fec_author  = get_user_by( 'login', 'flavio' );
$fec_photo   = (int) get_field( 'sobre_imagem', 'option' );
$fec_og      = fec_seed_image( '/seed/generated/og-image.jpg' );

$fec_settings = array(
	// Geral: "Página | Flávio Emílio Cavalcanti", como o template de título do Next
	'separator'                 => 'sc-pipe',
	'website_name'              => $fec_site,
	'alternate_website_name'    => 'Proficient',

	// Schema: o site representa uma pessoa (Flávio), com foto e redes sociais do perfil dele
	'company_or_person'         => 'person',
	'company_or_person_user_id' => $fec_author ? $fec_author->ID : 0,
	'person_logo'               => $fec_photo ? wp_get_attachment_url( $fec_photo ) : '',
	'person_logo_id'            => $fec_photo,

	// Serviços: "<título> | Proficient Consultoria | ..." e descrição = início do texto (como no Next)
	'title-servico'             => '%%title%% %%sep%% Proficient Consultoria %%sep%% %%sitename%%',
	'metadesc-servico'          => '%%excerpt%%',
	'title-palestra'            => '%%title%% %%sep%% %%sitename%%',
	'metadesc-palestra'         => '%%excerpt%%',
	'metadesc-post'             => '%%excerpt%%',

	// /eventos (app/eventos/page.tsx); a página individual de evento redireciona para lá: fora do índice
	'title-ptarchive-evento'              => 'Eventos Realizados %%sep%% %%sitename%%',
	'metadesc-ptarchive-evento'           => 'Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações em todo o Brasil.',
	'social-title-ptarchive-evento'       => 'Eventos Realizados %%sep%% %%sitename%%',
	'social-description-ptarchive-evento' => 'Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações.',
	'noindex-evento'                      => true,

	// Um autor só: arquivo de autor e de data duplicariam o blog
	'disable-author'            => true,
	'disable-date'              => true,

	// Compartilhamento: imagem padrão (o Next apontava para /images/og-image.jpg, que não existia)
	'opengraph'                 => true,
	'twitter'                   => true,
	'og_default_image'          => $fec_og ? wp_get_attachment_url( $fec_og ) : '',
	'og_default_image_id'       => $fec_og ?: '',
);

foreach ( $fec_settings as $fec_key => $fec_value ) {
	WPSEO_Options::set( $fec_key, $fec_value );
}

// Redes sociais do Flávio (entram no schema Person como sameAs).
if ( $fec_author ) {
	update_user_meta( $fec_author->ID, 'linkedin', (string) get_field( 'linkedin', 'option' ) );
	update_user_meta( $fec_author->ID, 'instagram', (string) get_field( 'instagram', 'option' ) );
	update_user_meta( $fec_author->ID, 'description', 'Administrador, educador e consultor organizacional com mais de 25 anos de experiência em gestão de pessoas, liderança e carreira.' );
}

// Home: título, descrição e cartões sociais de app/layout.tsx.
$fec_front = (int) get_option( 'page_on_front' );
if ( $fec_front ) {
	$fec_meta = array(
		'_yoast_wpseo_title'                 => $fec_site . ' %%sep%% Consultor Organizacional e Especialista em Gestão de Carreiras',
		'_yoast_wpseo_metadesc'              => $fec_tagline . ' Consultoria, palestras e treinamentos in-company.',
		'_yoast_wpseo_opengraph-title'       => $fec_site . ' | Consultor Organizacional',
		'_yoast_wpseo_opengraph-description' => $fec_tagline,
		'_yoast_wpseo_twitter-title'         => $fec_site . ' | Consultor Organizacional',
		'_yoast_wpseo_twitter-description'   => 'Transformando o potencial humano em resultados organizacionais',
	);
	foreach ( $fec_meta as $fec_key => $fec_value ) {
		update_post_meta( $fec_front, $fec_key, $fec_value );
	}
}

$fec_blog = (int) get_option( 'page_for_posts' );
if ( $fec_blog ) {
	update_post_meta( $fec_blog, '_yoast_wpseo_metadesc', 'Insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional.' );
}

update_option( 'fec_seo_seeded', time() );

// O Yoast guarda os dados de SEO em "indexables": refaz para refletir a configuração nova.
WP_CLI::runcommand(
	'yoast index --reindex --skip-confirmation',
	array(
		'launch'     => false,
		'exit_error' => false,
		'return'     => 'all',
	)
);
