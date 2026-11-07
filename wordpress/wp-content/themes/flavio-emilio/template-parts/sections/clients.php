<?php
/**
 * Clientes (components/sections/Clients.tsx): duas esteiras em CSS puro (classes .clients-track em main.css).
 */

defined( 'ABSPATH' ) || exit;

$fec_clients = fec_get_items( 'cliente' );
if ( ! $fec_clients ) {
	return;
}

// Mesma conta do original: ~282px por item a 50px/s.
$fec_duration = count( $fec_clients ) * 282 / 50;

$fec_render_track = static function ( string $track_class, bool $is_copy_hidden ) use ( $fec_clients, $fec_duration ) {
	?>
	<div class="clients-track-wrapper">
		<div class="<?php echo esc_attr( $track_class ); ?>" style="animation-duration: <?php echo esc_attr( $fec_duration ); ?>s">
			<?php
			// A lista é duplicada para o loop infinito; a 2ª cópia e a 2ª esteira ficam fora do leitor de tela e do Tab.
			foreach ( array( false, true ) as $fec_is_copy ) :
				$fec_hidden = $is_copy_hidden || $fec_is_copy;
				foreach ( $fec_clients as $fec_client ) :
					$fec_logo = fec_field( 'logo', $fec_client->ID );
					$fec_site = fec_field( 'site', $fec_client->ID );
					$fec_tag  = $fec_site ? 'a' : 'div';
					?>
					<<?php echo $fec_tag; // phpcs:ignore ?>
						class="clients-item block"
						<?php if ( $fec_site ) : ?>
							href="<?php echo esc_url( $fec_site ); ?>" target="_blank" rel="noopener noreferrer"
						<?php endif; ?>
						<?php echo $fec_hidden ? 'aria-hidden="true" tabindex="-1"' : ''; ?>
					>
						<div class="flex items-center justify-center h-20 md:h-32 p-3 md:p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
							<?php if ( $fec_logo ) : ?>
								<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'w-full h-full object-contain', 'alt' => $fec_hidden ? '' : get_the_title( $fec_client ) ) ); ?>
							<?php else : ?>
								<span class="text-sm md:text-xl font-display font-bold text-gray-700 uppercase tracking-wide px-2 md:px-4 text-center"><?php echo esc_html( get_the_title( $fec_client ) ); ?></span>
							<?php endif; ?>
						</div>
					</<?php echo $fec_tag; // phpcs:ignore ?>>
					<?php
				endforeach;
			endforeach;
			?>
		</div>
	</div>
	<?php
};
?>
<section id="clients" class="section-spacing bg-white">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'         => 'Nossos Clientes',
				'subtitle'      => 'Empresas que confiam em nosso trabalho',
				'badge'         => 'Parceiros',
				'badge_variant' => 'secondary',
				'icon'          => 'handshake',
			)
		);
		?>
	</div>

	<div class="relative overflow-hidden mt-12 space-y-8 md:space-y-12 w-full">
		<div class="absolute mb-0 left-0 top-0 bottom-0 w-16 md:w-32 bg-linear-to-r from-white to-transparent z-10 pointer-events-none"></div>
		<div class="absolute mb-0 right-0 top-0 bottom-0 w-16 md:w-32 bg-linear-to-l from-white to-transparent z-10 pointer-events-none"></div>
		<?php
		$fec_render_track( 'clients-track', false );
		$fec_render_track( 'clients-track-reverse', true );
		?>
	</div>
</section>
