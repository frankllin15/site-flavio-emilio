<?php
/**
 * Botões de compartilhar (docs/plano-blog.md, fase B4). Só links: nenhum script de rede social,
 * nada de rastreamento de terceiros. No celular, "Compartilhar" abre o menu nativo (Web Share API,
 * ligado em main.js); sem suporte, o botão fica oculto e os links resolvem.
 *
 * @var array{variant?: 'compact'|'full', post?: WP_Post} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_post    = $args['post'] ?? get_post();
$fec_full    = ( $args['variant'] ?? 'compact' ) === 'full';
$fec_url     = get_permalink( $fec_post );
$fec_title   = html_entity_decode( get_the_title( $fec_post ), ENT_QUOTES, 'UTF-8' );
$fec_excerpt = html_entity_decode( wp_strip_all_tags( get_the_excerpt( $fec_post ) ), ENT_QUOTES, 'UTF-8' );

$fec_svg_linkedin = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>';
$fec_svg_whatsapp = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>';
$fec_svg_x        = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';

$fec_links = array(
	'linkedin' => array( 'LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $fec_url ), $fec_svg_linkedin, 'hover:bg-[#0a66c2] hover:text-white hover:border-[#0a66c2]' ),
	'whatsapp' => array( 'WhatsApp', 'https://wa.me/?text=' . rawurlencode( $fec_title . ' ' . $fec_url ), $fec_svg_whatsapp, 'hover:bg-green-700 hover:text-white hover:border-green-700' ),
	'email'    => array( 'E-mail', 'mailto:?subject=' . rawurlencode( $fec_title ) . '&body=' . rawurlencode( $fec_excerpt . "\n\n" . $fec_url ), fec_icon( 'mail', 'w-5 h-5' ), 'hover:bg-gray-900 hover:text-white hover:border-gray-900' ),
	'facebook' => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $fec_url ), fec_icon( 'facebook', 'w-5 h-5' ), 'hover:bg-[#1877f2] hover:text-white hover:border-[#1877f2]' ),
	'x'        => array( 'X', 'https://x.com/intent/tweet?text=' . rawurlencode( $fec_title ) . '&url=' . rawurlencode( $fec_url ), $fec_svg_x, 'hover:bg-black hover:text-white hover:border-black' ),
);

$fec_btn_base = 'inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white text-gray-700 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary';
$fec_btn_size = $fec_full ? 'h-11 px-4 text-sm font-semibold' : 'h-10 w-10';
?>
<div class="fec-share <?php echo $fec_full ? 'rounded-2xl border border-gray-200 bg-gray-50 p-6' : ''; ?>"
	data-fec-share
	data-url="<?php echo esc_url( $fec_url ); ?>"
	data-title="<?php echo esc_attr( $fec_title ); ?>"
	data-text="<?php echo esc_attr( $fec_excerpt ); ?>">

	<?php if ( $fec_full ) : ?>
		<p class="mb-4 font-display text-lg font-bold text-gray-900">Gostou? Compartilhe com quem precisa ler isso.</p>
	<?php endif; ?>

	<div class="flex flex-wrap items-center gap-2">
		<?php // Celular: menu nativo (WhatsApp, LinkedIn, Instagram...). Oculto até o JS confirmar suporte. ?>
		<button type="button" data-fec-share-native hidden
			class="<?php echo esc_attr( fec_btn( 'primary', 'sm', 'rounded-full h-10 px-4' ) ); ?>">
			<?php echo fec_icon( 'share-2', 'w-4 h-4' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			Compartilhar
		</button>

		<?php foreach ( $fec_links as $fec_key => list( $fec_label, $fec_href, $fec_svg, $fec_hover ) ) : ?>
			<?php if ( ! $fec_full && in_array( $fec_key, array( 'facebook', 'x' ), true ) ) { continue; } ?>
			<a href="<?php echo esc_url( $fec_href ); ?>"
				<?php echo 'email' === $fec_key ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>
				class="<?php echo esc_attr( "$fec_btn_base $fec_btn_size $fec_hover" ); ?>"
				<?php if ( ! $fec_full ) : ?>
					title="Compartilhar no <?php echo esc_attr( $fec_label ); ?>"
				<?php endif; ?>
				data-fec-share-link="<?php echo esc_attr( $fec_key ); ?>">
				<?php echo $fec_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="<?php echo $fec_full ? '' : 'sr-only'; ?>">
					<?php echo $fec_full ? esc_html( $fec_label ) : esc_html( 'Compartilhar no ' . $fec_label ); ?><?php echo 'email' === $fec_key ? '' : '<span class="sr-only"> (abre em nova aba)</span>'; ?>
				</span>
			</a>
		<?php endforeach; ?>

		<button type="button" data-fec-share-copy
			class="<?php echo esc_attr( "$fec_btn_base $fec_btn_size hover:bg-accent-gold-50 hover:border-accent-gold-400" ); ?>"
			<?php echo $fec_full ? '' : 'title="Copiar link"'; ?>>
			<?php echo fec_icon( 'link', 'w-5 h-5' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="<?php echo $fec_full ? '' : 'sr-only'; ?>" data-fec-share-copy-label>Copiar link</span>
		</button>
		<span class="sr-only" role="status" aria-live="polite" data-fec-share-status></span>
	</div>
</div>
