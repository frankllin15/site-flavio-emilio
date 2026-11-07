<?php
/**
 * Post em destaque no topo do blog: imagem grande + resumo (docs/plano-blog.md, B2).
 *
 * @var array{post: WP_Post} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_post     = $args['post'];
$fec_category = fec_post_category( $fec_post );
$fec_link     = get_permalink( $fec_post );
?>
<article class="group relative mb-12 grid overflow-hidden rounded-2xl bg-white shadow-md transition-shadow hover:shadow-xl md:grid-cols-2">
	<div class="relative aspect-[16/10] md:aspect-auto md:min-h-[22rem] bg-linear-to-br from-brand-blue-200 to-brand-blue-400">
		<?php
		if ( has_post_thumbnail( $fec_post ) ) {
			echo get_the_post_thumbnail(
				$fec_post,
				'large',
				array(
					'class'         => 'absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105',
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '(min-width: 768px) 50vw, 100vw',
				)
			);
		}
		?>
	</div>
	<div class="flex flex-col justify-center p-8 md:p-10 lg:p-12">
		<div class="mb-4 flex items-center gap-3 text-xs font-semibold uppercase tracking-wider">
			<span class="rounded-full bg-accent-gold-100 px-3 py-1 text-accent-gold-800">Destaque</span>
			<?php if ( $fec_category ) : ?>
				<span class="text-primary"><?php echo esc_html( $fec_category->name ); ?></span>
			<?php endif; ?>
		</div>
		<h2 class="mb-4 text-2xl md:text-3xl font-bold leading-tight text-gray-900">
			<a href="<?php echo esc_url( $fec_link ); ?>" class="after:absolute after:inset-0 group-hover:text-primary transition-colors"><?php echo esc_html( get_the_title( $fec_post ) ); ?></a>
		</h2>
		<p class="mb-6 text-gray-600 leading-relaxed line-clamp-3"><?php echo esc_html( get_the_excerpt( $fec_post ) ); ?></p>
		<div class="mt-auto flex items-center gap-3 text-sm text-gray-500">
			<?php echo fec_author_avatar( (int) $fec_post->post_author, 'w-10 h-10 text-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div>
				<div class="font-semibold text-gray-900"><?php echo esc_html( get_the_author_meta( 'display_name', $fec_post->post_author ) ); ?></div>
				<time datetime="<?php echo esc_attr( get_the_date( 'c', $fec_post ) ); ?>"><?php echo esc_html( get_the_date( '', $fec_post ) ); ?></time>
				<span aria-hidden="true"> · </span><?php echo esc_html( fec_read_time( $fec_post ) ); ?> min de leitura
			</div>
		</div>
	</div>
</article>
