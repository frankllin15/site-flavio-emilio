<?php
/**
 * Página não encontrada (app/servicos/[id]/not-found.tsx, generalizada).
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="conteudo" class="min-h-screen bg-white flex items-center justify-center pt-20">
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center max-w-2xl mx-auto py-16">
			<p class="text-6xl font-bold text-gray-900 mb-4">404</p>
			<h1 class="text-3xl font-semibold text-gray-700 mb-6">Página não encontrada</h1>
			<p class="text-lg text-gray-600 mb-8">O conteúdo que você está procurando não existe ou foi removido.</p>
			<div class="flex flex-col sm:flex-row gap-4 justify-center">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'lg' ) ); ?>">Ir para o início</a>
				<a href="<?php echo esc_url( home_url( '/#servicos' ) ); ?>" class="<?php echo esc_attr( fec_btn( 'secondary', 'lg' ) ); ?>">Ver Serviços</a>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
