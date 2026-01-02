import type { BlogPost } from '@/types';

export const blogPosts: BlogPost[] = [
  {
    id: "1",
    slug: "lideranca-humanizada-futuro-gestao",
    title: "Liderança Humanizada: O Futuro da Gestão de Pessoas",
    excerpt: "Descubra como a liderança humanizada está transformando organizações ao redor do mundo, priorizando o desenvolvimento humano sem perder de vista os resultados.",
    coverImage: "/images/blog/lideranca-humanizada.jpg",
    author: {
      name: "Flávio Emílio Cavalcanti",
      avatar: "/images/about/profile.jpg"
    },
    publishedAt: "2024-01-15",
    category: "Liderança",
    readTime: 5,
    featured: true
  },
  {
    id: "2",
    slug: "5-pilares-clima-organizacional-saudavel",
    title: "Os 5 Pilares de um Clima Organizacional Saudável",
    excerpt: "Entenda os elementos fundamentais que constroem um ambiente de trabalho produtivo, engajador e que promove o bem-estar dos colaboradores.",
    coverImage: "/images/blog/cinco-pilares.webp",
    author: {
      name: "Flávio Emílio Cavalcanti",
      avatar: "/images/about/profile.jpg"
    },
    publishedAt: "2024-01-08",
    category: "Clima Organizacional",
    readTime: 6,
    featured: true
  },
  {
    id: "3",
    slug: "planejamento-carreira-era-digital",
    title: "Planejamento de Carreira na Era Digital",
    excerpt: "Como profissionais podem navegar pelas rápidas transformações do mercado de trabalho e construir carreiras resilientes e adaptáveis.",
    coverImage: "/images/blog/planejamento-de-carreira.jpg",
    author: {
      name: "Flávio Emílio Cavalcanti",
      avatar: "/images/about/profile.jpg"
    },
    publishedAt: "2024-01-01",
    category: "Carreira",
    readTime: 7,
    featured: true
  },
  {
    id: "4",
    slug: "feedback-ferramenta-desenvolvimento",
    title: "Feedback como Ferramenta de Desenvolvimento",
    excerpt: "Aprenda técnicas práticas para dar e receber feedback de forma construtiva, transformando conversas difíceis em oportunidades de crescimento.",
    coverImage: "/images/blog/post-4.jpg",
    author: {
      name: "Flávio Emílio Cavalcanti",
      avatar: "/images/about/profile.jpg"
    },
    publishedAt: "2023-12-20",
    category: "Desenvolvimento Profissional",
    readTime: 5,
    featured: false
  },
];
