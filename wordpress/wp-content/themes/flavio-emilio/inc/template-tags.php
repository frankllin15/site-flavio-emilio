<?php
/**
 * Helpers de template: equivalentes PHP dos componentes de UI do app Next
 * (components/ui/Button, SectionHeading, Carousel...).
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Dados
 * ---------------------------------------------------------------------- */

/**
 * Lê um campo da página de opções "Conteúdo do Site" (SCF/ACF), com fallback seguro
 * caso o plugin de campos esteja desativado.
 */
function fec_option( string $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, 'option' );
	return ( null === $value || '' === $value || false === $value ) ? $default : $value;
}

/**
 * Lê um campo de um post, com fallback.
 */
function fec_field( string $name, $post_id = null, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, $post_id ?? get_the_ID() );
	return ( null === $value || '' === $value || false === $value ) ? $default : $value;
}

/**
 * Itens publicados de um tipo de conteúdo, na ordem definida no painel (campo "Ordem").
 *
 * @return WP_Post[]
 */
function fec_get_items( string $post_type, int $limit = -1 ): array {
	return fec_prime_images( get_posts(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => $limit,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	) );
}

/**
 * Carrega de uma vez as imagens dos itens (destacada, logo, foto); sem isso, cada imagem custa
 * duas consultas ao montar a seção. As metas dos itens já vêm carregadas pelo get_posts().
 *
 * @param WP_Post[] $posts
 * @return WP_Post[]
 */
function fec_prime_images( array $posts ): array {
	$ids = array();
	foreach ( $posts as $post ) {
		foreach ( array( '_thumbnail_id', 'logo', 'avatar' ) as $key ) {
			$ids[] = (int) get_post_meta( $post->ID, $key, true );
		}
	}
	_prime_post_caches( array_filter( $ids ), false, true );
	return $posts;
}

/**
 * Eventos publicados, do mais recente para o mais antigo (campo "data").
 *
 * @return WP_Post[]
 */
function fec_get_events( int $limit = -1 ): array {
	return fec_prime_images( get_posts(
		array(
			'post_type'      => 'evento',
			'posts_per_page' => $limit,
			'meta_key'       => 'data', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	) );
}

/** Classes do Card com hover (components/ui/Card.tsx). */
function fec_card_class( string $extra = '' ): string {
	return trim( 'bg-white rounded-lg shadow-md p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 ' . $extra );
}

/**
 * Textos padrão do cabeçalho de cada seção da home (selo, título, subtítulo e botão).
 * Editáveis em Conteúdo do Site → Seções da home; campo vazio volta para o padrão daqui.
 * O seed também usa esta lista para preencher os campos na primeira importação.
 */
function fec_section_defaults(): array {
	static $defaults = null;
	return $defaults ??= array(
		'servicos'    => array(
			'selo'      => 'Soluções',
			'titulo'    => 'O que oferecemos',
			'subtitulo' => 'Estratégias personalizadas para desenvolver pessoas e transformar organizações',
			'botao'     => 'Solicitar Proposta',
		),
		'palestras'   => array(
			'selo'      => 'Palestras',
			'titulo'    => 'Nossas Palestras',
			'subtitulo' => 'Conteúdos inspiradores que transformam perspectivas e impulsionam resultados',
			'botao'     => 'Solicitar Palestra',
		),
		'eventos'     => array(
			'selo'      => 'Histórico',
			'titulo'    => 'Eventos Realizados',
			'subtitulo' => 'Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações',
			'botao'     => 'Solicitar Proposta para Evento',
		),
		'depoimentos' => array(
			'selo'      => 'Feedback',
			'titulo'    => 'Depoimentos',
			'subtitulo' => 'O que nossos clientes dizem sobre nós',
		),
		'blog'        => array(
			'selo' => 'Blog',
		),
		'livros'      => array(
			'selo'      => 'Publicações',
			'titulo'    => 'Livros',
			'subtitulo' => 'Conhecimento prático e inspiração para profissionais e organizações',
		),
	);
}

/** Texto de uma seção da home: valor do painel ou, se vazio, o padrão. */
function fec_section_text( string $section, string $field ): string {
	static $cache = array(); // cada seção é lida do banco uma vez por página
	if ( ! array_key_exists( $section, $cache ) ) {
		$saved             = fec_option( 'secao_' . $section, array() );
		$cache[ $section ] = is_array( $saved ) ? $saved : array();
	}
	$value = trim( (string) ( $cache[ $section ][ $field ] ?? '' ) );
	return '' !== $value ? $value : (string) ( fec_section_defaults()[ $section ][ $field ] ?? '' );
}

/** Texto puro do conteúdo do post (para cards com line-clamp). */
function fec_plain_content( $post = null ): string {
	return trim( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post ) ) ) );
}

