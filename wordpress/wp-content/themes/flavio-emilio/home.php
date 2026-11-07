<?php
/**
 * Blog: lista principal, categorias e busca (docs/plano-blog.md, fase B2).
 * Também usado por archive.php (categorias/tags) e search.php.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fec_is_index = is_home() && ! is_paged();
$fec_featured = $fec_is_index ? fec_featured_post() : null;
$fec_current  = is_category() ? get_queried_object_id() : 0;
$fec_cats     = get_categories(
	array(
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_category' ) ),
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

if ( is_search() ) {
	$fec_heading = sprintf( 'Resultados para “%s”', get_search_query() );
	$fec_intro   = '';
} elseif ( is_category() || is_tag() ) {
	$fec_heading = single_term_title( '', false );
	$fec_intro   = term_description();
} else {
	$fec_heading = fec_blog_title();
	$fec_intro   = esc_html( fec_option( 'blog_subtitulo' ) );
}

$fec_chip = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border transition-colors';
?>
<main id="conteudo" class="min-h-screen bg-gray-50 pt-20">
	<section class="bg-white border-b border-gray-200">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
			<div class="max-w-4xl mx-auto text-center">
				<?php if ( ! $fec_is_index ) : ?>
					<p class="mb-3 text-sm font-semibold uppercase tracking-wider text-accent-gold-700">
						<a href="<?php echo esc_url( fec_blog_url() ); ?>" class="hover:underline"><?php echo esc_html( fec_blog_title() ); ?></a>
					</p>
				<?php endif; ?>
				<h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-5"><?php echo esc_html( $fec_heading ); ?></h1>
				<div class="flex justify-center items-center gap-2 mb-6" aria-hidden="true">
					<div class="h-1 w-12 bg-gradient-to-r from-brand-blue-500 to-accent-gold-400 rounded-full"></div>
					<div class="h-1 w-12 bg-accent-gold-400 rounded-full"></div>
					<div class="h-1 w-12 bg-gradient-to-r from-accent-gold-400 to-brand-blue-500 rounded-full"></div>
				</div>
				<?php if ( $fec_intro ) : ?>
					<div class="text-xl text-gray-600 leading-relaxed [&_p]:m-0"><?php echo wp_kses_post( $fec_intro ); ?></div>
				<?php endif; ?>

				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-8 max-w-xl mx-auto flex gap-2">
					<label for="fec-busca" class="sr-only">Buscar artigos</label>
					<div class="relative flex-1">
						<?php echo fec_icon( 'search', 'pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400' ); // phpcs:ignore ?>
						<input id="fec-busca" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Buscar artigos…"
							class="w-full h-12 rounded-full border border-gray-300 bg-white pl-12 pr-4 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
					</div>
					<button type="submit" class="<?php echo esc_attr( fec_btn( 'primary', 'default', 'rounded-full h-12' ) ); ?>">Buscar</button>
				</form>
			</div>

			<?php if ( count( $fec_cats ) > 1 ) : ?>
				<nav class="mt-8 flex flex-wrap justify-center gap-2" aria-label="Categorias">
					<a href="<?php echo esc_url( fec_blog_url() ); ?>"
						class="<?php echo esc_attr( $fec_chip . ( is_home() ? ' bg-brand-blue-500 border-brand-blue-500 text-white' : ' bg-white border-gray-300 text-gray-700 hover:border-primary hover:text-primary' ) ); ?>"
						<?php echo is_home() ? 'aria-current="page"' : ''; ?>>Todos</a>
					<?php foreach ( $fec_cats as $fec_cat ) : ?>
						<?php $fec_active = (int) $fec_cat->term_id === (int) $fec_current; ?>
						<a href="<?php echo esc_url( get_category_link( $fec_cat ) ); ?>"
							class="<?php echo esc_attr( $fec_chip . ( $fec_active ? ' bg-brand-blue-500 border-brand-blue-500 text-white' : ' bg-white border-gray-300 text-gray-700 hover:border-primary hover:text-primary' ) ); ?>"
							<?php echo $fec_active ? 'aria-current="page"' : ''; ?>>
							<?php echo esc_html( $fec_cat->name ); ?>
						</a>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		</div>
	</section>

	<section class="section-spacing">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8">
			<?php if ( $fec_featured ) : ?>
				<?php get_template_part( 'template-parts/post-featured', null, array( 'post' => $fec_featured ) ); ?>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<h2 class="sr-only"><?php echo $fec_featured ? 'Mais artigos' : 'Artigos'; ?></h2>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
					<?php
					while ( have_posts() ) :
						the_post();
						if ( $fec_featured && get_the_ID() === $fec_featured->ID ) {
							continue;
						}
						get_template_part( 'template-parts/post-card', null, array( 'post' => get_post(), 'heading' => 'h3' ) );
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'class'     => 'mt-12 flex justify-center [&_.nav-links]:flex [&_.nav-links]:flex-wrap [&_.nav-links]:justify-center [&_.nav-links]:gap-2 [&_.page-numbers]:min-w-11 [&_.page-numbers]:px-4 [&_.page-numbers]:py-2.5 [&_.page-numbers]:text-center [&_.page-numbers]:rounded-full [&_.page-numbers]:bg-white [&_.page-numbers]:border [&_.page-numbers]:border-gray-200 [&_a.page-numbers:hover]:border-primary [&_.current]:bg-brand-blue-500 [&_.current]:border-brand-blue-500 [&_.current]:text-white',
						'prev_text' => '&larr; Anteriores',
						'next_text' => 'Próximos &rarr;',
					)
				);
				?>
			<?php elseif ( is_home() ) : ?>
				<?php get_template_part( 'template-parts/blog-empty' ); ?>
			<?php else : ?>
				<div class="max-w-xl mx-auto text-center py-12">
					<p class="text-xl text-gray-700 mb-6">
						<?php echo is_search() ? 'Nenhum artigo encontrado para essa busca.' : 'Nenhum artigo nesta categoria ainda.'; ?>
					</p>
					<a href="<?php echo esc_url( fec_blog_url() ); ?>" class="<?php echo esc_attr( fec_btn( 'secondary' ) ); ?>">Ver todos os artigos</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
