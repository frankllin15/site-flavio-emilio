<?php
/**
 * Card de post (mesmo visual do BlogPreview.tsx). Usado na home e na listagem do blog.
 *
 * @var array{post: WP_Post} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_post     = $args['post'];
$fec_tag      = ( $args['heading'] ?? 'h3' ) === 'h2' ? 'h2' : 'h3'; // h2 na listagem do blog (logo abaixo do <h1>)
$fec_category = get_the_category( $fec_post->ID )[0] ?? null;
$fec_author   = get_the_author_meta( 'display_name', $fec_post->post_author );
$fec_link     = get_permalink( $fec_post );
?>
<article class="<?php echo esc_attr( fec_card_class( 'relative flex flex-col' ) ); ?>">
	<?php if ( has_post_thumbnail( $fec_post ) ) : ?>
		<?php echo get_the_post_thumbnail( $fec_post, 'fec-card', array( 'class' => 'w-full h-auto aspect-video rounded-lg mb-4 object-cover', 'alt' => '' ) ); ?>
	<?php else : ?>
		<div class="aspect-video bg-linear-to-br from-brand-blue-200 to-brand-blue-400 rounded-lg mb-4 flex items-center justify-center" aria-hidden="true">
			<span class="text-white text-2xl font-bold"><?php echo esc_html( mb_strtoupper( mb_substr( $fec_category->name ?? 'Blog', 0, 2 ) ) ); ?></span>
		</div>
	<?php endif; ?>

	<div class="mb-4">
		<div class="flex items-center justify-between mb-3">
			<span class="text-xs font-semibold text-primary uppercase tracking-wider"><?php echo esc_html( $fec_category->name ?? '' ); ?></span>
			<span class="text-xs text-gray-500"><?php echo esc_html( fec_read_time( $fec_post ) ); ?> min de leitura</span>
		</div>
		<<?php echo $fec_tag; // phpcs:ignore ?> class="text-xl font-bold text-foreground">
			<?php /* O link cobre o card inteiro (after:absolute), mantendo um único alvo de foco. */ ?>
			<a href="<?php echo esc_url( $fec_link ); ?>" class="hover:text-primary transition-colors after:absolute after:inset-0"><?php echo esc_html( get_the_title( $fec_post ) ); ?></a>
		</<?php echo $fec_tag; // phpcs:ignore ?>>
	</div>

	<div class="text-gray-600 flex-1 flex flex-col">
		<p class="text-gray-600 text-sm mb-4 line-clamp-3"><?php echo esc_html( get_the_excerpt( $fec_post ) ); ?></p>

		<div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-200">
			<div class="flex items-center space-x-2">
				<div class="w-8 h-8 rounded-full bg-brand-blue-500 flex items-center justify-center" aria-hidden="true">
					<span class="text-white text-xs font-bold"><?php echo esc_html( fec_initials( $fec_author ) ); ?></span>
				</div>
				<span class="text-sm text-gray-700"><?php echo esc_html( $fec_author ); ?></span>
			</div>
			<time class="text-xs text-gray-500" datetime="<?php echo esc_attr( get_the_date( 'c', $fec_post ) ); ?>"><?php echo esc_html( get_the_date( 'd \d\e M \d\e Y', $fec_post ) ); ?></time>
		</div>
	</div>
</article>