/** Minutos de leitura (200 palavras/min, como em types/blog.ts). */
function fec_read_time( $post = null ): int {
	$words = count( preg_split( '/\s+/u', fec_plain_content( $post ), -1, PREG_SPLIT_NO_EMPTY ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/** "Novembro 2025" a partir do campo de data (Ymd) do evento. */
function fec_event_month( $post = null ): string {
	$raw = fec_field( 'data', $post ? get_post( $post )->ID : null );
	if ( ! $raw ) {
		return '';
	}
	$date = DateTimeImmutable::createFromFormat( '!Ymd', (string) $raw, wp_timezone() );
	if ( ! $date ) {
		return '';
	}
	$label = wp_date( 'F Y', $date->getTimestamp() );
	return mb_strtoupper( mb_substr( $label, 0, 1 ) ) . mb_substr( $label, 1 );
}

/** Iniciais para o avatar do autor ("Flávio Emílio Cavalcanti" → "FE"). */
function fec_initials( string $name ): string {
	$parts = preg_split( '/\s+/u', trim( $name ), -1, PREG_SPLIT_NO_EMPTY );
	$first = array_slice( $parts, 0, 2 );
	return mb_strtoupper( implode( '', array_map( static fn( $p ) => mb_substr( $p, 0, 1 ), $first ) ) );
}

function fec_whatsapp_url(): string {
	$number = preg_replace( '/\D+/', '', (string) fec_option( 'whatsapp_numero' ) );
	if ( ! $number ) {
		return '';
	}
	$message = (string) fec_option( 'whatsapp_mensagem' );
	return 'https://wa.me/' . $number . ( $message ? '?text=' . rawurlencode( $message ) : '' );
}

/* -------------------------------------------------------------------------
 * Ícones (Lucide, gerados em inc/icons.php)
 * ---------------------------------------------------------------------- */

function fec_icon( string $name, string $class = 'w-6 h-6', array $attrs = array() ): string {
	static $icons = null;
	$icons ??= require __DIR__ . '/icons.php';

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	$attrs = array_merge(
		array(
			'xmlns'           => 'http://www.w3.org/2000/svg',
			'width'           => '24',
			'height'          => '24',
			'viewBox'         => '0 0 24 24',
			'fill'            => 'none',
			'stroke'          => 'currentColor',
			'stroke-width'    => '2',
			'stroke-linecap'  => 'round',
			'stroke-linejoin' => 'round',
			'class'           => trim( "lucide lucide-{$name} {$class}" ),
			'aria-hidden'     => 'true',
		),
		$attrs
	);

	$html = '';
	foreach ( $attrs as $key => $value ) {
		$html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
	}
	return "<svg{$html}>{$icons[$name]}</svg>";
}

/* -------------------------------------------------------------------------
 * Botão (components/ui/Button.tsx)
 * ---------------------------------------------------------------------- */

/**
 * Classes do botão. Use $variant = '' quando for sobrescrever cores/bordas por completo
 * (o PHP não tem o tailwind-merge do Next para resolver conflitos de classe).
 */
function fec_btn( string $variant = 'primary', string $size = 'default', string $extra = '' ): string {
	$base = "inline-flex items-center justify-center gap-2 whitespace-nowrap text-center font-semibold transition-all [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none no-underline";

	$variants = array(
		'primary'   => 'bg-[var(--color-primary)] text-white rounded-lg hover:bg-[var(--color-brand-blue-600)] hover:-translate-y-0.5 active:translate-y-0 focus:shadow-[0_0_0_3px_rgba(30,64,175,0.3)]',
		'secondary' => 'bg-transparent text-[var(--color-primary)] border-2 border-[var(--color-primary)] rounded-lg hover:bg-[var(--color-primary)] hover:text-white hover:-translate-y-0.5 active:translate-y-0 focus:shadow-[0_0_0_3px_rgba(30,64,175,0.3)]',
		'accent'    => 'bg-[var(--color-accent)] text-brand-blue-900 rounded-lg hover:bg-[var(--color-accent-gold-400)] hover:-translate-y-0.5 active:translate-y-0 focus:shadow-[0_0_0_3px_rgba(245,158,11,0.3)]',
		'outline'   => 'border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground',
		''          => '',
	);

	$sizes = array(
		'default' => 'px-6 py-3 text-base has-[>svg]:px-4',
		'sm'      => 'px-3 py-2 text-sm rounded-md gap-1.5 has-[>svg]:px-2.5',
		'lg'      => 'px-8 py-4 text-lg rounded-md has-[>svg]:px-6',
	);

	return fec_merge_classes( $base . ' ' . ( $variants[ $variant ] ?? '' ) . ' ' . ( $sizes[ $size ] ?? $sizes['default'] ), $extra );
}

/**
 * Versão mínima do tailwind-merge: classes de $extra substituem as de $base do mesmo grupo
 * (display, padding, tamanho/cor de texto, fundo, borda arredondada), sem prefixo de variante.
 */
function fec_merge_classes( string $base, string $extra ): string {
	$group = static function ( string $class ): ?string {
		$rules = array(
			'display'    => '/^(hidden|block|inline-block|inline|flex|inline-flex|grid|inline-grid)$/',
			'px'         => '/^px-/',
			'py'         => '/^py-/',
			'font-size'  => '/^text-(xs|sm|base|lg|xl|[2-9]xl)$/',
			'text-color' => '/^text-(?!(xs|sm|base|lg|xl|[2-9]xl|left|center|right|justify)$)/',
			'bg'         => '/^bg-(?!linear|gradient|radial)/',
			'rounded'    => '/^rounded(-|$)/',
		);
		foreach ( $rules as $name => $pattern ) {
			if ( preg_match( $pattern, $class ) ) {
				return $name;
			}
		}
		return null;
	};

	$extra_classes = preg_split( '/\s+/', trim( $extra ), -1, PREG_SPLIT_NO_EMPTY );
	$overridden    = array();
	foreach ( $extra_classes as $class ) {
		if ( ! str_contains( $class, ':' ) && ( $g = $group( $class ) ) ) { // phpcs:ignore
			$overridden[ $g ] = true;
		}
	}

	$kept = array_filter(
		preg_split( '/\s+/', trim( $base ), -1, PREG_SPLIT_NO_EMPTY ),
		static function ( $class ) use ( $group, $overridden ) {
			if ( str_contains( $class, ':' ) ) {
				return true;
			}
			$g = $group( $class );
			return ! ( $g && isset( $overridden[ $g ] ) );
		}
	);

	return implode( ' ', array_merge( $kept, $extra_classes ) );
}

/* -------------------------------------------------------------------------
 * Título de seção (components/ui/SectionHeading.tsx)
 * ---------------------------------------------------------------------- */

/**
 * @param array{title:string, subtitle?:string, centered?:bool, badge?:string, badge_variant?:string, icon?:string, class?:string} $args
 */
function fec_section_heading( array $args ): void {
	$args = array_merge(
		array(
			'subtitle'      => '',
			'centered'      => true,
			'badge'         => '',
			'badge_variant' => 'primary',
			'icon'          => '',
			'class'         => '',
		),
		$args
	);

	$badge_variants = array(
		'primary'   => 'bg-brand-blue-100 text-brand-blue-700 border-brand-blue-200',
		'accent'    => 'bg-accent-gold-100 text-accent-gold-700 border-accent-gold-200',
		'secondary' => 'bg-gray-100 text-gray-700 border-gray-200',
	);
	$centered = (bool) $args['centered'];
	?>
	<div class="mb-12 animate-fade-in <?php echo $centered ? 'flex flex-col items-center' : ''; ?> <?php echo esc_attr( $args['class'] ); ?>">
		<?php if ( $args['badge'] ) : ?>
			<div class="mb-4">
				<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold border uppercase tracking-wide <?php echo esc_attr( $badge_variants[ $args['badge_variant'] ] ?? $badge_variants['primary'] ); ?>">
					<?php echo esc_html( $args['badge'] ); ?>
					<?php echo $args['icon'] ? fec_icon( $args['icon'], 'w-4 h-4 text-current md:hidden' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</span>
			</div>
		<?php endif; ?>

		<h2 class="text-4xl md:text-5xl font-bold mb-4 relative inline-flex items-center gap-3 text-gray-900 <?php echo $centered ? 'text-center' : ''; ?>">
			<?php echo $args['icon'] ? fec_icon( $args['icon'], 'w-10 h-10 md:w-12 md:h-12 text-accent-gold-500 shrink-0 hidden md:inline-block' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo esc_html( $args['title'] ); ?>
		</h2>

		<div class="flex items-center gap-2 mb-4" aria-hidden="true">
			<div class="h-1 w-12 bg-gradient-to-r from-brand-blue-500 to-accent-gold-400 rounded-full"></div>
			<div class="h-1 w-12 bg-accent-gold-400 rounded-full"></div>
			<div class="h-1 w-12 bg-gradient-to-r from-accent-gold-400 to-brand-blue-500 rounded-full"></div>
		</div>

		<?php if ( $args['subtitle'] ) : ?>
			<p class="text-xl text-gray-600 <?php echo $centered ? 'text-center max-w-3xl' : 'max-w-full'; ?>"><?php echo esc_html( $args['subtitle'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Carrossel (components/ui/Carousel.tsx). Markup aqui; o Embla é ligado em main.js.
 * Sem JS, mostra os primeiros cards e os botões ficam ocultos (disabled).
 * ---------------------------------------------------------------------- */

function fec_carousel_start( string $prev_label = 'Anterior', string $next_label = 'Próximo', string $label = 'Carrossel' ): void {
	$btn = 'absolute top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full bg-linear-to-br from-white to-gray-50 shadow-xl border-2 border-accent-gold-500/20 hidden md:flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:border-accent-gold-500 active:scale-95 disabled:opacity-0 disabled:pointer-events-none';
	?>
	<div class="relative" data-fec-carousel role="region" aria-roledescription="carrossel" aria-label="<?php echo esc_attr( $label ); ?>">
		<button type="button" data-fec-prev disabled class="left-0 -translate-x-4 <?php echo esc_attr( $btn ); ?>" aria-label="<?php echo esc_attr( $prev_label ); ?>">
			<?php echo fec_icon( 'chevron-left', 'w-6 h-6 text-gray-700' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<button type="button" data-fec-next disabled class="right-0 translate-x-4 <?php echo esc_attr( $btn ); ?>" aria-label="<?php echo esc_attr( $next_label ); ?>">
			<?php echo fec_icon( 'chevron-right', 'w-6 h-6 text-gray-700' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<div class="overflow-hidden" data-fec-viewport>
			<div class="flex">
	<?php
}

function fec_carousel_item_class(): string {
	return 'flex-[0_0_100%] md:flex-[0_0_50%] lg:flex-[0_0_33.333%] min-w-0 px-2';
}

function fec_carousel_end( string $hint = 'Arraste para o lado' ): void {
	?>
			</div>
		</div>
		<div class="md:hidden text-center mt-4 mb-2 text-sm text-gray-500 flex items-center justify-center gap-2">
			<?php echo fec_icon( 'move-left' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><?php echo esc_html( $hint ); ?></span>
			<?php echo fec_icon( 'move-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="flex justify-center gap-2 mt-8" data-fec-dots></div>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Menus
 * ---------------------------------------------------------------------- */

/**
 * Menu como links simples (<a>), mantendo as classes do layout original.
 */
function fec_menu_links( string $location, string $link_class = '' ): void {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return;
	}
	$items = wp_get_nav_menu_items( $locations[ $location ] );
	foreach ( (array) $items as $item ) {
		printf(
			'<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( $item->url ),
			esc_attr( $link_class ),
			esc_html( $item->title )
		);
	}
}

/** Onda decorativa (components/ui/decorative/WaveDivider.tsx). */
function fec_wave( string $variant = 'white-to-gray', string $position = 'bottom' ): void {
	$colors = array(
		'white-to-gray' => '#f9fafb',
		'gray-to-white' => '#ffffff',
		'blue-to-white' => '#ffffff',
		'dark'          => '#111827',
	);
	?>
	<div class="absolute inset-x-0 <?php echo 'bottom' === $position ? 'bottom-0' : 'top-0'; ?> h-16 md:h-24 pointer-events-none z-10" aria-hidden="true">
		<svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-full">
			<path d="M0,0 C150,80 350,80 600,60 C850,40 1050,40 1200,80 L1200,120 L0,120 Z" fill="<?php echo esc_attr( $colors[ $variant ] ?? '#ffffff' ); ?>"/>
		</svg>
	</div>
	<?php
}
