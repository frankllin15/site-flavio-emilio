<?php
/**
 * Dados estruturados (Yoast): o Flávio como pessoa com cargo, ligada à Proficient Consultoria.
 * Sem isto, o Yoast usa a foto dele como "logo" e não diz o que ele faz nem onde trabalha.
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'wpseo_schema_person',
	static function ( array $data ): array {
		$data['jobTitle'] = 'Consultor Organizacional';
		$data['worksFor'] = array(
			'@type' => 'Organization',
			'name'  => 'Proficient Consultoria',
		);

		$logo = function_exists( 'get_field' ) ? (int) get_field( 'proficient_logo', 'option' ) : 0;
		if ( $logo ) {
			$data['worksFor']['logo'] = wp_get_attachment_url( $logo );
			// O logo da marca vale para o site como publicador; a foto continua em "image".
			$data['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => wp_get_attachment_url( $logo ),
			);
		}
		return $data;
	}
);
