import type { Event } from '@/types';

export const events: Event[] = [
  {
    id: "1",
    title: "Desenvolvimento Comercial: Lidando com Objeções e Gerando Oportunidades",
    description: "Encontro com o supertime comercial da Potiguar Distribuidora Agropet Paraíba, focado no desenvolvimento de competências em vendas. O momento foi dedicado a refletir e praticar estratégias sobre como lidar com objeções e transformá-las em oportunidades reais de fechamento, fortalecendo a performance e os resultados da equipe.",
    location: "Potiguar Distribuidora Agropet – Paraíba",
    image: "/images/events/time-potiguardistribuidora.jpg",
    date: "Novembro 2025",
    sortDate: new Date("2025-11-15")
  },
  {
    id: "2",
    title: "CRA-RN no 2º Fórum de Presidentes do Sistema CFA/CRAs",
    description: "O CRA-RN marcou presença no 2º Fórum de Presidentes do Sistema CFA/CRAs, um encontro estratégico voltado ao alinhamento institucional, à tomada de decisões relevantes e ao fortalecimento da Administração no Brasil. O evento trouxe importantes deliberações e excelentes perspectivas para os Profissionais de Administração do Rio Grande do Norte e de todo o país.",
    location: "CRA-RN - Conselho Regional de Administração do Rio Grande do Norte",
    image: "/images/events/forum-de-presidente.jpg",
    date: "Dezembro 2025",
    sortDate: new Date("2025-12-15")
  },
  {
    id: "3",
    title: "Encerramento do Ciclo de Palestras 2025",
    description: "Realizada a última palestra de 2025 no Hospital Rio Grande, com casa cheia e a participação do time administrativo e de pessoal. Um momento de aprendizado, troca e fortalecimento de equipes, encerrando o ano com expectativas positivas e desejos de um 2026 ainda mais promissor para todos.",
    location: "Hospital Rio Grande - Natal/RN",
    image: "/images/events/hospital-rio-grande.jpg",
    date: "Setembro 2025",
    sortDate: new Date("2025-09-15")
  },
  {
    id: "4",
    title: "Formação em Liderança chega à 13ª Unidade Vila Galé",
    description: "A Vila Galé Belém recebeu a formação em liderança, tornando-se a 13ª unidade do grupo a participar desse importante processo de desenvolvimento. Um momento especial de aprendizado, troca de experiências e fortalecimento de lideranças, à altura da energia e receptividade desse time incrível. Gratidão a todos os envolvidos!",
    location: "Vila Galé Belém, Pará",
    image: "/images/events/vila-gale-belem.jpg",
    date: "Agosto 2025",
    sortDate: new Date("2025-08-15")
  },
  {
    id: "5",
    title: "Palestra de Encerramento do Encontro Anual da Coopern Enfermagem",
    description: "Uma tarde extremamente agradável ao lado dos profissionais de enfermagem da Coopern Enfermagem, marcando a palestra de encerramento do encontro anual dessa prestigiada instituição cooperativista. Um momento de troca, aprendizado e valorização do trabalho essencial desenvolvido pelos profissionais da área.",
    location: "Parque das Dunas, Natal/RN",
    image: "/images/events/coopern-enfermagem.jpg",
    date: "Julho 2025",
    sortDate: new Date("2025-07-15")
  },
];

/**
 * Get events sorted by date (most recent first)
 * @param limit - Optional limit for number of events to return
 * @returns Sorted array of events
 */
export function getSortedEvents(limit?: number): Event[] {
  const sorted = [...events].sort((a, b) =>
    b.sortDate.getTime() - a.sortDate.getTime()
  );

  return limit ? sorted.slice(0, limit) : sorted;
}