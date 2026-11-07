<?php
/**
 * Rodapé (esqueleto; o port fiel de components/layout/Footer.tsx é da fase 4).
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="bg-gray-950 text-gray-400 py-8">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
		&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Flávio Emílio Cavalcanti · Proficient. Todos os direitos reservados.
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
