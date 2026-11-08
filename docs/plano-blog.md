# Plano: blog com identidade própria, compartilhamento e aviso de novos posts

Site de Flávio Emílio Cavalcanti · 07/11/2026 · complementa `plano-migracao-wordpress.md`

## 1. Ponto de partida

O que o tema WordPress já tem, só com o mínimo para não quebrar:

| Existe | Falta |
|---|---|
| Lista em `/blog` (cards iguais à prévia da home), paginação, arquivo por categoria | Destaque editorial, filtro por categoria, busca |
| Página do post: categoria, tempo de leitura, autor, data, imagem e texto (`prose`) | Tipografia de leitura com a cara da marca, índice, caixa do autor, posts relacionados, chamada final |
| Prévia na home: 3 posts mais recentes | Uso do campo "Destaque na home" (já existe, mas não é usado) |
| Yoast: título, descrição, Open Graph com a imagem padrão do site | Imagem de compartilhamento própria de cada post, schema de artigo revisado |
| Nada | Botões de compartilhar, newsletter, aviso de novos posts, página de privacidade |

O conteúdo também não existe: os 4 posts importados são exemplos sem texto, em rascunho. **O blog só faz sentido no ar com pelo menos 3 a 5 artigos reais prontos.** Isso é o caminho crítico, mais do que o código.

O perfil do cliente (`customer-profile.md`) define o blog como **"Drops de Carreira"**: textos práticos e curtos, com o tom do "mentor". Prioridade: legibilidade em textos longos e muito espaço em branco.

## 2. Decisões para o cliente

| # | Decisão | Recomendação |
|---|---|---|
| 1 | **Nome do blog.** "Drops de Carreira" é o nome do livro e o título da seção de livros na home | ✅ Decidido: blog = "Drops de Carreira"; seção de livros = "Livros" |
| 2 | **Aviso de novos posts** | Newsletter por e-mail (seção 5) + "kit de divulgação" para postar no LinkedIn e no WhatsApp manualmente |
| 3 | **Comentários** | Manter fechados (exigem moderação e atraem spam). A conversa acontece no LinkedIn, onde o post for divulgado |
| 4 | **Frequência** | Definir antes de lançar a newsletter (ex.: 2 posts/mês). Newsletter sem ritmo perde inscritos |
| 5 | **Medição de audiência** | Opcional: analytics sem cookies (Plausible, pago, ou Matomo, auto-hospedado). Sem isso, não é preciso banner de cookies |

## 3. Identidade visual (mesmos tokens, fontes e componentes do site)

Nada de tema de blog genérico: tudo usa os tokens de `main.css` (azul `brand-blue`, dourado `accent-gold`, Montserrat nos títulos, Geist no texto).

### Lista (`/blog`)
- **Topo editorial:** o post mais recente (ou o marcado como "Destaque") ocupa a largura toda, com imagem grande, categoria em dourado e resumo. Os demais seguem no grid de cards que já existe.
- **Categorias em chips** (estilo dos badges do `SectionHeading`), filtrando por `/blog/categoria/...`, sem recarregar o layout.
- **Busca** simples (busca nativa do WP, restrita a posts).
- Faixa de **inscrição na newsletter** no meio da lista, depois do 6º card.

### Post
- **Cabeçalho:** categoria, título grande (Montserrat), linha fina dourada (o mesmo "accent line" do `SectionHeading`), autor com foto real (não as iniciais "FE"), data e tempo de leitura.
- **Tipografia de leitura:** `prose` personalizado com coluna de ~68 caracteres, entrelinha 1.75, links azuis sublinhados, citações com borda dourada e fundo `accent-gold-50` (o mesmo do bloco de fechamento das páginas de serviço), listas com o ✓ dourado das páginas de serviço.
- **Barra de progresso de leitura** fina e dourada no topo, só nos posts.
- **Índice automático** (para posts com 3+ subtítulos H2): na lateral no desktop e recolhível no celular, gerado em PHP a partir dos H2, sem plugin.
- **Fim do post:** botões de compartilhar, caixa "Sobre o autor" (foto, mini bio, LinkedIn), chamada para a newsletter, 3 posts relacionados (mesma categoria) e um card de conversão ligado ao tema ("Quer levar esse tema para sua empresa? Conheça a palestra X / fale comigo").

