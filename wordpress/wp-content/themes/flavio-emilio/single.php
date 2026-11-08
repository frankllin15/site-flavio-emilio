<?php
/**
 * Post do blog (docs/plano-blog.md, fase B1): leitura confortável na identidade do site,
 * índice, compartilhamento, autor, chamada para serviço/palestra e posts relacionados.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$fec_post_id  = get_the_ID();
	$fec_author   = (int) get_post_field( 'post_author' );
	$fec_category = fec_post_category();
	$fec_body     = fec_post_content_with_toc();
	$fec_has_toc  = count( $fec_body['toc'] ) >= 3;
	$fec_modified = get_the_modified_date( 'U' ) - get_the_date( 'U' ) > DAY_IN_SECONDS;
	?>
	<?php // Barra de progresso de leitura (main.js). ?>
	<div class="fixed left-0 top-0 z-[70] h-1 w-full origin-left scale-x-0 bg-accent-gold-400 motion-safe:transition-transform" data-fec-progress aria-hidden="true"></div>

	<main id="conteudo" class="bg-white pt-20">
		<article <?php post_class(); ?> data-fec-article>

			<header class="bg-gray-50 border-b border-gray-200">
				<div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-10 md:pt-14">
					<div class="max-w-3xl mx-auto">
						<?php
						if ( function_exists( 'yoast_breadcrumb' ) ) {
							yoast_breadcrumb( '<nav class="fec-breadcrumb mb-6 text-sm text-gray-500" aria-label="Você está em">', '</nav>' );
						}
						?>

						<?php if ( $fec_category ) : ?>
							<a href="<?php echo esc_url( get_category_link( $fec_category ) ); ?>"
								class="inline-flex mb-4 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider border bg-accent-gold-100 text-accent-gold-800 border-accent-gold-200 hover:bg-accent-gold-200 transition-colors">
								<?php echo esc_html( $fec_category->name ); ?>
							</a>
						<?php endif; ?>

						<h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-5"><?php the_title(); ?></h1>

						<div class="flex items-center gap-2 mb-6" aria-hidden="true">
							<div class="h-1 w-12 bg-gradient-to-r from-brand-blue-500 to-accent-gold-400 rounded-full"></div>
							<div class="h-1 w-12 bg-accent-gold-400 rounded-full"></div>
							<div class="h-1 w-12 bg-gradient-to-r from-accent-gold-400 to-brand-blue-500 rounded-full"></div>
						</div>

						<?php if ( has_excerpt() ) : ?>
							<p class="text-xl text-gray-600 leading-relaxed mb-8"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>

						<div class="flex flex-wrap items-center justify-between gap-6">
							<div class="flex items-center gap-3">
								<?php echo fec_author_avatar( $fec_author, 'w-12 h-12' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<div class="text-sm leading-snug">
									<div class="font-semibold text-gray-900"><?php the_author(); ?></div>
									<div class="text-gray-500">
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
										<span aria-hidden="true"> · </span>
										<?php echo esc_html( fec_read_time() ); ?> min de leitura
										<?php if ( $fec_modified ) : ?>
											<span aria-hidden="true"> · </span>atualizado em <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<?php get_template_part( 'template-parts/share', null, array( 'variant' => 'compact' ) ); ?>
						</div>
					</div>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-10">
					<?php // Centralização na <div>: a margem da <figure> é controlada pelo WordPress (ver .fec-figure em main.css). ?>
					<div class="max-w-5xl mx-auto">
						<figure class="fec-figure">
							<?php
							the_post_thumbnail(
								'large',
								array(
									'class'         => 'w-full h-auto aspect-[16/9] object-cover rounded-2xl shadow-lg',
									'alt'           => '',
									'loading'       => 'eager',
									'fetchpriority' => 'high',
									'sizes'         => '(min-width: 1024px) 1024px, 100vw',
								)
							);
							$fec_caption = get_the_post_thumbnail_caption();
							if ( $fec_caption ) :
								?>
								<figcaption class="mt-3 text-center text-sm text-gray-500"><?php echo esc_html( $fec_caption ); ?></figcaption>
							<?php endif; ?>
						</figure>
					</div>
				</div>
			<?php endif; ?>

			<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
				<div class="mx-auto <?php echo $fec_has_toc ? 'max-w-5xl lg:grid lg:grid-cols-[minmax(0,1fr)_15rem] lg:gap-16' : 'max-w-3xl'; ?>">

					<?php if ( $fec_has_toc ) : ?>
						<?php // Índice: recolhível antes do texto no celular, fixo na lateral no desktop. ?>
						<details class="fec-toc lg:hidden mb-10 rounded-xl border border-gray-200 bg-gray-50 p-5">
							<summary class="flex cursor-pointer list-none items-center justify-between font-display font-bold text-gray-900">
								<span class="flex items-center gap-2"><?php echo fec_icon( 'list', 'w-5 h-5 text-accent-gold-500' ); // phpcs:ignore ?> Neste artigo</span>
								<?php echo fec_icon( 'chevron-down', 'w-5 h-5 transition-transform' ); // phpcs:ignore ?>
							</summary>
							<ol class="mt-4 space-y-2 text-gray-700">
								<?php foreach ( $fec_body['toc'] as $fec_item ) : ?>
									<li><a class="hover:text-primary" href="#<?php echo esc_attr( $fec_item['id'] ); ?>"><?php echo esc_html( $fec_item['text'] ); ?></a></li>
								<?php endforeach; ?>
							</ol>
						</details>
					<?php endif; ?>

					<div class="min-w-0">
						<div class="fec-prose prose prose-lg max-w-[68ch] mx-auto" data-fec-content>
							<?php echo $fec_body['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- conteúdo do post já filtrado por the_content ?>
						</div>

						<div class="max-w-[68ch] mx-auto mt-14 space-y-10">
							<?php get_template_part( 'template-parts/share', null, array( 'variant' => 'full' ) ); ?>
							<?php get_template_part( 'template-parts/newsletter', null, array( 'variant' => 'card', 'origem' => 'post' ) ); ?>
							<?php get_template_part( 'template-parts/author-box', null, array( 'user_id' => $fec_author ) ); ?>
							<?php get_template_part( 'template-parts/post-cta', null, array( 'related' => (int) fec_field( 'relacionado', $fec_post_id, 0 ) ) ); ?>
						</div>
					</div>

					<?php if ( $fec_has_toc ) : ?>
						<aside class="hidden lg:block" aria-label="Neste artigo">
							<nav class="fec-toc sticky top-28">
								<p class="mb-4 flex items-center gap-2 font-display text-sm font-bold uppercase tracking-wider text-gray-900">
									<?php echo fec_icon( 'list', 'w-4 h-4 text-accent-gold-500' ); // phpcs:ignore ?> Neste artigo
								</p>
								<ol class="space-y-1 border-l-2 border-gray-200 text-sm" data-fec-toc>
									<?php foreach ( $fec_body['toc'] as $fec_item ) : ?>
										<li>
											<a href="#<?php echo esc_attr( $fec_item['id'] ); ?>"
												class="-ml-0.5 block border-l-2 border-transparent py-1.5 pl-4 text-gray-600 transition-colors hover:text-primary data-[active=1]:border-accent-gold-500 data-[active=1]:font-semibold data-[active=1]:text-gray-900">
												<?php echo esc_html( $fec_item['text'] ); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ol>
							</nav>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</article>

		<?php $fec_related = fec_related_posts( $fec_post_id ); ?>
		<?php if ( $fec_related ) : ?>
			<section class="section-spacing bg-gray-50" aria-labelledby="fec-related-title">
				<div class="container mx-auto px-4 sm:px-6 lg:px-8">
					<div class="flex items-end justify-between gap-4 mb-10">
						<h2 id="fec-related-title" class="text-3xl md:text-4xl font-bold text-gray-900">Continue lendo</h2>
						<a href="<?php echo esc_url( fec_blog_url() ); ?>" class="hidden sm:inline-flex items-center gap-2 font-semibold text-primary hover:underline">
							Ver todos os artigos <?php echo fec_icon( 'arrow-right', 'w-4 h-4' ); // phpcs:ignore ?>
						</a>
					</div>
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<?php
						foreach ( $fec_related as $fec_rel ) {
							get_template_part( 'template-parts/post-card', null, array( 'post' => $fec_rel ) );
						}
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</main>
	<?php
endwhile;

get_footer();
