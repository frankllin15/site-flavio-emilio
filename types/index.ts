// Common types used across the application

export interface Author {
  name: string;
  avatar?: string;
  bio?: string;
}

export interface SEO {
  title: string;
  description: string;
  keywords?: string[];
  ogImage?: string;
}

export interface Service {
  id: string;
  title?: string;
  description: string;
  logo?: string;  // Caminho para logo (quando disponível)
  color?: string;  // Cor do placeholder (quando não há logo)
  subtitle?: string;  // Subtítulo opcional antes de listas
  topics?: string[];  // Lista de temas/benefícios/casos
  learningPoints?: {
    title: string;
    description: string;
  }[];  // Pontos de aprendizado com título e descrição
  footer?: string;  // Texto de fechamento opcional
}

export interface Lecture {
  id: string;
  title: string;
  logo: string;  // Todas as palestras têm logo
  introduction: string;  // Texto introdutório
  subtitle?: string;  // Subtítulo antes da lista (opcional)
  topics?: string[];  // Lista de tópicos/pilares
  conclusion: string;  // Mensagem final/fechamento
}

export interface Book {
  id: string;
  title: string;
  subtitle?: string;
  description: string;
  coverImage: string;
  publishYear: number;
  purchaseUrl?: string;
  amazonUrl?: string;
  highlights: string[];
}

export interface Customers {
  id: string;
  name: string;
  logo?: string; // Path to logo image
  siteUrl?: string; // Optional URL to the customer's site
}

export interface Testimonial {
  id: string;
  quote: string;
  author: string;
  role: string;
  company: string;
  avatar?: string;
}

export interface Event {
  id: string;
  title: string;
  description: string;
  location: string;
  image: string;
  date?: string; // Optional - for displaying when it happened
}

// Re-export all types from other type files
export * from './blog';
export * from './contact';
