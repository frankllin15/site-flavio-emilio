<?php
/**
 * Hero (components/sections/Hero.tsx).
 */

defined( 'ABSPATH' ) || exit;

$fec_desktop = fec_option( 'hero_imagem_desktop' );
$fec_mobile  = fec_option( 'hero_imagem_mobile' );

/*
 * A imagem fica "contida" numa área de 100vw × 100vh (min-h-screen): se for mais alta que larga em
 * proporção à tela, a largura real é altura × proporção. Informar isso no `sizes` faz o navegador
 * baixar um arquivo do tamanho exibido, e não do tamanho da tela inteira.
 */
$fec_hero_sizes = static function ( $id ): string {
	$meta = wp_get_attachment_metadata( $id );
	if ( empty( $meta['width'] ) || empty( $meta['height'] ) ) {
		return '100vw';
	}
	$vh = (int) ceil( 100 * $meta['width'] / $meta['height'] );
	return sprintf( '(min-aspect-ratio: %d/%d) %dvh, 100vw', $meta['width'], $meta['height'], $vh );
};
?>
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden bg-brand-blue-900">
	<?php
	// <picture>: o navegador baixa só a imagem do tamanho de tela atual (com duas <img> e
	// display:none, as duas eram baixadas). A imagem de celular é a <img>; a de desktop, o <source>.
	$fec_img_id = $fec_mobile ?: $fec_desktop;
	if ( $fec_img_id ) :
		?>
		<picture>
			<?php if ( $fec_desktop && $fec_mobile ) : ?>
				<source media="(min-width: 768px)"
					srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $fec_desktop, 'full' ) ); ?>"
					sizes="<?php echo esc_attr( $fec_hero_sizes( $fec_desktop ) ); ?>">
			<?php endif; ?>
			<?php
			echo wp_get_attachment_image(
				$fec_img_id,
				'full',
				false,
				array(
					'class'         => 'absolute inset-0 h-full w-full object-contain object-left-top',
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'decoding'      => 'async',
					'sizes'         => $fec_hero_sizes( $fec_img_id ),
				)
			);
			?>
		</picture>
	<?php endif; ?>

	<div class="relative z-10 container mx-auto px-4 pt-32 sm:px-6 lg:px-8 lg:pt-32">
		<div class="max-w-3xl ml-auto">
			<div class="text-white py-8 pb-20">
				<h1 class="text-2xl md:text-4xl xl:text-5xl font-bold mb-6 leading-tight">
					<?php echo esc_html( fec_option( 'hero_titulo' ) ); ?>
					<span class="text-accent-gold-400"><?php echo esc_html( fec_option( 'hero_titulo_destaque' ) ); ?></span>
				</h1>

				<p class="text-lg md:text-xl lg:text-2xl mb-12 text-gray-100 leading-relaxed">
					<?php echo esc_html( fec_option( 'hero_subtitulo' ) ); ?>
				</p>

				<div class="flex flex-col sm:flex-row gap-4">
					<?php if ( fec_option( 'hero_cta1_texto' ) ) : ?>
						<a href="<?php echo esc_url( fec_option( 'hero_cta1_link', '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'accent', 'lg', 'text-base md:text-lg px-8 py-4' ) ); ?>">
							<?php echo esc_html( fec_option( 'hero_cta1_texto' ) ); ?>
						</a>
					<?php endif; ?>
					<?php if ( fec_option( 'hero_cta2_texto' ) ) : ?>
						<a href="<?php echo esc_url( fec_option( 'hero_cta2_link', '/#livros' ) ); ?>" class="<?php echo esc_attr( fec_btn( '', 'lg', 'text-base md:text-lg px-8 py-4 bg-white text-primary hover:bg-gray-100 border-white rounded-lg hover:-translate-y-0.5' ) ); ?>">
							<?php echo esc_html( fec_option( 'hero_cta2_texto' ) ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
		<a href="#sobre" aria-label="Rolar para baixo">
			<svg class="w-6 h-6 text-white" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
		</a>
	</div>
</section>
