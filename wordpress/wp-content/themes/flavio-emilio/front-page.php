<?php
/**
 * Home. Mesma sequência de seções de app/page.tsx.
 * Textos de cada seção: página "Conteúdo do Site" no painel e os tipos de conteúdo.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="conteudo">
	<?php
	get_template_part( 'template-parts/sections/hero' );
	get_template_part( 'template-parts/sections/about' );
	get_template_part( 'template-parts/sections/proficient' );
	get_template_part( 'template-parts/sections/services' );
	get_template_part( 'template-parts/sections/lectures' );
	get_template_part( 'template-parts/sections/events', null, array( 'limit' => 3 ) );
	get_template_part( 'template-parts/sections/clients' );
	get_template_part( 'template-parts/sections/testimonials' );
	get_template_part( 'template-parts/sections/blog' );
	get_template_part( 'template-parts/sections/books' );
	get_template_part( 'template-parts/sections/contact' );
	?>
</main>
<?php
get_footer();
