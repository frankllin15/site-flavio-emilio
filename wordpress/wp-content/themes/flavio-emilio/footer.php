<?php
/**
 * Rodapé. Portado de components/layout/Footer.tsx (+ ScrollToTopButton).
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="bg-gray-900 text-white pt-12 pb-8 border-t border-gray-800">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1fr_auto_1.1fr] gap-10 lg:gap-16 mb-10">
			<div>
				<h2 class="text-xl font-bold mb-4 text-white"><?php bloginfo( 'name' ); ?></h2>
				<p class="text-gray-400"><?php echo esc_html( fec_option( 'rodape_texto' ) ); ?></p>
			</div>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<div>
					<h2 class="text-xl font-bold mb-4 text-white">Links Rápidos</h2>
					<nav class="grid grid-cols-2 gap-x-8 gap-y-2" aria-label="Links rápidos">
						<?php fec_menu_links( 'footer', 'text-gray-400 hover:text-accent transition-colors' ); ?>
					</nav>
				</div>
			<?php endif; ?>

			<div class="md:col-span-2 lg:col-span-1">
				<?php get_template_part( 'template-parts/newsletter', null, array( 'variant' => 'footer', 'origem' => 'rodape' ) ); ?>
			</div>
		</div>

		<div class="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. Todos os direitos reservados.</p>
		</div>
	</div>
</footer>

<button type="button" data-fec-to-top hidden aria-label="Voltar ao topo"
	class="fixed bottom-6 right-6 z-40 bg-brand-blue-700 hover:bg-brand-blue-900 text-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110">
	<?php echo fec_icon( 'chevron-up' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</button>

<?php wp_footer(); ?>
</body>
</html>
