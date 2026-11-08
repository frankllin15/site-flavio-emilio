<?php
/**
 * Páginas do MailPoet (confirmação de inscrição, gerenciar inscrição, descadastro).
 * Sem isso, cairiam no layout de post do blog (autor, compartilhar...).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main id="conteudo" class="min-h-screen bg-gray-50 pt-20">
		<section class="section-spacing">
			<div class="container mx-auto px-4 sm:px-6 lg:px-8">
				<div class="max-w-2xl mx-auto rounded-2xl bg-white p-8 md:p-12 shadow-md text-center">
					<div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-accent-gold-100">
						<?php echo fec_icon( 'mail', 'w-8 h-8 text-accent-gold-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
					<p class="mb-2 text-sm font-semibold uppercase tracking-wider text-accent-gold-700"><?php echo esc_html( fec_blog_title() ); ?></p>
					<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6"><?php the_title(); ?></h1>
					<div class="fec-mailpoet text-lg text-gray-700 leading-relaxed text-left">
						<?php the_content(); ?>
					</div>
					<div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
						<a href="<?php echo esc_url( fec_blog_url() ); ?>" class="<?php echo esc_attr( fec_btn( 'primary' ) ); ?>">Ler o <?php echo esc_html( fec_blog_title() ); ?></a>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'secondary' ) ); ?>">Voltar ao início</a>
					</div>
				</div>
			</div>
		</section>
	</main>
	<?php
endwhile;

get_footer();
