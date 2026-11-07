<?php
/**
 * Gera seed/generated/og-image.jpg (1200×630), a imagem padrão de compartilhamento (Open Graph).
 * O app Next apontava para /images/og-image.jpg, que nunca existiu.
 *
 * Roda no container do Apache, que tem o Imagick com JPEG/PNG (o do WP-CLI não tem):
 *   npm run og
 *
 * O resultado é versionado; o seed o importa para a biblioteca de mídia e o define no Yoast.
 */

$hero = '/seed-images/hero.png';
$font = '/fonts/montserrat-latin-wght-normal.woff2';
$out  = '/seed/generated/og-image.jpg';

foreach ( array( $hero, $font ) as $required ) {
	if ( ! is_readable( $required ) ) {
		fwrite( STDERR, "Arquivo não encontrado: {$required} (rode `npm run fonts` antes?)\n" );
		exit( 1 );
	}
}

$w = 1200;
$h = 630;

$canvas = new Imagick();
$canvas->newImage( $w, $h, new ImagickPixel( '#172554' ) ); // brand-blue-900

// Foto do hero à esquerda, na altura total.
$photo = new Imagick( $hero );
$photo->resizeImage( 0, $h, Imagick::FILTER_LANCZOS, 1 );
$canvas->compositeImage( $photo, Imagick::COMPOSITE_OVER, 0, 0 );
$photo_w = $photo->getImageWidth();

// Degradê sobre a borda direita da foto, para emendar no fundo.
$fade = new Imagick();
$fade->newPseudoImage( $h, 220, 'gradient:rgba(23,37,84,0)-rgba(23,37,84,1)' );
$fade->rotateImage( new ImagickPixel( 'none' ), -90 );
$canvas->compositeImage( $fade, Imagick::COMPOSITE_OVER, $photo_w - 220, 0 );

$x    = $photo_w + 20;
$text = static function ( string $str, int $size, string $color, int $y ) use ( $canvas, $font, $x ) {
	$draw = new ImagickDraw();
	$draw->setFont( $font );
	$draw->setFontSize( $size );
	$draw->setFillColor( new ImagickPixel( $color ) );
	$canvas->annotateImage( $draw, $x, $y, 0, $str );
};

$text( 'Flávio Emílio', 60, '#ffffff', 210 );
$text( 'Cavalcanti', 60, '#ffffff', 280 );

$bar = new ImagickDraw();
$bar->setFillColor( new ImagickPixel( '#fbbf24' ) ); // accent-gold-400
$bar->rectangle( $x, 312, $x + 150, 318 );
$canvas->drawImage( $bar );

$text( 'Consultor Organizacional,', 30, '#f3f4f6', 380 );
$text( 'Palestrante e Especialista', 30, '#f3f4f6', 424 );
$text( 'em Gestão de Carreiras', 30, '#fbbf24', 468 );

if ( ! is_dir( dirname( $out ) ) ) {
	mkdir( dirname( $out ), 0775, true );
}
$canvas->setImageFormat( 'jpeg' );
$canvas->setImageCompressionQuality( 85 );
$canvas->stripImage();
$canvas->writeImage( $out );

echo "Gerado: seed/generated/og-image.jpg (" . round( filesize( $out ) / 1024 ) . " KB)\n";
