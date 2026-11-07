<?php
/**
 * Header. Portado de components/layout/Header.tsx (o comportamento de rolagem fica em assets/src/main.js).
 * O acabamento visual completo (menu mobile etc.) é da fase 4.
 */

defined( 'ABSPATH' ) || exit;

$fec_header_fixed = ! is_front_page(); // páginas internas usam o header já sólido
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'antialiased' ); ?>>
<?php wp_body_open(); ?>

<header
	data-fec-header
	data-fixed="<?php echo $fec_header_fixed ? '1' : '0'; ?>"
	class="group/header fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent py-4 data-[solid=1]:bg-white data-[solid=1]:shadow-md data-[solid=1]:py-3"
	<?php echo $fec_header_fixed ? 'data-solid="1"' : ''; ?>
>
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex items-center justify-between">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center group">
				<span class="text-2xl transition-colors duration-300 flex items-baseline gap-1 text-white group-data-[solid=1]/header:text-gray-900">
					<span class="font-extrabold tracking-tight">Flávio</span>
					<span class="font-medium tracking-tighter opacity-90 group-hover:opacity-100 transition-opacity">Emílio</span>
					<span class="h-2 w-2 rounded-full ml-0.5 animate-pulse-slow bg-blue-600"></span>
				</span>
			</a>

			<nav class="hidden md:flex items-center space-x-8" aria-label="Principal">
				<?php fec_menu_links( 'primary', 'font-semibold transition-colors hover:text-primary text-white group-data-[solid=1]/header:text-gray-700' ); ?>
			</nav>

			<div class="flex items-center space-x-4">
				<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>"
					class="hidden md:inline-block rounded-md bg-primary px-6 py-3 font-semibold text-white transition-colors hover:bg-brand-blue-600">
					Entrar em Contato
				</a>

				<button type="button" data-fec-menu-toggle aria-expanded="false" aria-controls="fec-mobile-menu"
					class="md:hidden p-2 text-white group-data-[solid=1]/header:text-gray-900" aria-label="Abrir menu">
					<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
				</button>
			</div>
		</div>

		<nav id="fec-mobile-menu" hidden class="md:hidden mt-4 rounded-lg bg-white p-4 shadow-lg flex flex-col gap-3" aria-label="Menu mobile">
			<?php fec_menu_links( 'primary', 'font-semibold text-gray-700 hover:text-primary' ); ?>
			<a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>" class="mt-2 rounded-md bg-primary px-4 py-2 text-center font-semibold text-white">Entrar em Contato</a>
		</nav>
	</div>
</header>
