import type { Service } from '@/types';

export const services: Service[] = [
  {
    id: "gestao-pessoas",
    title: "Gestão de Pessoas",
    description: "Desenvolvimento de estratégias para otimizar o capital humano da sua organização, promovendo engajamento e resultados sustentáveis.",
    icon: "users",
    features: [
      "Diagnóstico organizacional completo",
      "Desenvolvimento de lideranças",
      "Gestão de desempenho e competências",
      "Fortalecimento da cultura organizacional"
    ],
    target: "Empresas e Organizações"
  },
  {
    id: "lideranca",
    title: "Liderança Estratégica",
    description: "Capacitação de líderes para inspirar equipes, tomar decisões assertivas e conduzir transformações organizacionais efetivas.",
    icon: "target",
    features: [
      "Programas de desenvolvimento de líderes",
      "Coaching executivo personalizado",
      "Liderança humanizada e consciente",
      "Gestão de conflitos e mudanças"
    ],
    target: "Líderes e Gestores"
  },
  {
    id: "clima-organizacional",
    title: "Clima Organizacional",
    description: "Análise e melhoria do ambiente de trabalho, identificando oportunidades para aumentar a satisfação e produtividade das equipes.",
    icon: "heart",
    features: [
      "Pesquisa de clima organizacional",
      "Plano de ação baseado em dados",
      "Programas de bem-estar corporativo",
      "Acompanhamento e mensuração de resultados"
    ],
    target: "Departamentos de RH"
  },
  {
    id: "planejamento-carreira",
    title: "Planejamento de Carreira",
    description: "Orientação profissional para desenvolvimento de carreira consciente, alinhando objetivos pessoais com oportunidades de mercado.",
    icon: "compass",
    features: [
      "Assessment de perfil profissional",
      "Plano de desenvolvimento individual (PDI)",
      "Orientação para transição de carreira",
      "Estratégias de networking e posicionamento"
    ],
    target: "Profissionais e Executivos"
  },
];
