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
  title: string;
  description: string;
  icon: string; // Icon name or path
  features: string[];
  target: string; // Target audience
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

export interface CompanyLogo {
  id: string;
  name: string;
  logo: string; // Path to logo image
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
