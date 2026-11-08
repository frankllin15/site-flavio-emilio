// Gera inc/icons.php com os ícones Lucide usados pelo tema (mesma biblioteca do app Next).
// Para adicionar um ícone: inclua o nome na lista abaixo e rode `npm run icons`.
import { readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const out = join(root, 'wp-content/themes/flavio-emilio/inc/icons.php');

const names = [
  'arrow-left', 'book-open', 'briefcase', 'calendar', 'check', 'chevron-left', 'chevron-right',
  'chevron-up', 'handshake', 'heart', 'mail', 'map-pin', 'move-left', 'move-right', 'presentation',
  'quote', 'shield', 'star', 'target', 'trending-up', 'users', 'zap',
  'share-2', 'link', 'list', 'search', 'clock', 'arrow-right', 'facebook', 'chevron-down', 'lightbulb', 'rss',
];

const version = JSON.parse(readFileSync(join(root, 'node_modules/lucide-static/package.json'), 'utf8')).version;

const entries = names.map((name) => {
  const svg = readFileSync(join(root, 'node_modules/lucide-static/icons', `${name}.svg`), 'utf8');
  const inner = svg
    .slice(svg.indexOf('>', svg.indexOf('<svg')) + 1, svg.lastIndexOf('</svg>'))
    .replace(/\s*\n\s*/g, '')
    .trim();
  return `\t'${name}' => '${inner.replace(/'/g, "\\'")}',`;
});

writeFileSync(
  out,
  `<?php
/**
 * Ícones Lucide v${version} (ISC). GERADO por \`npm run icons\` (bin/build-icons.js): não edite à mão.
 */

defined( 'ABSPATH' ) || exit;

return array(
${entries.join('\n')}
);
`
);
console.log(`inc/icons.php gerado (${names.length} ícones)`);
