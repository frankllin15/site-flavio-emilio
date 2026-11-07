<?php
/**
 * Página da palestra: /palestras/<slug> (app/palestras/[id]/page.tsx).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$fec_logo       = fec_field( 'logo' );
	$fec_conclusion = fec_field( 'conclusao' );
	?>
	<main id="conteudo" class="min-h-screen bg-white pt-20">
		<section class="section-spacing">
			<div class="container mx-auto px-4 sm:px-6 lg:px-8">
				<article class="max-w-4xl mx-auto">
					<?php if ( $fec_logo ) : ?>
						<div class="mb-8 flex justify-center">
							<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'h-24 w-auto object-contain', 'alt' => '', 'loading' => 'eager' ) ); ?>
						</div>
					<?php endif; ?>

					<h1 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8"><?php the_title(); ?></h1>

					<div class="mb-12 text-xl text-gray-700 leading-relaxed space-y-4">
						<?php the_content(); ?>
					</div>

					<?php
					get_template_part(
						'template-parts/detail-topics',
						null,
						array(
							'subtitle' => fec_field( 'subtitulo' ),
							'topics'   => fec_field( 'topicos', null, array() ),
						)
					);
					?>

					<?php if ( $fec_conclusion ) : ?>
						<div class="bg-accent-gold-50 border-l-4 border-accent-gold-500 rounded-r-lg p-6 mb-12">
							<p class="text-lg text-gray-800 italic leading-relaxed"><?php echo esc_html( $fec_conclusion ); ?></p>
						</div>
					<?php endif; ?>

					<div class="text-center">
						<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg', 'text-lg px-8 py-6' ) ); ?>">Solicitar Palestra</a>
					</div>
				</article>
			</div>
		</section>
	</main>
	<?php
endwhile;

get_footer();
