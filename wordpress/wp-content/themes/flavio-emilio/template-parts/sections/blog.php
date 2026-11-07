<?php
/**
 * Prévia do blog na home (components/sections/BlogPreview.tsx): 3 posts mais recentes.
 * Some da home se não houver post publicado.
 */

defined( 'ABSPATH' ) || exit;

$fec_posts = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	)
);
if ( ! $fec_posts ) {
	return;
}
?>
<section id="blog" class="section-spacing bg-white">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'    => 'Blog Posts',
				'subtitle' => 'Insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional',
			)
		);
		?>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			foreach ( $fec_posts as $fec_post ) {
				get_template_part( 'template-parts/post-card', null, array( 'post' => $fec_post ) );
			}
			?>
		</div>

		<?php if ( get_option( 'page_for_posts' ) ) : ?>
			<div class="text-center mt-12">
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="<?php echo esc_attr( fec_btn( 'secondary' ) ); ?>">Ver Todos os Artigos</a>
			</div>
		<?php endif; ?>
	</div>
</section>
