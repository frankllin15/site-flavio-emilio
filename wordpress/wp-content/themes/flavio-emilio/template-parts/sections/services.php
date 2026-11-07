<?php
/**
 * Serviços (components/sections/Services.tsx).
 */

defined( 'ABSPATH' ) || exit;

$fec_services = fec_get_items( 'servico' );
if ( ! $fec_services ) {
	return;
}
?>
<section id="servicos" class="relative section-spacing bg-gray-50 overflow-hidden">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'         => 'O que oferecemos',
				'subtitle'      => 'Estratégias personalizadas para desenvolver pessoas e transformar organizações',
				'badge'         => 'Soluções',
				'badge_variant' => 'primary',
				'icon'          => 'briefcase',
			)
		);

		fec_carousel_start( 'Serviço anterior', 'Próximo serviço', 'Serviços' );
		foreach ( $fec_services as $fec_service ) :
			$fec_logo = fec_field( 'logo', $fec_service->ID );
			?>
			<div class="<?php echo esc_attr( fec_carousel_item_class() ); ?>">
				<div class="<?php echo esc_attr( fec_card_class( 'flex flex-col h-full' ) ); ?>">
					<div class="mb-4">
						<?php if ( $fec_logo ) : ?>
							<div class="mb-4 flex justify-center">
								<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'h-20 w-auto object-contain', 'alt' => get_the_title( $fec_service ) ) ); ?>
							</div>
						<?php else : ?>
							<h3 class="text-2xl md:text-3xl font-extrabold font-display text-center text-slate-700 mb-4 tracking-tight uppercase">
								<?php echo esc_html( get_the_title( $fec_service ) ); ?>
							</h3>
						<?php endif; ?>
					</div>

					<div class="text-gray-600 flex-1 flex flex-col">
						<p class="text-gray-600 mb-6 text-sm md:text-base leading-relaxed line-clamp-4">
							<?php echo esc_html( fec_plain_content( $fec_service ) ); ?>
						</p>
						<div class="mt-auto">
							<a href="<?php echo esc_url( get_permalink( $fec_service ) ); ?>" class="<?php echo esc_attr( fec_btn( 'outline', 'default', 'w-full rounded-md' ) ); ?>">
								Ver Detalhes<span class="sr-only">: <?php echo esc_html( get_the_title( $fec_service ) ); ?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
		endforeach;
		fec_carousel_end();
		?>

		<div class="text-center mt-12">
			<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg' ) ); ?>">Solicitar Proposta</a>
		</div>
	</div>

	<?php fec_wave( 'gray-to-white' ); ?>
</section>
