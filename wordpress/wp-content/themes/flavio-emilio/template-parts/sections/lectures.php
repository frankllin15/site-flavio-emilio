<?php
/**
 * Palestras (components/sections/Lectures.tsx).
 */

defined( 'ABSPATH' ) || exit;

$fec_lectures = fec_get_items( 'palestra' );
if ( ! $fec_lectures ) {
	return;
}
?>
<section id="palestras" class="relative section-spacing bg-white overflow-hidden">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'         => 'Nossas Palestras',
				'subtitle'      => 'Conteúdos inspiradores que transformam perspectivas e impulsionam resultados',
				'badge'         => 'Palestras',
				'badge_variant' => 'secondary',
				'icon'          => 'presentation',
			)
		);

		fec_carousel_start( 'Palestra anterior', 'Próxima palestra' );
		foreach ( $fec_lectures as $fec_lecture ) :
			$fec_logo = fec_field( 'logo', $fec_lecture->ID );
			?>
			<div class="<?php echo esc_attr( fec_carousel_item_class() ); ?>">
				<div class="<?php echo esc_attr( fec_card_class( 'flex flex-col h-full' ) ); ?>">
					<div class="mb-4">
						<?php if ( $fec_logo ) : ?>
							<div class="mb-4 flex justify-center">
								<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'h-20 w-auto object-contain', 'alt' => get_the_title( $fec_lecture ) ) ); ?>
							</div>
						<?php endif; ?>
						<h3 class="text-xl font-bold text-center text-foreground"><?php echo esc_html( get_the_title( $fec_lecture ) ); ?></h3>
					</div>

					<div class="text-gray-600 flex-1 flex flex-col">
						<p class="text-gray-600 mb-6 text-sm md:text-base leading-relaxed line-clamp-4">
							<?php echo esc_html( fec_plain_content( $fec_lecture ) ); ?>
						</p>
						<div class="mt-auto">
							<a href="<?php echo esc_url( get_permalink( $fec_lecture ) ); ?>" class="<?php echo esc_attr( fec_btn( 'outline', 'default', 'w-full rounded-md' ) ); ?>">
								Ver Detalhes<span class="sr-only">: <?php echo esc_html( get_the_title( $fec_lecture ) ); ?></span>
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
			<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg' ) ); ?>">Solicitar Palestra</a>
		</div>
	</div>

	<?php fec_wave( 'white-to-gray' ); ?>
</section>
