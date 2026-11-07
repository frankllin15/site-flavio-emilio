<?php
/**
 * Proficient (components/sections/Proficient.tsx).
 */

defined( 'ABSPATH' ) || exit;

$fec_logo   = fec_option( 'proficient_logo' );
$fec_values = fec_option( 'proficient_valores', array() );
?>
<section id="proficient" class="relative section-spacing bg-white overflow-hidden">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
			<div class="order-1">
				<?php if ( $fec_logo ) : ?>
					<div class="flex justify-center lg:justify-start mb-8">
						<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'h-16 w-auto', 'alt' => 'Proficient' ) ); ?>
					</div>
				<?php endif; ?>

				<p class="text-xl md:text-2xl text-center lg:text-left text-gray-700 font-medium mb-8 leading-relaxed">
					<?php echo esc_html( fec_option( 'proficient_missao' ) ); ?>
				</p>

				<div class="text-center lg:text-left text-lg text-gray-700 leading-relaxed space-y-4">
					<?php echo wp_kses_post( fec_option( 'proficient_texto' ) ); ?>
				</div>
			</div>

			<div class="order-2">
				<h3 class="text-2xl md:text-3xl font-bold text-center lg:text-left text-gray-900 mb-8">
					<?php echo esc_html( fec_option( 'proficient_valores_titulo', 'Nossos Valores' ) ); ?>
				</h3>

				<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
					<?php foreach ( $fec_values as $fec_value ) : ?>
						<div class="flex items-start gap-4 group hover:transform hover:-translate-x-1 transition-all duration-300">
							<div class="w-12 h-12 flex-shrink-0 bg-accent-gold-100 rounded-full flex items-center justify-center group-hover:bg-accent-gold-200 transition-colors">
								<?php echo fec_icon( $fec_value['icone'] ?: 'star', 'w-7 h-7 text-accent-gold-500' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</div>
							<div>
								<h4 class="text-lg font-semibold text-gray-900 mb-1"><?php echo esc_html( $fec_value['termo'] ); ?></h4>
								<p class="text-gray-600 leading-relaxed"><?php echo esc_html( $fec_value['descricao'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<?php fec_wave( 'white-to-gray' ); ?>
</section>
