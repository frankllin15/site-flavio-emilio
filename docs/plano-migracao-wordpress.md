# Plano de migração: Next.js → WordPress

Site de Flávio Emílio Cavalcanti · plano elaborado em 07/11/2026

## 1. Diagnóstico do projeto atual

| Aspecto | Situação hoje | Impacto na migração |
|---|---|---|
| Stack | Next.js 16 + React 19 + Tailwind 4, sem banco | Todo o front será reescrito em PHP (tema); o design e o CSS são reaproveitáveis |
| Conteúdo | Arrays TypeScript em `lib/data/` + textos fixos dentro dos componentes (Hero, About, Proficient, Contact) | Precisa virar CPTs/campos editáveis; os textos fixos são o maior risco de ficarem "presos" no código |
| Volume | 5 serviços, 3 palestras, 5 eventos, 2 livros, ~21 clientes, 4 depoimentos, 3 posts | Pequeno: dá para importar por script (e refazer a importação quantas vezes for preciso) |
| Rotas | `/`, `/servicos/[id]`, `/palestras/[id]`, `/eventos` | Manter os mesmos slugs nos CPTs (sem precisar de redirects) |
| Blog | Só listagem com 3 posts de exemplo e **sem página de detalhe**; tipos prontos para o DatoCMS | Passa a usar os posts nativos do WP, o que resolve essa lacuna |
| Formulário | `/api/contact` com Zod + Resend | Trocar por plugin de formulário + SMTP |
| Interações JS | Header fixo ao rolar, menu mobile, carrossel Embla, botão "voltar ao topo" | Embla funciona sem React: reaproveitar em JS puro |
| Fontes | Google Fonts via `next/font` (Geist, Playfair, Montserrat) | Hospedar no próprio servidor (performance e LGPD) |

**Pendências a decidir antes de portar** (não vale a pena migrar placeholders):
- Depoimentos (Ana Paula, Carlos Eduardo, Mariana, Roberto) e avatares parecem fictícios: confirmar quais são reais.
- Posts do blog são de exemplo e não têm o texto completo.
- `public/images/og-image.jpg` é referenciado no metadata, mas não existe.
- Os números do About ("25+", "100+"…) estão fixos no código: vão virar campos.
- `Welcome` e `FloatingWhatsAppButton` estão comentados: decidir se entram.
- Bug pequeno: o CTA "Entrar em Contato" do header abre `/#contato` em nova aba (`target="_blank"`).

## 2. Arquitetura recomendada

**Tema próprio (clássico, em PHP) + Tailwind 4 compilado + campos personalizados.** Nada de page builder.

Motivos:
- O design já existe em Tailwind: as classes dos componentes `.tsx` passam quase literalmente para os templates PHP, então o visual fica idêntico.
- Elementor/Divi deixariam o site mais pesado, criariam dependência de plugin pago e levariam a refazer o layout do zero.
- Um tema de blocos (FSE) com Tailwind é possível, mas dá mais trabalho, e o cliente só precisa editar **conteúdo**, não layout.

### Modelo de conteúdo

| Conteúdo atual | No WordPress | Campos (ACF / SCF) |
|---|---|---|
| `services.ts` | CPT `servico` (slug `servicos`) | logo (imagem), cor do placeholder (select), subtítulo, tópicos (repeater), pontos de aprendizado (repeater título+descrição), rodapé, ordem |
| `lectures.ts` | CPT `palestra` (slug `palestras`) | logo, introdução, subtítulo, tópicos (repeater), conclusão |
| `events.ts` | CPT `evento` (arquivo em `/eventos`) | local, data (date picker, substitui `date` e `sortDate`), imagem destacada |
| `books.ts` | CPT `livro` | subtítulo, ano, link de compra, destaques (repeater), capa |
| `socialProof.ts` → customers | CPT `cliente` (sem página pública) | logo, URL do site, ordem |
| `socialProof.ts` → testimonials | CPT `depoimento` (sem página pública) | citação, cargo, empresa, avatar |
| `blogPosts.ts` | Posts nativos + categorias nativas | tempo de leitura calculado em PHP; "destaque" via campo booleano ou post fixo |
| Hero, About, Proficient, Contato, redes sociais | **Página de Opções** "Conteúdo da Home" | títulos, textos, imagens do hero (desktop/mobile), estatísticas (repeater), WhatsApp, e‑mail, LinkedIn, Instagram |
| Menu do header | Menu nativo do WP (`wp_nav_menu`) | — |

