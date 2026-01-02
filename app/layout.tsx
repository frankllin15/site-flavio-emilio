import type { Metadata } from "next";
import { Geist, Geist_Mono, Playfair_Display } from "next/font/google";
import "./globals.css";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

const playfair = Playfair_Display({
  variable: "--font-playfair",
  subsets: ["latin"],
  weight: ["400", "700"],
});

export const metadata: Metadata = {
  title: {
    default: "Flávio Emílio Cavalcanti | Consultor Organizacional e Especialista em Gestão de Carreiras",
    template: "%s | Flávio Emílio Cavalcanti"
  },
  description: "Transformando o potencial humano em resultados organizacionais através de liderança estratégica e gestão de carreira consciente. Consultoria, palestras e treinamentos in-company.",
  keywords: ["gestão de pessoas", "liderança", "carreira", "consultoria organizacional", "RH", "desenvolvimento profissional", "palestras empresariais", "treinamento corporativo", "clima organizacional"],
  authors: [{ name: "Flávio Emílio Cavalcanti" }],
  creator: "Flávio Emílio Cavalcanti",
  openGraph: {
    type: "website",
    locale: "pt_BR",
    url: "https://flavioemilio.com.br",
    title: "Flávio Emílio Cavalcanti | Consultor Organizacional",
    description: "Transformando o potencial humano em resultados organizacionais através de liderança estratégica e gestão de carreira consciente.",
    siteName: "Flávio Emílio Cavalcanti",
    images: [
      {
        url: "/images/og-image.jpg",
        width: 1200,
        height: 630,
        alt: "Flávio Emílio Cavalcanti - Consultor Organizacional"
      }
    ]
  },
  twitter: {
    card: "summary_large_image",
    title: "Flávio Emílio Cavalcanti | Consultor Organizacional",
    description: "Transformando o potencial humano em resultados organizacionais",
    images: ["/images/og-image.jpg"],
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
  verification: {
    // Add Google Search Console verification when available
    // google: 'your-verification-code',
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="pt-BR">
      <body
        className={`${geistSans.variable} ${geistMono.variable} ${playfair.variable} antialiased`}
      >
        {children}
      </body>
    </html>
  );
}
