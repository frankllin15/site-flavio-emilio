<?php
/**
 * Fallback genérico (lista de posts / qualquer rota sem template próprio).
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="pt-32 pb-20">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'mb-12' ); ?>>
					<h2 class="text-3xl font-bold mb-3 text-gray-900">
						<a href="<?php the_permalink(); ?>" class="hover:text-primary"><?php the_title(); ?></a>
					</h2>
					<div class="text-gray-700 leading-relaxed"><?php is_singular() ? the_content() : the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="text-gray-600">Nada encontrado.</p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