**Plugin de campos:** recomendo o **Secure Custom Fields (SCF)**, o fork gratuito do ACF mantido pelo WordPress.org, que já inclui repeater e páginas de opções. Se preferir o ACF Pro, o código é idêntico. Os grupos de campos são registrados em PHP no mu-plugin (`fec-core/fields.php`), versionados no Git e com chaves fixas.

**Onde fica o código:** os CPTs e campos vão num **mu-plugin** (`fec-core`), não no tema. Assim, trocar o tema no futuro não faz o conteúdo sumir.

### Plugins (lista enxuta)

| Função | Plugin |
|---|---|
| Campos personalizados | Secure Custom Fields (ou ACF Pro) |
| Formulário de contato | Contact Form 7 + Flamingo (guarda as mensagens no painel) |
| Envio de e‑mail | SMTP próprio no mu-plugin (`fec-core/mail.php`), configurado por constantes no `wp-config.php`: dispensa o WP Mail SMTP e não guarda a senha no banco (Resend ou e‑mail da hospedagem) |
| Anti‑spam | Cloudflare Turnstile (integração nativa no CF7; chaves também no `wp-config.php`) |
| SEO | Yoast SEO (sitemap, OG, schema Person), configurado pelo seed |
| Cache/otimização | LiteSpeed Cache (Hostinger usa LiteSpeed) ou o cache nativo da Locaweb |
| Backup | Backup da hospedagem + UpdraftPlus para cópia externa |
| Segurança | Limitar tentativas de login + 2FA (ex.: Wordfence Login Security) |

O formulário atual tem os campos nome, e‑mail, telefone, empresa, assunto (consultoria/palestra/livros/outros) e mensagem. Isso é recriado no CF7 com a mesma validação e o mesmo template de e‑mail.

## 3. Ambiente de desenvolvimento com Docker

Fica numa pasta `wordpress/` neste repositório (o app Next continua aqui até a virada; depois vai para a branch `legacy-nextjs`).

```
wordpress/
├── docker-compose.yml
├── .env.example                 # senhas do banco, URL local
├── package.json (scripts npm)    # up, down, setup, seed, build
├── wp-content/
│   ├── themes/flavio-emilio/
│   │   ├── style.css, functions.php
│   │   ├── front-page.php, single-servico.php, single-palestra.php,
│   │   │   archive-evento.php, single.php, home.php, 404.php
│   │   ├── template-parts/sections/  (hero, about, proficient, services…)
│   │   ├── template-parts/ui/         (button, card, section-heading, carousel)
│   │   ├── assets/src/  (main.css com @theme copiado de globals.css, main.js)
│   │   ├── assets/dist/ (gerado)
│   │   ├── assets/fonts/ (woff2 locais)
│   │   └── package.json  (tailwindcss, @tailwindcss/cli, esbuild, embla-carousel)
│   └── mu-plugins/fec-core/  (CPTs, página de opções, helpers)
└── seed/
    ├── export-data.ts   # lê lib/data/*.ts e gera seed/data.json
    └── import.php       # wp eval-file: cria posts, faz upload das imagens, preenche campos
```

`docker-compose.yml`, com os serviços:

| Serviço | Imagem | Papel |
|---|---|---|
| `db` | `mariadb:10.11` | Mesma família de banco das hospedagens |
| `wordpress` | `wordpress:6.x-php8.3-apache` | Monta `wp-content/themes` e `wp-content/mu-plugins` como volumes |
| `wpcli` | `wordpress:cli-php8.3` | Instala o WP, ativa plugins, roda o seed |
| `mailpit` | `axllent/mailpit` | Recebe os e‑mails do formulário em `localhost:8025` (sem enviar nada de verdade) |
| `adminer` | `adminer` | Inspecionar o banco (opcional) |
| `node` | `node:22-alpine` | `tailwindcss --watch` + `esbuild --watch` (ou rodar o Node direto no Windows) |

Fluxo: `npm run up` → `npm run setup` (wp core install + plugins + ativa o tema) → `npm run seed` (importa todo o conteúdo atual) → `npm run dev:css` (watch do CSS/JS). O site local fica em `http://localhost:8080`.

