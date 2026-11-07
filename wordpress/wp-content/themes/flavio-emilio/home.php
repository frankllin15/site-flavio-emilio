<?php
/**
 * Listagem do blog (página "Blog" definida em Configurações → Leitura). Não existia no app Next.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="conteudo" class="min-h-screen bg-gray-50 pt-20">
	<section class="section-spacing bg-white border-b border-gray-200">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8">
			<div class="max-w-4xl mx-auto text-center">
				<h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
					<?php echo is_category() ? esc_html( single_cat_title( '', false ) ) : 'Blog'; ?>
				</h1>
				<p class="text-xl text-gray-600 leading-relaxed">Insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional</p>
			</div>
		</div>
	</section>

	<section class="section-spacing">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8">
			<?php if ( have_posts() ) : ?>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/post-card', null, array( 'post' => get_post() ) );
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'class'     => 'mt-12 flex justify-center [&_.nav-links]:flex [&_.nav-links]:gap-2 [&_.page-numbers]:px-4 [&_.page-numbers]:py-2 [&_.page-numbers]:rounded-md [&_.page-numbers]:bg-white [&_.page-numbers]:shadow-sm [&_.current]:bg-primary [&_.current]:text-white',
						'prev_text' => '&larr; Anteriores',
						'next_text' => 'Próximos &rarr;',
					)
				);
				?>
			<?php else : ?>
				<p class="text-center text-lg text-gray-600">Nenhum artigo publicado ainda.</p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
