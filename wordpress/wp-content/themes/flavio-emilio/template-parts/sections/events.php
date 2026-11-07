<?php
/**
 * Eventos (components/sections/Events.tsx). Usado na home (limit 3) e em /eventos (todos, sem título).
 *
 * @var array{limit?:int, show_heading?:bool, show_view_all?:bool} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_limit         = (int) ( $args['limit'] ?? 0 );
$fec_show_heading  = $args['show_heading'] ?? true;
$fec_show_view_all = $args['show_view_all'] ?? true;
// Sem o título da seção (página /eventos), os cards ficam logo abaixo do <h1>: usam <h2>.
$fec_card_tag      = $fec_show_heading ? 'h3' : 'h2';

$fec_events = fec_get_events( $fec_limit ?: -1 );
if ( ! $fec_events ) {
	return;
}
$fec_total = (int) wp_count_posts( 'evento' )->publish;
?>
<section id="eventos" class="section-spacing bg-gray-50">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		if ( $fec_show_heading ) {
			fec_section_heading(
				array(
					'title'         => 'Eventos Realizados',
					'subtitle'      => 'Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações',
					'badge'         => 'Histórico',
					'badge_variant' => 'primary',
					'icon'          => 'calendar',
				)
			);
		}
		?>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php foreach ( $fec_events as $fec_event ) : ?>
				<?php $fec_month = fec_event_month( $fec_event ); ?>
				<article class="group">
					<div class="relative aspect-[4/3] rounded-lg overflow-hidden mb-4 bg-gray-100">
						<?php
						echo get_the_post_thumbnail(
							$fec_event,
							'fec-card',
							array(
								'class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105',
								'alt'   => get_the_title( $fec_event ),
								'sizes' => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw',
							)
						);
						?>
						<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
					</div>

					<div>
						<?php if ( $fec_month ) : ?>
							<div class="mb-2">
								<span class="inline-block text-xs font-semibold text-primary uppercase tracking-wider"><?php echo esc_html( $fec_month ); ?></span>
							</div>
						<?php endif; ?>

						<<?php echo $fec_card_tag; // phpcs:ignore ?> class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors"><?php echo esc_html( get_the_title( $fec_event ) ); ?></<?php echo $fec_card_tag; // phpcs:ignore ?>>

						<p class="text-gray-600 text-sm md:text-base mb-3 line-clamp-4"><?php echo esc_html( fec_plain_content( $fec_event ) ); ?></p>

						<?php if ( fec_field( 'local', $fec_event->ID ) ) : ?>
							<div class="flex items-start text-sm text-gray-700">
								<?php echo fec_icon( 'map-pin', 'mr-2 mt-0.5 shrink-0' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span><?php echo esc_html( fec_field( 'local', $fec_event->ID ) ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( $fec_show_view_all && $fec_limit && $fec_total > $fec_limit ) : ?>
			<div class="text-center mt-8">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'evento' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'secondary' ) ); ?>">Ver Todos os Eventos</a>
			</div>
		<?php endif; ?>

		<div class="text-center mt-12">
			<p class="text-gray-600 mb-6 text-lg">Quer levar palestras e treinamentos de excelência para sua organização?</p>
			<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg' ) ); ?>">Solicitar Proposta para Evento</a>
		</div>
	</div>
</section>
