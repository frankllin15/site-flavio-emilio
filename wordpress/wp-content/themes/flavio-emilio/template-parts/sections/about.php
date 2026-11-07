<?php
/**
 * Sobre (components/sections/About.tsx).
 */

defined( 'ABSPATH' ) || exit;

$fec_image   = fec_option( 'sobre_imagem' );
$fec_numbers = fec_option( 'sobre_numeros', array() );
?>
<section id="sobre" class="section-spacing bg-gray-50">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
			<div class="order-2 lg:order-1 flex flex-col justify-between h-full md:pt-32">
				<div class="relative">
					<div class="aspect-square rounded-2xl overflow-hidden shadow-2xl">
						<div class="relative w-full h-full bg-linear-to-br from-brand-blue-200 to-brand-blue-400 flex items-center justify-center">
							<?php
							if ( $fec_image ) {
								echo wp_get_attachment_image(
									$fec_image,
									'large',
									false,
									array(
										'class' => 'absolute inset-0 w-full h-full object-cover',
										'alt'   => get_bloginfo( 'name' ),
										'sizes' => '(max-width: 768px) 100vw, 50vw',
									)
								);
							}
							?>
						</div>
					</div>
					<div class="absolute -bottom-6 -right-6 w-48 h-48 bg-accent-gold-400 rounded-2xl -z-10 hidden md:block"></div>
				</div>

				<?php if ( $fec_numbers ) : ?>
					<div class="grid grid-cols-3 gap-6 mt-10">
						<?php foreach ( $fec_numbers as $fec_number ) : ?>
							<div class="text-center">
								<div class="text-4xl font-bold text-primary mb-2"><?php echo esc_html( $fec_number['numero'] ); ?></div>
								<div class="text-sm text-gray-600 uppercase tracking-wider"><?php echo esc_html( $fec_number['rotulo'] ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="order-1 lg:order-2">
				<?php fec_section_heading( array( 'title' => fec_option( 'sobre_titulo', 'Sobre' ), 'centered' => false ) ); ?>

				<?php /* Trechos em negrito no editor aparecem em dourado, como os <span> do original. */ ?>
				<div class="space-y-6 text-gray-700 text-lg leading-relaxed border-l-4 border-accent-gold-400 pl-6 [&_strong]:text-accent-gold-500 [&_strong]:font-medium">
					<?php echo wp_kses_post( fec_option( 'sobre_texto' ) ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
