<?php
/**
 * Depoimentos (components/sections/Testimonials.tsx). Some da home se não houver depoimento publicado.
 */

defined( 'ABSPATH' ) || exit;

$fec_testimonials = fec_get_items( 'depoimento' );
if ( ! $fec_testimonials ) {
	return;
}
?>
<section id="testimonials" class="section-spacing bg-gray-50">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'         => 'Depoimentos',
				'subtitle'      => 'O que nossos clientes dizem sobre nós',
				'badge'         => 'Feedback',
				'badge_variant' => 'secondary',
				'icon'          => 'quote',
			)
		);

		fec_carousel_start( 'Depoimento anterior', 'Próximo depoimento', 'Depoimentos' );
		foreach ( $fec_testimonials as $fec_item ) :
			$fec_avatar  = fec_field( 'avatar', $fec_item->ID );
			$fec_role    = array_filter( array( fec_field( 'cargo', $fec_item->ID ), fec_field( 'empresa', $fec_item->ID ) ) );
			?>
			<div class="<?php echo esc_attr( fec_carousel_item_class() ); ?>">
				<figure class="<?php echo esc_attr( fec_card_class( 'fec-figure flex flex-col h-full' ) ); ?>">
					<div class="mb-4">
						<div class="flex items-center gap-4 mb-2">
							<?php if ( $fec_avatar ) : ?>
								<?php echo wp_get_attachment_image( $fec_avatar, 'thumbnail', false, array( 'class' => 'rounded-full mb-2 w-12 h-12 object-cover', 'alt' => '' ) ); ?>
							<?php endif; ?>
							<figcaption class="text-2xl font-bold text-gray-900"><?php echo esc_html( get_the_title( $fec_item ) ); ?></figcaption>
						</div>
						<?php if ( $fec_role ) : ?>
							<span class="text-sm text-gray-500"><?php echo esc_html( implode( ' - ', $fec_role ) ); ?></span>
						<?php endif; ?>
					</div>
					<blockquote class="text-gray-600 flex-1">
						<p>&ldquo;<?php echo esc_html( fec_field( 'citacao', $fec_item->ID ) ); ?>&rdquo;</p>
					</blockquote>
				</figure>
			</div>
			<?php
		endforeach;
		fec_carousel_end();
		?>
	</div>
</section>
