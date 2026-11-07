<?php
/**
 * Plugin Name: FEC Core
 * Description: Tipos de conteúdo (serviços, palestras, eventos, livros, clientes, depoimentos) e campos do site Flávio Emílio. Independe do tema.
 * Version: 0.1.0
 */

defined( 'ABSPATH' ) || exit;

// O WordPress só carrega arquivos soltos em mu-plugins/; o código fica na subpasta.
require __DIR__ . '/fec-core/post-types.php';
require __DIR__ . '/fec-core/fields.php';
require __DIR__ . '/fec-core/admin.php';
require __DIR__ . '/fec-core/mail.php';
