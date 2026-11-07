<?php
/**
 * /eventos (app/eventos/page.tsx): todos os eventos, sem paginação.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="conteudo" class="min-h-screen bg-gray-50 pt-20">
	<section class="section-spacing bg-white border-b border-gray-200">
		<div class="container mx-auto px-4 sm:px-6 lg:px-8">
			<div class="max-w-4xl mx-auto text-center">
				<h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Eventos Realizados</h1>
				<p class="text-xl text-gray-600 leading-relaxed">
					Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações em todo o Brasil.
					Cada evento representa um momento de transformação, aprendizado e fortalecimento de equipes.
				</p>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/sections/events', null, array( 'show_heading' => false, 'show_view_all' => false ) ); ?>
</main>
<?php
get_footer();
