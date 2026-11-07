<?php
/**
 * Página do serviço: /servicos/<slug> (app/servicos/[id]/page.tsx).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$fec_logo   = fec_field( 'logo' );
	$fec_footer = fec_field( 'rodape' );
	?>
	<main id="conteudo" class="min-h-screen bg-white pt-20">
		<section class="section-spacing">
			<div class="container mx-auto px-4 sm:px-6 lg:px-8">
				<article class="max-w-4xl mx-auto">
					<div class="mb-8 flex justify-center">
						<?php if ( $fec_logo ) : ?>
							<h1>
								<span class="sr-only"><?php the_title(); ?></span>
								<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'h-24 w-auto object-contain', 'alt' => '' ) ); ?>
							</h1>
						<?php else : ?>
							<h1 class="text-4xl font-display md:text-5xl font-bold text-center text-gray-900 mb-6"><?php the_title(); ?></h1>
						<?php endif; ?>
					</div>

					<div class="mb-12 text-xl text-gray-700 leading-relaxed text-center space-y-4">
						<?php the_content(); ?>
					</div>

					<?php
					get_template_part(
						'template-parts/detail-topics',
						null,
						array(
							'subtitle' => fec_field( 'subtitulo' ),
							'topics'   => fec_field( 'topicos', null, array() ),
							'points'   => fec_field( 'pontos_aprendizado', null, array() ),
							'columns'  => 2,
						)
					);
					?>

					<?php if ( $fec_footer ) : ?>
						<div class="bg-accent-gold-50 border-l-4 border-accent-gold-500 rounded-r-lg p-6 mb-12">
							<p class="text-lg text-gray-800 italic leading-relaxed"><?php echo esc_html( $fec_footer ); ?></p>
						</div>
					<?php endif; ?>

					<div class="text-center">
						<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg', 'text-lg px-8 py-6' ) ); ?>">Solicitar Proposta</a>
					</div>
				</article>
			</div>
		</section>
	</main>
	<?php
endwhile;

get_footer();
