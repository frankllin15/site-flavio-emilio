import type { CompanyLogo, Testimonial } from '@/types';

export const companyLogos: CompanyLogo[] = [
  {
    id: "1",
    name: "Empresa Tech Solutions",
    logo: "/images/social-proof/company-1.svg"
  },
  {
    id: "2",
    name: "Grupo Financeiro Nacional",
    logo: "/images/social-proof/company-2.svg"
  },
  {
    id: "3",
    name: "Indústria Brasileira S.A.",
    logo: "/images/social-proof/company-3.svg"
  },
  {
    id: "4",
    name: "Retail Corporation",
    logo: "/images/social-proof/company-4.svg"
  },
  {
    id: "5",
    name: "Healthcare Partners",
    logo: "/images/social-proof/company-5.svg"
  },
  {
    id: "6",
    name: "Educação e Cultura",
    logo: "/images/social-proof/company-6.svg"
  },
];

export const testimonials: Testimonial[] = [
  {
    id: "1",
    quote: "O trabalho do Flávio Emílio transformou completamente nossa cultura organizacional. Sua abordagem humanizada trouxe resultados mensuráveis em engajamento e produtividade.",
    author: "Ana Paula Silva",
    role: "CEO",
    company: "Tech Solutions",
    avatar: "/images/testimonials/person-1.jpg"
  },
  {
    id: "2",
    quote: "As palestras do Flávio são inspiradoras e práticas. Ele consegue traduzir conceitos complexos de gestão em ações concretas que nossos líderes implementam no dia a dia.",
    author: "Carlos Eduardo Santos",
    role: "Diretor de RH",
    company: "Grupo Financeiro Nacional",
    avatar: "/images/testimonials/person-2.jpg"
  },
  {
    id: "3",
    quote: "A consultoria em clima organizacional foi um divisor de águas. Identificamos pontos cegos que nunca tínhamos percebido e construímos um plano de ação que realmente funciona.",
    author: "Mariana Costa",
    role: "Gerente de Pessoas e Cultura",
    company: "Indústria Brasileira S.A.",
    avatar: "/images/testimonials/person-3.jpg"
  }
];