### Para quem escreve (editor de blocos)
O Flávio escreve no editor do WordPress. Para o resultado ficar sempre na identidade, sem depender de "saber design":
- `theme.json` mínimo: só as cores e os tamanhos de fonte da marca na paleta do editor, e o editor mostra a mesma fonte do site.
- **Estilos e padrões de bloco prontos:** "Dica prática" (caixa dourada), "Destaque" (citação grande), "Resumo em tópicos" (lista com ✓), "Chamada para palestra/serviço" (escolhe um serviço/palestra e gera o card).
- Blocos que quebram o visual (colunas com cores livres, fontes customizadas) ficam desabilitados.

## 4. Compartilhamento

Botões feitos à mão, **sem scripts de terceiros** (os widgets oficiais das redes pesam centenas de KB e rastreiam o leitor, o que exigiria banner de cookies/LGPD).

| Canal | Como |
|---|---|
| **Celular** | Um botão "Compartilhar" que abre o menu nativo do aparelho (Web Share API): WhatsApp, LinkedIn, Instagram etc. de uma vez |
| LinkedIn | `linkedin.com/sharing/share-offsite/?url=` (principal canal B2B do público) |
| WhatsApp | `wa.me/?text=` com título + link |
| E-mail | `mailto:` com assunto e link (muito usado para repassar a RH/gestores) |
| Facebook e X | Links de compartilhamento simples (secundários, atrás de "mais") |
| Copiar link | Botão com aviso "Link copiado" |

Posição: logo abaixo do título (discreto) e no fim do post (completo). No desktop, opcionalmente, uma barra lateral fixa.

**Imagem de compartilhamento por post:** como o `og-image.jpg` do site, gerada automaticamente ao publicar (Imagick no servidor): fundo azul, título do post em Montserrat, nome do Flávio e foto pequena. Se o post tiver imagem destacada, a imagem gerada a usa como fundo escurecido. Assim, todo link compartilhado no LinkedIn/WhatsApp chega com um cartão bonito e legível, mesmo que o autor não crie arte. O Yoast continua permitindo trocar manualmente.

**Citação compartilhável (opcional):** o bloco "Destaque" ganha um botão "compartilhar esta frase" (LinkedIn/WhatsApp com a frase + link).

## 5. Aviso de novos posts

Três canais, do mais ao menos automático:

### 5.1 Newsletter por e-mail (principal)
Inscrição em 3 lugares: fim de cada post, meio da lista do blog e rodapé do site. Pede só o e-mail (e o nome, opcional).

**Recomendação: MailPoet** (plugin do WordPress, da mesma empresa do WooCommerce):
- Inscritos ficam no próprio site (LGPD: os dados são do Flávio, não de uma plataforma externa).
- **E-mail automático de "novo post"**: ao publicar, os inscritos recebem o resumo, a imagem e o link, com um modelo nas cores da marca. Pode ser imediato ou um resumo semanal/mensal.
- Confirmação dupla de inscrição (double opt-in), link de descadastro e lista de consentimento: o que a LGPD pede.
- Envio pelo serviço do próprio MailPoet (plano gratuito para listas pequenas; confirmar o limite atual na contratação) ou pelo SMTP já configurado (`FEC_SMTP_*`), atenção ao limite diário de envio da hospedagem.

Alternativas avaliadas:

| Opção | Prós | Contras |
|---|---|---|
| **MailPoet** (recomendado) | Tudo no painel do WP, automação de novo post pronta, modelos visuais | Plugin pesado no painel (não afeta o site público); plano pago se a lista crescer |
| Brevo (ex-Sendinblue) ou Mailchimp + formulário | Boa entregabilidade, CRM | Dados fora do site; automação de "novo post" via RSS é limitada ou paga nos planos gratuitos |
| Código próprio + Resend (já usada no Next) | Leve, controle total | Mais desenvolvimento: confirmação, descadastro, filas, modelos. Só vale com lista muito pequena |

