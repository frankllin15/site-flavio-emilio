<?php
/**
 * Post do blog. Não existia no app Next (só havia a prévia na home).
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$fec_category = get_the_category()[0] ?? null;
	$fec_author   = get_the_author();
	?>
	<main id="conteudo" class="min-h-screen bg-white pt-20">
		<article class="section-spacing">
			<div class="container mx-auto px-4 sm:px-6 lg:px-8">
				<div class="max-w-3xl mx-auto">
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary transition-colors mb-8">
						<?php echo fec_icon( 'arrow-left', 'w-4 h-4' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						Voltar para o Blog
					</a>

					<header class="mb-8">
						<div class="flex items-center gap-4 mb-4 text-sm">
							<?php if ( $fec_category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $fec_category ) ); ?>" class="font-semibold text-primary uppercase tracking-wider hover:underline"><?php echo esc_html( $fec_category->name ); ?></a>
							<?php endif; ?>
							<span class="text-gray-500"><?php echo esc_html( fec_read_time() ); ?> min de leitura</span>
						</div>

						<h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight"><?php the_title(); ?></h1>

						<div class="flex items-center gap-3">
							<div class="w-10 h-10 rounded-full bg-brand-blue-500 flex items-center justify-center" aria-hidden="true">
								<span class="text-white text-sm font-bold"><?php echo esc_html( fec_initials( $fec_author ) ); ?></span>
							</div>
							<div class="text-sm">
								<div class="text-gray-900 font-semibold"><?php echo esc_html( $fec_author ); ?></div>
								<time class="text-gray-500" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							</div>
						</div>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto rounded-2xl shadow-lg mb-10', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					<?php endif; ?>

					<div class="prose prose-lg max-w-none prose-headings:font-display prose-a:text-primary prose-blockquote:border-accent-gold-400">
						<?php the_content(); ?>
					</div>

					<div class="mt-16 rounded-2xl bg-gray-50 p-8 text-center">
						<p class="text-lg text-gray-700 mb-6">Quer levar esse tema para a sua organização?</p>
						<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg' ) ); ?>">Entrar em Contato</a>
					</div>
				</div>
			</div>
		</article>
	</main>
	<?php
endwhile;

get_footer();
