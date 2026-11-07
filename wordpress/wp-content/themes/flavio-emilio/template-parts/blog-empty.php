<?php
/**
 * Aviso "em breve" do blog, enquanto não há posts publicados (home e página do blog).
 */

defined( 'ABSPATH' ) || exit;

$fec_linkedin = fec_option( 'linkedin' );
?>
<div class="max-w-2xl mx-auto rounded-2xl border border-accent-gold-200 bg-accent-gold-50 p-8 md:p-10 text-center">
	<div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-accent-gold-100">
		<?php echo fec_icon( 'lightbulb', 'w-7 h-7 text-accent-gold-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<p class="font-display text-2xl font-bold text-gray-900 mb-3">Os primeiros textos estão a caminho</p>
	<p class="text-gray-700 leading-relaxed mb-6">
		Reflexões curtas e práticas sobre liderança, gestão de pessoas e carreira, para aplicar no dia a dia.
		<?php if ( $fec_linkedin ) : ?>
			Enquanto isso, acompanhe as novidades no LinkedIn.
		<?php endif; ?>
	</p>
	<?php if ( $fec_linkedin ) : ?>
		<a href="<?php echo esc_url( $fec_linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( fec_btn( 'primary' ) ); ?>">
			Seguir no LinkedIn<span class="sr-only"> (abre em nova aba)</span>
		</a>
	<?php endif; ?>
</div>
