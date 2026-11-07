<?php
/**
 * Imagens: miniaturas geradas em WebP (bem menores que JPEG/PNG, com transparência).
 *
 * O original enviado fica como está (JPEG/PNG); o WordPress usa as miniaturas WebP no srcset,
 * então o navegador baixa a versão leve no tamanho certo. Para converter imagens já enviadas:
 *   npm run wp -- media regenerate --yes
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'image_editor_output_format',
	static function ( array $formats ): array {
		if ( ! wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
			return $formats; // servidor sem WebP: mantém o formato original
		}
		$formats['image/jpeg'] = 'image/webp';
		$formats['image/png']  = 'image/webp';
		return $formats;
	}
);

// Qualidade das miniaturas WebP (padrão do WP é 86 para WebP; 80 é visualmente igual e menor).
add_filter(
	'wp_editor_set_quality',
	static function ( $quality, $mime_type ) {
		return 'image/webp' === $mime_type ? 80 : $quality;
	},
	10,
	2
);
