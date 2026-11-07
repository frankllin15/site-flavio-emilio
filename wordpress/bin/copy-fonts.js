// Copia os woff2 (subset latin) do @fontsource para o tema.
import { copyFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const dest = join(root, 'wp-content/themes/flavio-emilio/assets/fonts');
mkdirSync(dest, { recursive: true });

const files = [
  ['@fontsource-variable/geist', 'geist-latin-wght-normal.woff2'],
  ['@fontsource-variable/montserrat', 'montserrat-latin-wght-normal.woff2'],
];

for (const [pkg, file] of files) {
  copyFileSync(join(root, 'node_modules', pkg, 'files', file), join(dest, file));
  console.log(`fonte copiada: ${file}`);
}
