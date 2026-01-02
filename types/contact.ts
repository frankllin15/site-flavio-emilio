// Contact form data structure
export interface ContactFormData {
  name: string;
  email: string;
  phone?: string;
  company?: string;
  message: string;
  subject: 'consultoria' | 'palestra' | 'livros' | 'outros';
}

// API response types
export interface ContactSuccessResponse {
  success: true;
  data?: unknown;
}

export interface ContactErrorResponse {
  success: false;
  error: string;
  details?: unknown;
}

export type ContactResponse = ContactSuccessResponse | ContactErrorResponse;
