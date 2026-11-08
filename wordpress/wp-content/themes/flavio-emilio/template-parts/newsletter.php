<?php
/**
 * Inscrição na newsletter "Drops de Carreira" (docs/plano-blog.md, B5).
 * Envia para /wp-json/fec/v1/newsletter (fec-core/newsletter.php) via main.js.
 *
 * Variantes:
 *  - card   : fim do post e aviso "em breve" (fundo claro)
 *  - band   : faixa na lista do blog (fundo azul profundo, como o hero)
 *  - footer : rodapé, compacta, sobre fundo escuro
 *
 * @var array{variant?: 'card'|'band'|'footer', origem?: string, titulo?: string, texto?: string} $args
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_option( 'fec_newsletter_list_id' ) ) {
	return; // newsletter não configurada (seed/newsletter.php)
}

$fec_variant = $args['variant'] ?? 'card';
$fec_dark    = in_array( $fec_variant, array( 'band', 'footer' ), true );
$fec_uid     = wp_unique_id( 'fec-nl-' );
$fec_privacy = get_privacy_policy_url();
$fec_blog    = fec_blog_title();
$fec_title   = $args['titulo'] ?? 'Receba o ' . $fec_blog . ' no seu e-mail';
$fec_text    = $args['texto'] ?? 'Um aviso a cada novo texto sobre liderança, gestão de pessoas e carreira. Sem spam; você cancela quando quiser.';

$fec_input = $fec_dark
	? 'w-full h-12 rounded-lg border border-white/20 bg-white/10 px-4 text-white placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-accent-gold-400'
	: 'w-full h-12 rounded-lg border border-gray-300 bg-white px-4 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary';

// Botão dourado com texto azul profundo (contraste ~8:1); o dourado com texto branco não passa no WCAG.
$fec_gold_btn = 'rounded-lg bg-accent-gold-400 text-brand-blue-900 hover:bg-accent-gold-300 hover:-translate-y-0.5';

$fec_wrap = array(
	'card'   => 'rounded-2xl border border-accent-gold-200 bg-accent-gold-50 p-6 md:p-8',
	'band'   => 'relative overflow-hidden rounded-2xl bg-brand-blue-900 p-8 md:p-12 text-white',
	'footer' => '',
)[ $fec_variant ];
?>
<section class="<?php echo esc_attr( $fec_wrap ); ?>" aria-labelledby="<?php echo esc_attr( $fec_uid ); ?>-titulo" data-fec-newsletter-box>
	<?php if ( 'band' === $fec_variant ) : ?>
		<div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-accent-gold-400/10" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="relative <?php echo 'band' === $fec_variant ? 'md:grid md:grid-cols-[1fr_minmax(0,28rem)] md:items-center md:gap-12' : ''; ?>">
		<div class="<?php echo 'footer' === $fec_variant ? 'mb-4' : 'mb-6 md:mb-0'; ?> <?php echo 'card' === $fec_variant ? 'md:mb-6' : ''; ?>">
			<?php if ( 'footer' === $fec_variant ) : ?>
				<h2 id="<?php echo esc_attr( $fec_uid ); ?>-titulo" class="text-xl font-bold mb-2 text-white">Receba o <?php echo esc_html( $fec_blog ); ?></h2>
				<p class="text-gray-400 text-sm">Novos textos direto no seu e-mail. Sem spam.</p>
			<?php else : ?>
				<p class="mb-2 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider <?php echo $fec_dark ? 'text-accent-gold-400' : 'text-accent-gold-700'; ?>">
					<?php echo fec_icon( 'mail', 'w-4 h-4' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Newsletter
				</p>
				<h2 id="<?php echo esc_attr( $fec_uid ); ?>-titulo" class="font-display text-2xl md:text-3xl font-bold mb-3 <?php echo $fec_dark ? 'text-white' : 'text-gray-900'; ?>">
					<?php echo esc_html( $fec_title ); ?>
				</h2>
				<p class="leading-relaxed <?php echo $fec_dark ? 'text-gray-200' : 'text-gray-700'; ?>">
					<?php echo esc_html( $fec_text ); ?>
				</p>
			<?php endif; ?>
		</div>

		<form class="space-y-3" method="post" novalidate
			action="<?php echo esc_url( rest_url( 'fec/v1/newsletter' ) ); ?>"
			data-fec-newsletter
			data-origem="<?php echo esc_attr( $args['origem'] ?? $fec_variant ); ?>">

			<div class="<?php echo 'footer' === $fec_variant ? 'flex flex-col sm:flex-row gap-2' : 'grid gap-3 ' . ( 'card' === $fec_variant ? 'sm:grid-cols-2' : '' ); ?>">
				<?php if ( 'footer' !== $fec_variant ) : ?>
					<div>
						<label for="<?php echo esc_attr( $fec_uid ); ?>-nome" class="sr-only">Seu nome (opcional)</label>
						<input id="<?php echo esc_attr( $fec_uid ); ?>-nome" type="text" name="nome" autocomplete="given-name" placeholder="Seu nome (opcional)" class="<?php echo esc_attr( $fec_input ); ?>">
					</div>
				<?php endif; ?>
				<div class="flex-1">
					<label for="<?php echo esc_attr( $fec_uid ); ?>-email" class="sr-only">Seu e-mail</label>
					<input id="<?php echo esc_attr( $fec_uid ); ?>-email" type="email" name="email" required autocomplete="email" placeholder="Seu melhor e-mail" class="<?php echo esc_attr( $fec_input ); ?>">
				</div>
				<?php if ( 'footer' === $fec_variant ) : ?>
					<button type="submit" class="<?php echo esc_attr( fec_btn( '', 'default', 'h-12 ' . $fec_gold_btn ) ); ?>">Inscrever</button>
				<?php endif; ?>
			</div>

			<?php // Honeypot: invisível para pessoas; robôs costumam preencher. ?>
			<div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
				<label for="<?php echo esc_attr( $fec_uid ); ?>-site">Não preencha</label>
				<input id="<?php echo esc_attr( $fec_uid ); ?>-site" type="text" name="site" tabindex="-1" autocomplete="off">
			</div>

			<label class="flex items-start gap-3 text-sm leading-snug <?php echo $fec_dark ? 'text-gray-300' : 'text-gray-600'; ?>">
				<input type="checkbox" name="aceite" value="1" required class="mt-0.5 h-5 w-5 shrink-0 rounded border-gray-300 accent-[#1e40af]">
				<span>
					Quero receber os textos do <?php echo esc_html( $fec_blog ); ?> por e-mail. Posso cancelar a qualquer momento.
					<?php if ( $fec_privacy ) : ?>
						<a href="<?php echo esc_url( $fec_privacy ); ?>" class="underline <?php echo $fec_dark ? 'hover:text-white' : 'hover:text-primary'; ?>">Política de privacidade</a>.
					<?php endif; ?>
				</span>
			</label>

			<?php if ( 'footer' !== $fec_variant ) : ?>
				<button type="submit" class="<?php echo esc_attr( ( $fec_dark ? fec_btn( '', 'default', 'w-full sm:w-auto ' . $fec_gold_btn ) : fec_btn( 'primary', 'default', 'w-full sm:w-auto' ) ) ); ?>">
					Quero receber
					<?php echo fec_icon( 'arrow-right', 'w-4 h-4' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>
			<?php endif; ?>

			<p class="text-sm font-semibold empty:hidden <?php echo $fec_dark ? 'text-accent-gold-300' : 'text-primary'; ?>" role="status" aria-live="polite" data-fec-newsletter-status></p>
		</form>

		<?php // Alternativa ao e-mail (B6): feed RSS do blog. ?>
		<p class="mt-4 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm <?php echo 'band' === $fec_variant ? 'md:col-span-2' : ''; ?> <?php echo $fec_dark ? 'text-gray-400' : 'text-gray-500'; ?>">
			<span>Usa leitor de feeds?</span>
			<a href="<?php echo esc_url( get_feed_link() ); ?>" class="inline-flex items-center gap-1.5 font-semibold underline-offset-2 hover:underline <?php echo $fec_dark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-primary'; ?>">
				<?php echo fec_icon( 'rss', 'w-4 h-4' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				Assine o RSS
			</a>
		</p>
	</div>
</section>
