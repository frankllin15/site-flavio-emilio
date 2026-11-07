<?php
/**
 * Caixa "Sobre o autor" no fim do post. Bio = Usuários → Perfil → Informações biográficas.
 *
 * @var array{user_id:int} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_uid      = (int) $args['user_id'];
$fec_bio      = get_the_author_meta( 'description', $fec_uid );
$fec_linkedin = get_the_author_meta( 'linkedin', $fec_uid );
?>
<section class="flex flex-col sm:flex-row gap-6 rounded-2xl border border-gray-200 p-6 md:p-8" aria-label="Sobre o autor">
	<?php echo fec_author_avatar( $fec_uid, 'w-20 h-20 md:w-24 md:h-24', 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<div>
		<p class="text-xs font-semibold uppercase tracking-wider text-accent-gold-700 mb-1">Sobre o autor</p>
		<p class="font-display text-xl font-bold text-gray-900 mb-2"><?php echo esc_html( get_the_author_meta( 'display_name', $fec_uid ) ); ?></p>
		<?php if ( $fec_bio ) : ?>
			<p class="text-gray-600 leading-relaxed mb-4"><?php echo esc_html( $fec_bio ); ?></p>
		<?php endif; ?>
		<div class="flex flex-wrap gap-x-6 gap-y-2 text-sm font-semibold">
			<a href="<?php echo esc_url( home_url( '/#sobre' ) ); ?>" class="text-primary hover:underline">Conheça a trajetória</a>
			<?php if ( $fec_linkedin ) : ?>
				<a href="<?php echo esc_url( $fec_linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline">
					Siga no LinkedIn<span class="sr-only"> (abre em nova aba)</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
