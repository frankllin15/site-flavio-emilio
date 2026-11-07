/**
 * Exporta o conteúdo do app Next.js (lib/data/*.ts + textos fixos dos componentes) para seed/data.json,
 * que depois é lido por seed/import.php dentro do container WP-CLI.
 *
 * Uso: npm run seed:export   (Node 22.18+ roda TypeScript direto, sem build)
 */
import { writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

import { services } from '../../lib/data/services.ts';
import { lectures } from '../../lib/data/lectures.ts';
import { events } from '../../lib/data/events.ts';
import { books } from '../../lib/data/books.ts';
import { customers, testimonials } from '../../lib/data/socialProof.ts';
import { blogPosts } from '../../lib/data/blogPosts.ts';

const here = dirname(fileURLToPath(import.meta.url));

/** Caminhos "/images/x.png" do Next viram relativos a public/images (montado em /seed-images). */
const img = (p?: string) => (p ? p.replace(/^\/images\//, '') : null);

/*
 * Textos que hoje estão escritos direto nos componentes. Copiados de:
 * Hero.tsx, About.tsx, Proficient.tsx e Contact.tsx.
 */
const options = {
  hero_titulo: 'Transformando Potencial Humano em',
  hero_titulo_destaque: 'Resultados Organizacionais',
  hero_subtitulo:
    'Consultoria organizacional, palestras e treinamentos in-company com foco em liderança estratégica e gestão de carreira consciente.',
  hero_imagem_desktop: img('/images/hero.png'),
  hero_imagem_mobile: img('/images/hero-mobile.png'),
  hero_cta1_texto: 'Contrate para sua Empresa',
  hero_cta1_link: '/#contato',
  hero_cta2_texto: 'Conheça meus Livros',
  hero_cta2_link: '/#livros',

  sobre_titulo: 'Sobre Flávio Emílio',
  sobre_imagem: img('/images/hero.jpg'),
  sobre_texto: [
    'Sou <strong>Flávio Emílio Monteiro Cavalcanti</strong>, <strong>administrador</strong> por formação, educador por vocação e apaixonado por desenvolver pessoas e organizações. Com mais de 25 anos de atuação como consultor e educador corporativo, venho ajudando empresas privadas e organizações públicas a enfrentarem desafios estratégicos e a alcançarem resultados consistentes através do desenvolvimento de suas lideranças, da melhoria de processos e do fortalecimento da cultura organizacional.',
    'Sou presidente reeleito do <strong>Conselho Regional de Administração do RN (CRA-RN)</strong>, e tenho a satisfação de contribuir para o desenvolvimento da profissão no nosso estado. Sou <strong>mestre em Administração de Recursos Humanos pela UFRN</strong> e desde cedo trilhei uma carreira que une a academia e o mercado. Já lecionei em diversas instituições de ensino superior e coordenei programas de pós-graduação, sempre com foco na aplicação prática do conhecimento e visão de mercado.',
    'Nos últimos anos, conduzi projetos de capacitação e consultoria em empresas de diversos setores — de hospitais a resorts, de mineradoras a redes de varejo em vários estados brasileiros. Atendo organizações que acreditam que investir nas pessoas é o caminho mais sustentável para crescer com consistência e propósito.',
    'Se você busca um parceiro comprometido com resultados, com linguagem acessível e profunda bagagem técnica, será um prazer contribuir com sua equipe.',
  ]
    .map((p) => `<p>${p}</p>`)
    .join('\n'),
  sobre_numeros: [
    { numero: '25+', rotulo: 'Anos de Experiência' },
    { numero: '100+', rotulo: 'Empresas Atendidas' },
    { numero: '2', rotulo: 'Livros Publicados' },
  ],

  proficient_logo: img('/images/logos/proficient.png'),
  proficient_missao:
    'Compartilhar competências de gestão para o desenvolvimento de pessoas e negócios, promovendo resultados sustentáveis e transformações positivas no ambiente organizacional',
  proficient_texto:
    'Ao longo da minha trajetória, desenvolvi projetos em empresas de diversos portes e segmentos, sempre com foco em transformar estratégias em resultados e lideranças em agentes de mudança. Escrevi livros, criei programas de desenvolvimento e idealizei a Proficient com o propósito claro: compartilhar competências de gestão que geram impacto real na vida das pessoas e no desempenho dos negócios.',
  proficient_valores_titulo: 'Nossos Valores',
  proficient_valores: [
    { icone: 'shield', termo: 'Integridade', descricao: 'como base de todas as relações' },
    { icone: 'zap', termo: 'Praticidade', descricao: 'aliada à excelência técnica' },
    { icone: 'heart', termo: 'Respeito', descricao: 'às pessoas, suas histórias e contextos' },
    { icone: 'target', termo: 'Compromisso', descricao: 'com resultados reais' },
    { icone: 'trending-up', termo: 'Aprendizado contínuo', descricao: 'como caminho de evolução' },
  ],

  contato_titulo: 'Vamos conversar?',
  contato_texto:
    'Entre em contato via WhatsApp ou email. Estou pronto para ajudar você e sua organização a alcançar novos patamares.',
  whatsapp_numero: '5584991056687',
  whatsapp_mensagem: 'Olá, gostaria de saber mais sobre seus serviços',
  email: 'contato@proficient.com.br',
  telefone: '(84) 99105-6687',
  localizacao: 'Natal/RN, Brasil',
  linkedin: 'https://www.linkedin.com/in/flavioemilio/',
  instagram: 'https://instagram.com/flavio_emilio',

  // O formulário (components/client/ContactForm.tsx) foi retirado da home no commit c0ffab0:
  // começa desligado. O ID do formulário é preenchido pelo import.php.
  contato_mostrar_formulario: false,

  // components/layout/Footer.tsx
  rodape_texto:
    'Consultor Organizacional, Palestrante e Especialista em Gestão de Carreiras. Transformando potencial humano em resultados organizacionais.',
};

/** Menus: header (components/layout/Header.tsx) e rodapé (components/layout/Footer.tsx). */
const menus = {
  primary: {
    name: 'Principal',
    items: [
      { label: 'Sobre', href: '/#sobre' },
      { label: 'Proficient', href: '/#proficient' },
      { label: 'Serviços', href: '/#servicos' },
      { label: 'Palestras', href: '/#palestras' },
      { label: 'Eventos', href: '/#eventos' },
      { label: 'Blog', href: '/#blog' },
      { label: 'Livros', href: '/#livros' },
    ],
  },
  footer: {
    name: 'Rodapé',
    items: [
      { label: 'Sobre', href: '/#sobre' },
      { label: 'Serviços', href: '/#servicos' },
      { label: 'Palestras', href: '/#palestras' },
      { label: 'Eventos', href: '/#eventos' },
      { label: 'Blog', href: '/#blog' },
      { label: 'Livros', href: '/#livros' },
      { label: 'Contato', href: '/#contato' },
    ],
  },
};

const ymd = (d: Date) => d.toISOString().slice(0, 10).replace(/-/g, '');

const data = {
  generatedAt: new Date().toISOString(),
  options,
  menus,

  servico: services.map((s, i) => ({
    slug: s.id,
    title: s.title ?? s.id,
    content: s.description,
    order: i,
    fields: {
      logo: img(s.logo),
      cor: s.color ?? 'blue',
      subtitulo: s.subtitle ?? '',
      topicos: (s.topics ?? []).map((texto) => ({ texto })),
      pontos_aprendizado: (s.learningPoints ?? []).map((p) => ({ titulo: p.title, descricao: p.description })),
      rodape: s.footer ?? '',
    },
  })),

  palestra: lectures.map((l, i) => ({
    slug: l.id,
    title: l.title,
    content: l.introduction,
    order: i,
    fields: {
      logo: img(l.logo),
      subtitulo: l.subtitle ?? '',
      topicos: (l.topics ?? []).map((texto) => ({ texto })),
      conclusao: l.conclusion,
    },
  })),

  // O id numérico do Next ("1", "2"...) não serve de slug; o slug vem do título.
  evento: events.map((e) => ({
    key: `evento-${e.id}`,
    title: e.title,
    content: e.description,
    thumbnail: img(e.image),
    fields: { local: e.location, data: ymd(e.sortDate) },
  })),

  livro: books.map((b, i) => ({
    key: b.id,
    title: b.title,
    content: b.description,
    order: i,
    thumbnail: img(b.coverImage),
    fields: {
      subtitulo: b.subtitle ?? '',
      ano: b.publishYear,
      link_compra: b.purchaseUrl ?? '',
      destaques: b.highlights.map((texto) => ({ texto })),
    },
  })),

  cliente: customers.map((c, i) => ({
    key: `cliente-${c.id}`,
    title: c.name,
    order: i,
    fields: { logo: img(c.logo), site: c.siteUrl ?? '' },
  })),

  // Depoimentos parecem fictícios (nomes/empresas genéricos): entram como RASCUNHO para revisão.
  depoimento: testimonials.map((t, i) => ({
    key: `depoimento-${t.id}`,
    title: t.author,
    order: i,
    status: 'draft',
    fields: { citacao: t.quote, cargo: t.role, empresa: t.company, avatar: img(t.avatar) },
  })),

  // Posts de exemplo, sem texto completo: entram como RASCUNHO.
  post: blogPosts.map((p) => ({
    slug: p.slug,
    title: p.title,
    excerpt: p.excerpt,
    content: p.content ?? `<p>${p.excerpt}</p>`,
    date: `${p.publishedAt} 09:00:00`,
    category: p.category,
    thumbnail: img(p.coverImage),
    status: 'draft',
    fields: { destaque: !!p.featured },
  })),
};

const out = join(here, 'data.json');
writeFileSync(out, JSON.stringify(data, null, 2));

const counts = Object.entries(data)
  .filter(([, v]) => Array.isArray(v))
  .map(([k, v]) => `${k}: ${(v as unknown[]).length}`)
  .join(', ');
console.log(`seed/data.json gerado (${counts})`);
