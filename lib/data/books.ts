import type { Book } from '@/types';

export const books: Book[] = [
  {
    id: "book-1",
    title: "Perguntas E Respostas Para A Gestão De Sua Carreira",
    subtitle: "Dúvidas e orientações para uma vida profissional bem sucedida",
    description: "Este livro oferece respostas práticas para as perguntas mais comuns sobre gestão de carreira, ajudando profissionais a tomar decisões informadas e estratégicas em sua trajetória profissional.",
    coverImage: "/images/books/perguntas-e-respostas-para-a-gestao-de-sua-carreira.jpg",
    publishYear: 2023,
    purchaseUrl: "#",
    amazonUrl: "#",
    highlights: [
      "Autoavaliação e definição de objetivos",
      "Desenvolvimento de habilidades e competências",
      "Networking e construção de relacionamentos profissionais",
      "Planejamento de carreira a longo prazo"
    ]
  },
  {
    id: "book-2",
    title: "Drops De Carreira",
    subtitle: "Dicas valiosas para você ir mais longe na vida profissional",
    description: "Uma coletânea de insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional, este livro é um guia essencial para quem busca crescimento e sucesso na carreira.",
    coverImage: "/images/books/DROPS_DE_CARREIRA_1410897238B.webp",
    publishYear: 2022,
    purchaseUrl: "#",
    amazonUrl: "#",
    highlights: [
      "Liderança eficaz em ambientes dinâmicos",
      "Estratégias para motivar e engajar equipes",
      "Técnicas de comunicação assertiva",
      "Gestão de mudanças organizacionais"
    ]
  },
];
