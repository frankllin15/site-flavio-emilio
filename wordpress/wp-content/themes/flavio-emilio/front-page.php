<?php
/**
 * Home — ESQUELETO da fase 1–3.
 *
 * O Hero já lê a página de opções; o restante só mostra o que foi importado, para conferir
 * o modelo de conteúdo. Cada bloco será substituído pela seção portada na fase 4
 * (template-parts/sections/*.php).
 */

defined( 'ABSPATH' ) || exit;

get_header();

$fec_hero_desktop = fec_option( 'hero_imagem_desktop' );
$fec_hero_mobile  = fec_option( 'hero_imagem_mobile' );
?>
<main>
	<section id="hero" class="relative min-h-screen flex items-center overflow-hidden bg-brand-blue-900">
		<?php if ( $fec_hero_desktop ) : ?>
			<?php echo wp_get_attachment_image( $fec_hero_desktop, 'full', false, array( 'class' => 'absolute inset-0 h-full w-full object-contain object-left-top hidden md:block', 'alt' => '', 'fetchpriority' => 'high', 'loading' => 'eager' ) ); ?>
		<?php endif; ?>
		<?php if ( $fec_hero_mobile ) : ?>
			<?php echo wp_get_attachment_image( $fec_hero_mobile, 'full', false, array( 'class' => 'absolute inset-0 h-full w-full object-contain object-left-top md:hidden', 'alt' => '', 'loading' => 'eager' ) ); ?>
		<?php endif; ?>

		<div class="relative z-10 container mx-auto px-4 pt-32 sm:px-6 lg:px-8">
			<div class="max-w-3xl ml-auto text-white py-8 pb-20">
				<h1 class="text-2xl md:text-4xl xl:text-5xl font-bold mb-6 leading-tight">
					<?php echo esc_html( fec_option( 'hero_titulo' ) ); ?>
					<span class="text-accent-gold-400"><?php echo esc_html( fec_option( 'hero_titulo_destaque' ) ); ?></span>
				</h1>
				<p class="text-lg md:text-xl lg:text-2xl mb-12 text-gray-100 leading-relaxed"><?php echo esc_html( fec_option( 'hero_subtitulo' ) ); ?></p>
				<div class="flex flex-col sm:flex-row gap-4">
					<a href="<?php echo esc_url( fec_option( 'hero_cta1_link', '/#contato' ) ); ?>" class="rounded-md bg-accent px-8 py-4 text-base md:text-lg font-semibold text-white hover:bg-accent-gold-600"><?php echo esc_html( fec_option( 'hero_cta1_texto' ) ); ?></a>
					<a href="<?php echo esc_url( fec_option( 'hero_cta2_link', '/#livros' ) ); ?>" class="rounded-md bg-white px-8 py-4 text-base md:text-lg font-semibold text-primary hover:bg-gray-100"><?php echo esc_html( fec_option( 'hero_cta2_texto' ) ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<section class="section-spacing bg-gray-50">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8">
			<h2 class="text-3xl font-bold mb-2 text-gray-900">Conteúdo importado</h2>
			<p class="text-gray-600 mb-8">Esqueleto temporário: as seções reais da home entram na fase 4.</p>
			<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
				<?php
				$fec_types = array(
					'servico'    => 'Serviços',
					'palestra'   => 'Palestras',
					'evento'     => 'Eventos',
					'livro'      => 'Livros',
					'cliente'    => 'Clientes',
					'depoimento' => 'Depoimentos',
					'post'       => 'Blog',
				);
				foreach ( $fec_types as $fec_type => $fec_label ) :
					$fec_items = get_posts(
						array(
							'post_type'      => $fec_type,
							'posts_per_page' => -1,
							'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
						)
					);
					?>
					<div class="rounded-xl bg-white p-6 shadow">
						<h3 class="text-xl font-bold text-primary mb-3"><?php echo esc_html( $fec_label ); ?> (<?php echo count( $fec_items ); ?>)</h3>
						<ul class="space-y-1 text-gray-700 text-sm">
							<?php foreach ( $fec_items as $fec_item ) : ?>
								<li>
									<?php if ( is_post_type_viewable( $fec_type ) ) : ?>
										<a class="hover:text-primary underline" href="<?php echo esc_url( get_permalink( $fec_item ) ); ?>"><?php echo esc_html( get_the_title( $fec_item ) ); ?></a>
									<?php else : ?>
										<?php echo esc_html( get_the_title( $fec_item ) ); ?>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
