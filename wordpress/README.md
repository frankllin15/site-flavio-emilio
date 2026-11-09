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
npm run setup    # instala WP em pt-BR, plugins (SCF, CF7, Flamingo, Yoast) e ativa o tema
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
docker-compose.yml          db (MariaDB 10.11), wordpress (PHP 8.3), wpcli, og-image, mailpit, adminer
bin/setup.sh                instalação/configuração via WP-CLI (pode rodar de novo)
docker/                     arquivos só do ambiente local (limites de upload do PHP)
seed/export-data.ts         lib/data/*.ts + textos fixos dos componentes → seed/data.json
seed/import.php             data.json → posts, campos, imagens, menus, páginas Início/Blog, formulário
seed/contact-form.php       formulário de contato (CF7) e e-mail
seed/seo.php                configuração do Yoast (metadados do Next, schema, imagem de compartilhamento)
seed/generated/             og-image.jpg, gerada por `npm run og` (bin/og-image.php)
wp-content/mu-plugins/      fec-core: tipos de conteúdo, campos (SCF), ajustes do painel, SMTP/Turnstile, WebP
wp-content/themes/flavio-emilio/
  front-page.php            home: monta as seções de template-parts/sections/
  single-servico.php        /servicos/<slug>
  single-palestra.php       /palestras/<slug>
  archive-evento.php        /eventos
  home.php, single.php      blog (listagem e post)
  template-parts/           seções da home, card de post, bloco de tópicos
  inc/template-tags.php     helpers equivalentes aos componentes de UI do Next (botão, título de seção, carrossel)
  inc/performance.php       lazy load, carregamento condicional do CF7, limpeza do <head>
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

## Blog

Plano completo: [../docs/plano-blog.md](../docs/plano-blog.md). Fases B1–B4 implementadas:

- **Lista** (`home.php`, também usado em categorias e busca): post em destaque no topo (o "Post em destaque" mais recente), chips de categoria, busca só em posts, 9 por página.
- **Post** (`single.php`): breadcrumb, cabeçalho com autor (foto em Usuários → Perfil → "Foto do autor"), tipografia de leitura (`.fec-prose`), barra de progresso, índice automático a partir de 3 subtítulos H2, compartilhamento, "Sobre o autor", chamada para serviço/palestra (campo "Chamada no fim do post") e "Continue lendo".
- **Editor**: `theme.json` só com as cores e fontes da marca, sem cores livres. Estilos de bloco "Destaque" (citação) e "Dica prática" (grupo), padrões em Inserir → Padrões → Flávio Emílio, blocos limitados ao essencial e subtítulos H2–H4. O visual dos blocos fica em `assets/src/blocks.css` (site e editor).
- **Compartilhar** (`template-parts/share.php`): LinkedIn, WhatsApp, e-mail, Facebook, X e copiar link, sem scripts de terceiros. No celular, o botão "Compartilhar" abre o menu nativo (só aparece em HTTPS, quando o aparelho suporta).
- **Imagem de compartilhamento por post** (`inc/og-post.php`): gerada ao publicar ou salvar, com título, categoria, foto e nome do autor sobre a imagem destacada escurecida. O Yoast passa a usá-la, a não ser que outra imagem seja escolhida na aba "Social". Precisa do Imagick com JPEG no servidor. Para regenerar todas depois de mudar o layout, aumente `FEC_OG_VERSION`.
- Nome do blog: **Drops de Carreira** (decisão do cliente), editável em **Conteúdo do Site → Blog**. A página continua em `/blog/`, e o item de menu continua "Blog" para caber no header. A seção de livros da home passou a se chamar "Livros".
- Home: seção com os 3 posts mais recentes. Sem posts publicados, mostra um aviso "em breve" com link para o LinkedIn (`template-parts/blog-empty.php`), também usado na página do blog.
- O `theme.json` vale só para o editor. No site, os estilos globais gerados por ele são desligados (`inc/editor.php`), porque mudariam a cor de todos os links.
- **Cuidado com CSS do WordPress sem camada:** o Tailwind 4 coloca as utilidades em `@layer`, e qualquer CSS sem camada vence elas, mesmo com menos especificidade. O WordPress injeta, por exemplo, `:where(figure) { margin: 0 0 1em }`, que anulava o `mx-auto` da imagem do post. Em `<figure>` de template, use a classe `fec-figure` (margem zerada) e centralize num elemento em volta.

## Newsletter (Drops de Carreira)

Fase B5 de [../docs/plano-blog.md](../docs/plano-blog.md). Plugin: **MailPoet**.

- **Inscrição:** formulário próprio na identidade do site (`template-parts/newsletter.php`), no fim de cada post, na lista do blog (depois do 6º card), no aviso "em breve" e no rodapé. Ele envia para `POST /wp-json/fec/v1/newsletter` (`fec-core/newsletter.php`), que cadastra no MailPoet com **confirmação dupla**. Tem campo invisível contra robôs, tempo mínimo de preenchimento, limite de 5 tentativas por IP a cada 10 min e caixa de consentimento obrigatória. A resposta é a mesma para e-mail novo ou já cadastrado.
- **E-mail automático de novo post:** criado pelo seed (`seed/newsletter.php`) com as cores do site. Sai **imediatamente** a cada post publicado, com um post por e-mail, e só para inscritos confirmados. Para editar: MailPoet → E-mails → Notificações de posts.
- **Configuração:** lista "Drops de Carreira", remetente e "responder para" do Flávio, aviso de nova inscrição para o e-mail de contato, rastreamento sem cookies (`partial`) e assistente do MailPoet desligado.
- **Envio:** o MailPoet não usa o `phpmailer_init` do WordPress. O `fec-core` copia o SMTP do site (`FEC_SMTP_*`) para ele, a não ser que o "MailPoet Sending Service" seja escolhido no painel. Esse serviço é a alternativa recomendada se a lista crescer, porque o limite diário de envio da hospedagem costuma ser baixo.
- **Staging não envia newsletter:** o MailPoet bloqueia os envios fora de `WP_ENVIRONMENT_TYPE=production`. No Docker, `FEC_DEV_ALLOW_NEWSLETTER=1` libera, porque tudo cai no Mailpit. **Em produção, confira que `WP_ENVIRONMENT_TYPE` é `production` (o padrão do WordPress quando a constante não existe).**
- **WP-Cron no Docker:** o Apache do container também ouve na porta 8080 (`docker/apache-loopback.conf`). Sem isso, o WordPress não conseguia chamar a si mesmo em `localhost:8080`, e a fila do MailPoet e os posts agendados não rodavam. Para processar a fila na hora: `npm run wp -- action-scheduler run`.
- **Páginas do MailPoet** (confirmação, gerenciar inscrição, descadastro): template `single-mailpoet_page.php`, fora do Google.

## Divulgação dos posts (B6)

- **Caixa "Divulgar este post"** na lateral do editor (`assets/src/editor-share.js`, compilado por `npm run build:editor-js`). Depois de publicar, ela traz um texto pronto e editável (título, duas frases do resumo e link) e os botões **Publicar no LinkedIn** (abre o LinkedIn com o texto preenchido), **Enviar no WhatsApp** e **Copiar texto**.
- Cada canal recebe o link com UTM própria: `utm_source=linkedin|whatsapp`, `utm_medium=social`, `utm_campaign=drops-de-carreira`. Assim dá para ver no Analytics de onde vieram as visitas.
- O LinkedIn não permite postagem automática confiável sem APIs pagas, por isso a divulgação é manual, mas em dois cliques. Alternativa: o plugin Jetpack Social, que tem plano gratuito com limite mensal e exige conta WordPress.com.
- **RSS:** `/feed/`, com o nome "Drops de Carreira", anunciado no `<head>` e com o link "Assine o RSS" nos convites da newsletter. O feed de comentários foi removido (comentários fechados).

## Privacidade (B7)

- **Política de Privacidade** em `/politica-de-privacidade/`, criada pelo seed (`seed/privacy.php` + `seed/privacy-policy.html`) a partir do que o site realmente coleta: newsletter, formulário de contato, registros técnicos da hospedagem, ausência de cookies de rastreamento, fornecedores (hospedagem, envio de e-mail, Cloudflare Turnstile) e direitos da LGPD. **É um texto-base: o cliente precisa revisar antes de publicar, de preferência com um advogado.** Os prazos citados (2 anos para contatos, 30 dias para inscrições não confirmadas, 12 meses para inativos) são sugestões a confirmar.
- **Links:** rodapé de todas as páginas, caixa de consentimento da newsletter e nota abaixo do botão do formulário de contato.
- **MailPoet:** apaga inscrições não confirmadas após 30 dias e desativa inscritos inativos após 365 dias, como diz a política.
- **Pedidos de titulares:** **Ferramentas → Exportar dados pessoais / Apagar dados pessoais** do WordPress cobre o MailPoet. Mensagens do formulário guardadas no Flamingo (**Flamingo → Mensagens recebidas**) devem ser apagadas manualmente.

## SEO

- Yoast SEO, configurado pelo seed (`seed/seo.php`) com os metadados do app Next: título e descrição da home (`app/layout.tsx`), "Serviço | Proficient Consultoria | …", `/eventos`, robots e cartões sociais.
- Schema: o site representa a **pessoa** Flávio (usuário `flavio`), com foto e LinkedIn/Instagram como `sameAs`.
- Imagem padrão de compartilhamento: `seed/generated/og-image.jpg`, gerada por `npm run og` (foto do hero + Montserrat). O Next apontava para um `og-image.jpg` que nunca existiu. Para trocar: **SEO → Configurações → Redes sociais**.
- Sitemap: `/sitemap_index.xml`. A página individual de evento redireciona para `/eventos` e fica fora do índice. Arquivos de autor e de data estão desligados.
- Em **staging**, marque **Configurações → Leitura → Evite que mecanismos de busca indexem este site**.

## Desempenho

- Miniaturas geradas em **WebP** (`fec-core/media.php`). Os originais JPEG/PNG continuam guardados. Depois de mudar tamanhos ou formato: `npm run wp -- media regenerate --yes`.
  - Cuidado com arquivos de mesmo nome e extensões diferentes (ex.: `hero.png` e `hero.jpg`): os dois viram `hero.webp`. Pelo painel, o WordPress renomeia sozinho. O seed também renomeia (`fec_seed_unique_name`, gera `hero-2.jpg`).
- Hero em `<picture>`: o navegador baixa só a imagem do tamanho da tela (cerca de 16 KB em WebP, contra 3 MB do `hero.png`).
- Lazy load em todas as imagens fora do topo da página (`inc/performance.php`).
- CSS/JS do Contact Form 7 e o script do Turnstile só carregam quando o formulário aparece. Emojis e links extras do `<head>` foram removidos.
- Lighthouse (local, sem cache de página, celular): home 98–99; serviço, palestra, eventos e blog 99–100. Acessibilidade 100 em todas, exceto a home (96), por causa do contraste das cores da marca: texto branco no botão dourado, trechos dourados no "Sobre" e o botão verde do WhatsApp.
- Na hospedagem: ative o cache de página (**LiteSpeed Cache** na Hostinger ou o cache da Locaweb). O Docker não tem cache, então os números de produção tendem a ser melhores.
- No Docker, o Imagick do container WP-CLI não lê JPEG/PNG. O `fec-core` faz o WP-CLI usar o GD, e a imagem de compartilhamento é gerada no container do Apache.

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
