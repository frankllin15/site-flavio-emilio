<?php
/**
 * Páginas comuns criadas no painel (ex.: Política de Privacidade).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="conteudo" class="min-h-screen bg-white pt-20">
		<article class="section-spacing">
			<div class="container mx-auto px-4 sm:px-6 lg:px-8">
				<div class="max-w-3xl mx-auto">
					<h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-10"><?php the_title(); ?></h1>
					<div class="prose prose-lg max-w-none prose-headings:font-display prose-a:text-primary">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</article>
	</main>
	<?php
endwhile;

get_footer();