### 5.2 Kit de divulgação (LinkedIn e WhatsApp)
LinkedIn e o Canal do WhatsApp não permitem postagem automática confiável sem APIs pagas/restritas. Em vez de automatizar, facilitar ao máximo:
- Caixa **"Divulgar este post"** no editor, visível depois de publicar: texto pronto (título + 2 frases do resumo + link com UTM) e botões que abrem o LinkedIn e o WhatsApp já preenchidos. Divulgar vira um processo de 2 cliques.
- Link para o **Canal do WhatsApp** do Flávio (se ele criar um) no fim dos posts e no rodapé: "Receba os Drops no WhatsApp".
- Opcional: Jetpack Social para postar no LinkedIn automaticamente (plano gratuito com limite mensal de compartilhamentos; exige conta WordPress.com).

### 5.3 RSS
Já existe (`/feed/`). Basta um link "RSS" discreto no rodapé do blog para quem usa leitores de feed. Custo zero.

Notificação push no navegador **não é recomendada**: é intrusiva para o público executivo e exige serviço externo.

## 6. SEO do blog

- Schema `Article`/`BlogPosting` com autor = Flávio (Person já configurado), data de publicação e de atualização.
- Breadcrumbs (Início › Blog › Categoria › Post) visíveis e em schema.
- Categorias com texto de introdução próprio (evita página de categoria "vazia" para o Google).
- Sitemap de posts e categorias (Yoast) entra quando houver posts publicados.
- Links internos: cada post aponta para pelo menos um serviço/palestra relacionado (o bloco "Chamada para palestra/serviço" já ajuda).

## 7. Privacidade (LGPD)

- **Página de Política de Privacidade** (hoje não existe e passa a ser obrigatória com newsletter e formulário de contato): o que é coletado, para quê, por quanto tempo, como pedir exclusão.
- Checkbox de consentimento na inscrição, com link para a política.
- Sem pixels de rede social e sem scripts de compartilhamento de terceiros → sem banner de cookies (enquanto não houver analytics com cookies).

## 8. Fases e estimativa

| # | Fase | Entregas | Esforço |
|---|---|---|---|
| B0 | Decisões e conteúdo | Respostas da seção 2; 3–5 posts reais escritos; foto do autor; categorias definidas | Cliente |
| B1 ✅ | Leitura | Template do post redesenhado: cabeçalho, tipografia `prose` da marca, barra de progresso, índice, caixa do autor, relacionados, card de conversão | 2 dias |
| B2 ✅ | Lista | Topo editorial com destaque, chips de categoria, busca, faixa da newsletter, páginas de categoria com introdução | 1–1,5 dia |
| B3 ✅ | Editor | `theme.json` mínimo, estilos e padrões de bloco, bloqueio de blocos fora da identidade | 1 dia |
| B4 ✅ | Compartilhamento | Botões (Web Share + links), copiar link, imagem de compartilhamento gerada por post | 1–1,5 dia |
| B5 ✅ | Newsletter | MailPoet configurado: formulário na identidade, double opt-in, modelo de e-mail de novo post, testes no Mailpit | 1 dia |
| B6 | Divulgação | Caixa "Divulgar este post" no editor, links do Canal do WhatsApp e RSS | 0,5 dia |
| B7 | Privacidade e SEO | Política de privacidade (texto-base para revisão do cliente), consentimento, schema e breadcrumbs, revisão Lighthouse | 0,5–1 dia |

**Andamento:** B1–B5 implementadas e testadas localmente (07/11/2026). Faltam B6 (kit de divulgação) e B7 (privacidade: a página de Política de Privacidade passa a ser necessária com a newsletter).

**Total: ~7–8 dias úteis**, mais o tempo do cliente para escrever os primeiros posts.

**Encaixe no plano de migração:** as fases B1–B4 e B7 podem entrar **antes do staging** (fase 8), para o cliente revisar o site completo de uma vez. A newsletter (B5–B6) pode ir junto ou logo depois do go-live, quando houver posts reais e a frequência estiver definida. O site pode ir ao ar com o blog escondido do menu, se os textos não estiverem prontos.

## 9. Como validar

- Post de teste longo (com H2, citações, listas, imagens) revisado no celular e no desktop.
- Compartilhamento conferido nos validadores oficiais (LinkedIn Post Inspector, depurador de compartilhamento do Facebook) e enviando o link no WhatsApp.
- Inscrição → confirmação → e-mail de novo post → descadastro, com tudo chegando no Mailpit local e, em staging, numa caixa real.
- Lighthouse do post e da lista mantendo desempenho ≥ 95 e acessibilidade 100.
