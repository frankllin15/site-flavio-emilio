<?php
/**
 * Livros (components/sections/Books.tsx).
 */

defined( 'ABSPATH' ) || exit;

$fec_books = fec_get_items( 'livro' );
if ( ! $fec_books ) {
	return;
}
?>
<section id="livros" class="section-spacing bg-linear-to-br from-gray-50 to-white">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<?php
		fec_section_heading(
			array(
				'title'         => 'Drops de Carreira',
				'subtitle'      => 'Conhecimento prático e inspiração para profissionais e organizações',
				'badge'         => 'Publicações',
				'badge_variant' => 'accent',
				'icon'          => 'book-open',
			)
		);
		?>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
			<?php foreach ( $fec_books as $fec_book ) : ?>
				<?php
				$fec_subtitle   = fec_field( 'subtitulo', $fec_book->ID );
				$fec_highlights = array_slice( (array) fec_field( 'destaques', $fec_book->ID, array() ), 0, 3 );
				$fec_buy        = fec_field( 'link_compra', $fec_book->ID );
				?>
				<article class="group flex flex-col h-full">
					<div class="relative mb-6 overflow-hidden rounded-lg shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
						<div class="aspect-2/3 relative bg-gray-100">
							<?php
							echo get_the_post_thumbnail(
								$fec_book,
								'large',
								array(
									'class' => 'absolute inset-0 w-full h-full object-cover',
									'alt'   => 'Capa do livro ' . get_the_title( $fec_book ),
									'sizes' => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw',
								)
							);
							?>
						</div>
					</div>

					<div class="flex flex-col flex-grow">
						<h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo esc_html( get_the_title( $fec_book ) ); ?></h3>
						<?php if ( $fec_subtitle ) : ?>
							<p class="text-sm text-gray-500 mb-3"><?php echo esc_html( $fec_subtitle ); ?></p>
						<?php endif; ?>
						<p class="text-gray-600 mb-4 text-sm"><?php echo esc_html( fec_plain_content( $fec_book ) ); ?></p>

						<?php if ( $fec_highlights ) : ?>
							<ul class="space-y-1 mb-4">
								<?php foreach ( $fec_highlights as $fec_highlight ) : ?>
									<li class="flex items-start text-sm">
										<svg class="w-4 h-4 text-accent-gold-500 mr-2 mt-0.5 shrink-0" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
										<span class="text-gray-700"><?php echo esc_html( $fec_highlight['texto'] ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( $fec_buy ) : ?>
							<div class="flex gap-3 mt-auto">
								<a href="<?php echo esc_url( $fec_buy ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( fec_btn( 'primary', 'sm', 'flex-1' ) ); ?>">
									Adquirir<span class="sr-only"> (abre em nova aba)</span>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