Atenção ao Tailwind 4: no `main.css`, declarar `@source "../../**/*.php";` para que ele encontre as classes dentro dos templates PHP.

Fixe a mesma versão de PHP do plano de hospedagem contratado (8.2 ou 8.3) para não haver surpresa no deploy.

## 4. Fases e estimativa

| # | Fase | Entregas | Esforço |
|---|---|---|---|
| 0 | Decisões | Hospedagem e plano escolhidos, pendências da seção 1 resolvidas, domínio/DNS mapeados | 0,5 dia |
| 1 | Docker + esqueleto | compose, install automatizado, tema vazio com Tailwind compilando, fontes locais | 1 dia |
| 2 | Modelo de conteúdo | mu-plugin com CPTs, campos SCF em JSON, página de opções, menus | 1 dia |
| 3 | Importação | `export-data.ts` + `import.php` idempotente (roda de novo sem duplicar), imagens para a biblioteca de mídia | 1 dia |
| 4 | Templates | Header/Footer, as 10 seções da home, single de serviço/palestra, arquivo de eventos, blog (lista + post), 404 | 3–4 dias |
| 5 | Interações | Embla em JS puro (setas, dots, teclado, swipe), menu mobile, header ao rolar, voltar ao topo | 1 dia |
| 6 | Formulário e e‑mail | CF7 + Turnstile + SMTP, testado no Mailpit e depois em staging | 0,5 dia |
| 7 | SEO e performance | Metadados atuais no Rank Math/Yoast, OG image, schema, imagens WebP, cache, Lighthouse ≥ 90 | 1 dia |
| 8 | Staging e treinamento | Deploy em subdomínio de staging, revisão com o cliente, guia curto de "como editar" | 1 dia |
| 9 | Go‑live | Deploy em produção, DNS, SSL, Search Console, backup inicial | 0,5 dia |

Total: **~10–12 dias úteis** para uma pessoa.

## 5. Hospedagem e deploy

| | Hostinger | Locaweb |
|---|---|---|
| Plano adequado | Business / Cloud Startup (tem SSH, Git e staging) | Hospedagem WordPress ou plano com SSH |
| Servidor | LiteSpeed + LiteSpeed Cache | Apache/Nginx, depende do plano |
| Deploy | Git integrado no hPanel ou GitHub Actions via SSH/rsync | SFTP/SSH via GitHub Actions |
| Staging | Nativo (1 clique) | Depende do plano |
| Observação | Geralmente melhor custo/benefício e desempenho | Suporte e faturamento nacionais |

**Regra principal: o código desce por Git, o conteúdo vive em produção.**
- Tema + mu-plugin: GitHub Actions faz build do CSS/JS e envia por rsync/SFTP só `themes/flavio-emilio` e `mu-plugins/fec-core` (sem `node_modules`).
- Primeira publicação: subir o banco local já com o seed (`wp db export` → import no servidor → `wp search-replace http://localhost:8080 https://flavioemilio.com.br`), ou usar o All-in-One WP Migration.
- **Depois do go‑live, nunca mais envie o banco do dev para produção**: o cliente vai editar o conteúdo lá. Para trazer dados reais para o ambiente local, faça o caminho inverso (produção → local).
- Plugins: atualizados pelo painel em produção, com as mesmas versões instaladas no Docker.

## 6. Riscos e cuidados

- **Classes do Tailwind sumindo** se o `@source` não cobrir os PHP: validar o CSS de produção numa página com todas as seções.
- **Segurança/XSS:** escapar toda saída (`esc_html`, `esc_url`, `wp_kses_post`). O endpoint atual interpolava os dados do formulário no HTML do e‑mail sem escapar; o CF7 já trata isso.
- **Excesso de plugins:** manter a lista da seção 2; cada plugin a mais é mais superfície de ataque e mais peso.
- **Imagens:** os logos de clientes em `.avif` e alguns `.png` grandes devem ser convertidos ou otimizados no import.
- **SEO:** se já existir algum site no domínio, mapear as URLs antigas e criar redirects 301 antes da virada de DNS.
- **E‑mail:** configurar SPF/DKIM do domínio para os contatos do formulário não caírem no spam.

## 7. Próximo passo sugerido

Fases 1–3 (Docker + modelo de conteúdo + importação). Ao final, já dá para editar todo o conteúdo atual no painel do WordPress local, antes de escrever uma linha de template.
