<?php
/**
 * Gera seed/images/favicon.png (512×512), o ícone do site: monograma "FE" em Montserrat sobre o
 * azul da marca, com o ponto dourado que fecha o logotipo do cabeçalho ("Flávio Emílio •").
 * Desenho chapado, sem brilho nem degradê, para continuar legível em 16×16 na aba do navegador.
 *
 * Roda no container do Apache (Imagick com PNG):  npm run favicon
 * O seed importa o arquivo e o define como ícone do site (Aparência → Personalizar → Identidade do site).
 */

$fonts = '/fonts/';
$bold  = $fonts . 'montserrat-latin-700-normal.woff2';
$out   = $argv[1] ?? '/seed/images/favicon.png';

if ( ! is_readable( $bold ) ) {
	fwrite( STDERR, "Fonte não encontrada: {$bold} (rode `npm run fonts` antes)\n" );
	exit( 1 );
}

// Desenha em 4× e reduz no fim: bordas e letras saem bem suavizadas.
$scale = 4;
$size  = 512 * $scale;
$navy  = '#172554'; // brand-blue-900
$gold  = '#fbbf24'; // accent-gold-400

$canvas = new Imagick();
$canvas->newImage( $size, $size, new ImagickPixel( 'none' ) );
$canvas->setImageFormat( 'png' );

// Fundo: quadrado de cantos arredondados (o celular aplica a própria máscara por cima).
$bg = new ImagickDraw();
$bg->setFillColor( new ImagickPixel( $navy ) );
$bg->roundRectangle( 0, 0, $size - 1, $size - 1, 112 * $scale, 112 * $scale );
$canvas->drawImage( $bg );

// Monograma: "F" e "E" coladas (kerning negativo), centralizadas pela largura/altura reais.
$text = new ImagickDraw();
$text->setFont( $bold );
$text->setFontSize( 248 * $scale );
$text->setFillColor( new ImagickPixel( '#ffffff' ) );
$text->setTextAntialias( true );
$text->setTextKerning( -10 * $scale );

$m      = $canvas->queryFontMetrics( $text, 'FE' );
$dot_r  = 26 * $scale;
$gap    = 12 * $scale;
$full_w = $m['textWidth'] + $gap + $dot_r * 2;
$cap_h  = $m['ascender'] * 0.70; // altura das maiúsculas no Montserrat ≈ 70% do ascender
$x      = ( $size - $full_w ) / 2;
$base   = ( $size + $cap_h ) / 2;

$canvas->annotateImage( $text, $x, $base, 0, 'FE' );

// Ponto dourado na linha de base, como no logotipo do cabeçalho.
$dot = new ImagickDraw();
$dot->setFillColor( new ImagickPixel( $gold ) );
$dot_cx = $x + $m['textWidth'] + $gap + $dot_r;
$dot_cy = $base - $dot_r;
$dot->circle( $dot_cx, $dot_cy, $dot_cx + $dot_r, $dot_cy );
$canvas->drawImage( $dot );

$canvas->resizeImage( 512, 512, Imagick::FILTER_LANCZOS, 1 );
$canvas->stripImage();
$canvas->writeImage( 'png32:' . $out );

printf( "Gerado: %s (%d KB)\n", $out, (int) ceil( filesize( $out ) / 1024 ) );
