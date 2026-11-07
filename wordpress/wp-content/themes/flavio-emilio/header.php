<?php
/**
 * Header. Portado de components/layout/Header.tsx + components/client/MobileMenu.tsx.
 * Comportamento (rolagem, abrir/fechar menu) em assets/src/main.js.
 */

defined( 'ABSPATH' ) || exit;

// Na home o header começa transparente sobre o Hero; nas outras páginas já é sólido (prop `fixed` do Next).
$fec_header_solid = ! is_front_page();
$fec_contato_url  = home_url( '/#contato' );
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
<a href="#conteudo" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[60] focus:bg-white focus:px-4 focus:py-2 focus:rounded">Pular para o conteúdo</a>

<header
	data-fec-header
	data-always-solid="<?php echo $fec_header_solid ? '1' : '0'; ?>"
	<?php echo $fec_header_solid ? 'data-solid="1"' : ''; ?>
	class="group/header fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent py-4 data-[solid=1]:bg-white data-[solid=1]:shadow-md data-[solid=1]:py-3"
>
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex items-center justify-between">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center group">
				<span class="text-2xl transition-colors duration-300 flex items-baseline gap-1 text-white group-data-[solid=1]/header:text-gray-900">
					<span class="font-extrabold tracking-tight">Flávio</span>
					<span class="font-medium tracking-tighter opacity-90 group-hover:opacity-100 transition-opacity">Emílio</span>
					<span class="h-2 w-2 rounded-full ml-0.5 bg-blue-600"></span>
				</span>
			</a>

			<nav class="hidden md:flex items-center space-x-8" aria-label="Principal">
				<?php fec_menu_links( 'primary', 'font-semibold transition-colors hover:text-primary text-white group-data-[solid=1]/header:text-gray-700' ); ?>
			</nav>

			<div class="flex items-center space-x-4">
				<a href="<?php echo esc_url( $fec_contato_url ); ?>" class="<?php echo esc_attr( fec_btn( 'primary', 'default', 'hidden md:inline-block' ) ); ?>">
					Entrar em Contato
				</a>

				<button type="button" data-fec-menu-open aria-expanded="false" aria-controls="fec-mobile-menu" aria-label="Menu de navegação"
					class="md:hidden p-2 hover:text-primary transition-colors text-white group-data-[solid=1]/header:text-gray-900">
					<svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
				</button>
			</div>
		</div>
	</div>
</header>

<?php /* Menu mobile: painel lateral + overlay (fora do <header> para não herdar o padding/transição) */ ?>
<div data-fec-menu-overlay hidden class="fixed inset-0 bg-black/60 z-[55] md:hidden"></div>
<div id="fec-mobile-menu" data-fec-menu aria-hidden="true" inert
	class="fixed top-0 right-0 h-full w-64 bg-gray-100 shadow-2xl z-[60] transform transition-transform duration-300 ease-in-out md:hidden translate-x-full data-[open=1]:translate-x-0">
	<div class="flex flex-col h-full">
		<div class="flex justify-end p-4">
			<button type="button" data-fec-menu-close class="p-2 text-gray-700 hover:text-primary transition-colors" aria-label="Fechar menu">
				<svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path d="M6 18L18 6M6 6l12 12"/></svg>
			</button>
		</div>
		<nav class="flex flex-col px-4 py-8 space-y-4" aria-label="Menu mobile">
			<?php $fec_mobile_link = 'text-lg font-semibold text-gray-700 hover:text-primary transition-colors py-2'; ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( $fec_mobile_link ); ?>">Início</a>
			<?php fec_menu_links( 'primary', $fec_mobile_link ); ?>
			<a href="<?php echo esc_url( $fec_contato_url ); ?>" class="<?php echo esc_attr( $fec_mobile_link ); ?>">Contato</a>
		</nav>
	</div>
</div>
