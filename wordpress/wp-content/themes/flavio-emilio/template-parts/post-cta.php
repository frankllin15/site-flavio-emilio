<?php
/**
 * Chamada de conversão no fim do post: o serviço/palestra escolhido em "Chamada no fim do post",
 * ou um convite genérico para contato. Mesmas cores do hero (azul profundo + dourado).
 *
 * @var array{related:int} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_rel     = $args['related'] ? get_post( $args['related'] ) : null;
$fec_rel     = ( $fec_rel && 'publish' === $fec_rel->post_status ) ? $fec_rel : null;
$fec_is_talk = $fec_rel && 'palestra' === $fec_rel->post_type;
$fec_logo    = $fec_rel ? (int) fec_field( 'logo', $fec_rel->ID, 0 ) : 0;
?>
<section class="relative overflow-hidden rounded-2xl bg-brand-blue-900 p-8 md:p-10 text-white" aria-label="Leve este tema para sua organização">
	<div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-accent-gold-400/15" aria-hidden="true"></div>
	<div class="relative flex flex-col md:flex-row md:items-center gap-8">
		<?php if ( $fec_logo ) : ?>
			<div class="shrink-0 rounded-xl bg-white p-4 self-start">
				<?php echo wp_get_attachment_image( $fec_logo, 'fec-logo', false, array( 'class' => 'h-16 w-auto object-contain', 'alt' => '' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="flex-1">
			<?php if ( $fec_rel ) : ?>
				<p class="text-sm font-semibold uppercase tracking-wider text-accent-gold-400 mb-2"><?php echo $fec_is_talk ? 'Palestra relacionada' : 'Serviço relacionado'; ?></p>
				<p class="font-display text-2xl font-bold mb-3"><?php echo esc_html( get_the_title( $fec_rel ) ); ?></p>
				<p class="text-gray-200 leading-relaxed mb-6 line-clamp-3"><?php echo esc_html( fec_plain_content( $fec_rel ) ); ?></p>
			<?php else : ?>
				<p class="text-sm font-semibold uppercase tracking-wider text-accent-gold-400 mb-2">Para sua organização</p>
				<p class="font-display text-2xl font-bold mb-3">Quer levar esse tema para a sua equipe?</p>
				<p class="text-gray-200 leading-relaxed mb-6">Palestras, treinamentos in-company e consultoria sob medida para a realidade da sua empresa.</p>
			<?php endif; ?>
			<div class="flex flex-col sm:flex-row gap-3">
				<?php if ( $fec_rel ) : ?>
					<a href="<?php echo esc_url( get_permalink( $fec_rel ) ); ?>" class="<?php echo esc_attr( fec_btn( '', 'default', 'rounded-lg bg-white text-brand-blue-900 hover:bg-gray-100 hover:-translate-y-0.5' ) ); ?>">
						<?php echo $fec_is_talk ? 'Conheça a palestra' : 'Conheça o serviço'; ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="<?php echo esc_attr( fec_btn( '', 'default', 'rounded-lg border-2 border-white/60 text-white hover:bg-white/10' ) ); ?>">
					Fale comigo
				</a>
			</div>
		</div>
	</div>
</section>
