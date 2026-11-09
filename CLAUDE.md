# CLAUDE.md

Guia para o Claude Code neste repositório.

## Projeto

Site de Flávio Emílio Cavalcanti (consultor organizacional, palestrante, gestão de carreiras), com o blog "Drops de Carreira". Idioma do conteúdo e das respostas: **português (pt-BR)**.

Stack: WordPress 7.1 (PHP 8.3) em Docker, tema clássico próprio, Tailwind CSS 4 (CLI), esbuild, Secure Custom Fields, Contact Form 7 + Flamingo, Yoast SEO e MailPoet. Hospedagem prevista: Hostinger (plano Premium). O app Next.js antigo está na branch `legacy-nextjs`.

Tudo fica em `wordpress/`. Detalhes de ambiente, estrutura e produção: `wordpress/README.md`. Planos: `docs/`.

## Comandos (rodar em `wordpress/`)

```bash
npm run up / down          # Docker: db, wordpress, mailpit, adminer
npm run build              # fontes + CSS + JS do tema (assets/dist e assets/fonts não vão para o git)
npm run dev:css / dev:js   # watch
npm run setup              # instala/configura o WP via WP-CLI (pode rodar de novo)
npm run seed               # importa seed/data.json e seed/images (idempotente; "-- atualizar" sobrescreve)
npm run wp -- <comando>    # WP-CLI
npm run share / share:stop # link público temporário (Cloudflare Tunnel), só leitura
```

Site http://localhost:8080 · Mailpit http://localhost:8025 · Adminer http://localhost:8081.

## Arquitetura

- `wp-content/mu-plugins/fec-core/`: tipos de conteúdo (servico, palestra, evento, livro, cliente, depoimento), campos SCF registrados em PHP (chaves estáveis `field_fec_<grupo>_<nome>`; não mudar com conteúdo em produção), página de opções "Conteúdo do Site", SMTP/Turnstile por constantes `FEC_*` (`fec_config()`), WebP, newsletter (endpoint `POST /wp-json/fec/v1/newsletter`).
- `wp-content/themes/flavio-emilio/`: templates, `template-parts/sections/*` da home, `inc/template-tags.php` (helpers `fec_option`, `fec_field`, `fec_btn`, `fec_section_heading`, `fec_section_text`…), `inc/blog.php`, `inc/og-post.php` (imagem de compartilhamento por post via Imagick), `assets/src/` (Tailwind e JS).
- `seed/`: `data.json` + `images/` → `import.php` (rodado com `wp eval-file`), mais `seo.php`, `newsletter.php`, `privacy.php`, `contact-form.php`.
- `docker/`: só do ambiente local (`share.php` é incluído no wp-config apenas no Docker).

## Convenções

- Prefixo `fec_` em funções, opções e metas; textos da interface em português.
- Tailwind 4: CSS sem camada do WordPress vence as utilities; os estilos globais do theme.json são removidos no front (theme.json vale só para o editor).
- Fundo dourado leva texto azul-marinho (`text-brand-blue-900`) por contraste.
- Posts ficam em `/blog/<slug>/`; categorias em `/blog/categoria/`; CPTs usam `with_front=false`.
- No Git Bash, use `MSYS_NO_PATHCONV=1` em comandos com caminhos `/var/www/...` ou `/blog/...`.
- Credenciais de teste só do `.env.example`; segredos de produção ficam no `wp-config.php` da hospedagem, nunca no banco ou no git.
