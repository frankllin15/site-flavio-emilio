<?php
/**
 * Home: últimos posts do Drops de Carreira (substitui o BlogPreview.tsx do Next).
 * Sem posts publicados, mostra um aviso "em breve" em vez de sumir (a seção e o menu continuam
 * fazendo sentido no lançamento).
 */

defined( 'ABSPATH' ) || exit;

$fec_posts = get_posts(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	)
);
$fec_total = (int) wp_count_posts( 'post' )->publish;
?>
<section id="blog" class="section-spacing bg-white">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'         => fec_blog_title(),
				'subtitle'      => fec_option( 'blog_subtitulo' ),
				'badge'         => 'Blog',
				'badge_variant' => 'accent',
				'icon'          => 'lightbulb',
			)
		);
		?>

		<?php if ( $fec_posts ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				foreach ( $fec_posts as $fec_post ) {
					get_template_part( 'template-parts/post-card', null, array( 'post' => $fec_post ) );
				}
				?>
			</div>

			<div class="text-center mt-12">
				<a href="<?php echo esc_url( fec_blog_url() ); ?>" class="<?php echo esc_attr( fec_btn( 'secondary' ) ); ?>">
					<?php echo $fec_total > 3 ? 'Ver todos os artigos' : 'Ir para o ' . esc_html( fec_blog_title() ); ?>
					<?php echo fec_icon( 'arrow-right', 'w-4 h-4' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/blog-empty' ); ?>
		<?php endif; ?>
	</div>
</section>
