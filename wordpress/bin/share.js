// Sobe o Cloudflare Tunnel (serviço "tunnel" do docker-compose) e mostra o link público.
// Uso: npm run share   |   parar: npm run share:stop
import { execSync } from 'node:child_process';

const run = (cmd) => execSync(cmd, { encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] });

console.log('> Subindo o túnel (cloudflared)...');
run('docker compose --profile share up -d tunnel');

const deadline = Date.now() + 60_000;
let url = '';
while (!url && Date.now() < deadline) {
  const logs = run('docker compose --profile share logs tunnel --no-color 2>&1');
  url = [...logs.matchAll(/https:\/\/[a-z0-9-]+\.trycloudflare\.com/g)].pop()?.[0] ?? '';
  if (!url) await new Promise((r) => setTimeout(r, 1000));
}

if (!url) {
  console.error('Não apareceu o link em 60 s. Veja: docker compose --profile share logs tunnel');
  process.exit(1);
}

console.log(`
  Link público: ${url}

  - Só leitura: login e painel ficam bloqueados por esse endereço (use o localhost para editar).
  - E-mails do site continuam indo para o Mailpit (http://localhost:8025).
  - O link vale enquanto o túnel e o Docker estiverem rodando; muda a cada reinício.
  - Parar: npm run share:stop
`);
