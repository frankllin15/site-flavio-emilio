<?php
/**
 * Bloco cinza com subtítulo + lista de tópicos (com ✓) e/ou pontos de aprendizado.
 * Compartilhado por single-servico.php e single-palestra.php.
 *
 * @var array{subtitle:string, topics:array, points?:array, columns?:int} $args
 */

defined( 'ABSPATH' ) || exit;

$fec_topics = array_filter( (array) ( $args['topics'] ?? array() ), static fn( $t ) => ! empty( $t['texto'] ) );
$fec_points = array_filter( (array) ( $args['points'] ?? array() ), static fn( $p ) => ! empty( $p['titulo'] ) );

if ( ! $fec_topics && ! $fec_points ) {
	return;
}
?>
<div class="bg-gray-50 rounded-2xl p-8 mb-12">
	<?php if ( ! empty( $args['subtitle'] ) ) : ?>
		<h2 class="text-2xl font-semibold text-gray-900 mb-6"><?php echo esc_html( $args['subtitle'] ); ?></h2>
	<?php endif; ?>

	<?php if ( $fec_topics ) : ?>
		<ul class="grid grid-cols-1 <?php echo 2 === ( $args['columns'] ?? 1 ) ? 'md:grid-cols-2' : ''; ?> gap-4 <?php echo $fec_points ? 'mb-8' : ''; ?>">
			<?php foreach ( $fec_topics as $fec_topic ) : ?>
				<li class="flex items-start">
					<?php echo fec_icon( 'check', 'w-6 h-6 text-accent-gold-500 mr-3 mt-1 flex-shrink-0' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="text-lg text-gray-700"><?php echo esc_html( $fec_topic['texto'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $fec_points ) : ?>
		<div class="space-y-6">
			<?php foreach ( $fec_points as $fec_point ) : ?>
				<div class="border-l-4 border-accent-gold-400 pl-6">
					<h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo esc_html( $fec_point['titulo'] ); ?></h3>
					<p class="text-gray-700 leading-relaxed"><?php echo esc_html( $fec_point['descricao'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
