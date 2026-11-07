# WordPress: ambiente local

Port do site para WordPress. Plano completo: [../docs/plano-migracao-wordpress.md](../docs/plano-migracao-wordpress.md).

## Requisitos

- Docker Desktop
- Node 22.18+ (para compilar o tema e exportar o conteúdo do app Next)

## Primeira vez

```bash
cd wordpress
npm install
npm run env      # cria .env a partir do .env.example
npm run up       # sobe banco, WordPress, Mailpit e Adminer
npm run build    # fontes + CSS + JS do tema
npm run setup    # instala WP em pt-BR, plugins e ativa o tema
npm run seed     # exporta lib/data/*.ts e importa no WordPress
```

| O quê | Onde |
|---|---|
| Site | http://localhost:8080 |
| Painel | http://localhost:8080/wp-admin (usuário/senha no `.env`) |
| E-mails enviados (Mailpit) | http://localhost:8025 |
| Banco (Adminer) | http://localhost:8081, servidor `db`, credenciais do `.env` |

## Dia a dia

```bash
npm run up
npm run dev:css   # Tailwind em watch (em outro terminal: npm run dev:js)
npm run wp -- plugin list   # qualquer comando WP-CLI
npm run down
```

- `npm run seed:import`: cria o que falta e não mexe no que já existe.
- `npm run seed:import -- atualizar`: sobrescreve com os dados do seed (apaga edições feitas no painel local).
- `npm run reset`: apaga banco e arquivos do WordPress. Depois, rode `up`, `setup` e `seed` de novo.

## Estrutura

```
docker-compose.yml          db (MariaDB 10.11), wordpress (PHP 8.3), wpcli, mailpit, adminer
bin/setup.sh                instalação/configuração via WP-CLI (pode rodar de novo)
docker/                     arquivos só do ambiente local (limites de upload do PHP)
seed/export-data.ts         lib/data/*.ts + textos fixos dos componentes → seed/data.json
seed/import.php             data.json → posts, campos, imagens, menus, páginas Início/Blog, formulário
seed/contact-form.php       formulário de contato (CF7) e e-mail
wp-content/mu-plugins/      fec-core: tipos de conteúdo, campos (SCF), ajustes do painel, SMTP/Turnstile
wp-content/themes/flavio-emilio/
  front-page.php            home: monta as seções de template-parts/sections/
  single-servico.php        /servicos/<slug>
  single-palestra.php       /palestras/<slug>
  archive-evento.php        /eventos
  home.php, single.php      blog (listagem e post)
  template-parts/           seções da home, card de post, bloco de tópicos
  inc/template-tags.php     helpers equivalentes aos componentes de UI do Next (botão, título de seção, carrossel)
  inc/icons.php             ícones Lucide, GERADO por `npm run icons` (bin/build-icons.js)
  assets/src/main.css       Tailwind 4 (portado de app/globals.css)
  assets/src/main.js        header, menu mobile, voltar ao topo e carrossel (Embla)
  assets/dist, assets/fonts gerados por `npm run build` (não versionados)
```

As classes Tailwind dos templates foram copiadas dos componentes `.tsx`. `fec_btn()` faz uma versão mínima do `tailwind-merge`: classes extras substituem as do mesmo grupo (display, padding, texto, fundo, borda).

Só `wp-content/themes/flavio-emilio` (com `assets/dist` e `assets/fonts` já gerados) e `wp-content/mu-plugins` vão para a hospedagem.

## Formulário de contato e e-mail

- O formulário é do Contact Form 7. O seed cria o formulário "Contato (site)" a partir de `seed/contact-form.php`, com os campos e o e-mail portados de `ContactForm.tsx` e `route.ts`.
- Ele começa **desligado**, como no site atual (foi retirado da home no commit c0ffab0). Para ligar: **Conteúdo do Site → Contato → Mostrar formulário de contato**.
- Destinatário, assunto e textos do e-mail: menu **Contato** → editar o formulário → aba **E-mail**.
- As mensagens também ficam salvas em **Flamingo → Mensagens recebidas**.
- No Docker, todo e-mail cai no Mailpit (http://localhost:8025), e o Turnstile usa as chaves públicas de teste da Cloudflare, que sempre aprovam.

## Produção: `wp-config.php`

O SMTP e o anti-spam são lidos por `fec-core/mail.php` de constantes, nunca do banco. Adicione ao `wp-config.php` da hospedagem:

```php
// SMTP: da hospedagem (Hostinger/Locaweb) ou da Resend (smtp.resend.com, usuário "resend", senha = API key)
define( 'FEC_SMTP_HOST', 'smtp.exemplo.com' );
define( 'FEC_SMTP_PORT', 587 );
define( 'FEC_SMTP_SECURE', 'tls' );          // 'tls' (587), 'ssl' (465) ou ''
define( 'FEC_SMTP_USER', 'usuario' );
define( 'FEC_SMTP_PASS', 'senha' );
define( 'FEC_MAIL_FROM', 'site@flavioemilio.com.br' ); // precisa ser autorizado no SMTP (SPF/DKIM do domínio)
define( 'FEC_MAIL_FROM_NAME', 'Flávio Emílio Cavalcanti' );

// Cloudflare Turnstile: crie um widget em dash.cloudflare.com → Turnstile para o domínio do site
define( 'FEC_TURNSTILE_SITEKEY', '...' );
define( 'FEC_TURNSTILE_SECRET', '...' );
```

Sem `FEC_SMTP_HOST`, o WordPress usa o envio padrão do servidor (costuma cair em spam). Sem as chaves do Turnstile, o formulário funciona sem o desafio anti-spam. Depois de configurar, envie um teste pelo formulário e confira se chegou fora do spam.

## Observações

- Depoimentos e posts do blog foram importados como **rascunho**: no app Next eles são exemplos (nomes e empresas genéricos, posts sem texto). Publique pelo painel depois de revisar.
- `public/images/blog/post-4.jpg` não existe no app Next, então esse post ficou sem imagem destacada.
- Os campos são registrados em PHP (`fec-core/fields.php`). Não altere as chaves `field_fec_*` depois que houver conteúdo em produção.
