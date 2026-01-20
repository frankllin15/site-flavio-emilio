import Image from "next/image";
import Link from "next/link";
import { Button } from "@/components/ui/Button";

export default function Hero() {
  return (
    <section
      id="hero"
      className="relative min-h-screen flex items-center overflow-hidden bg-brand-blue-900"
    >
      {/* Background Image */}
      <Image
        src="/images/hero.png"
        alt=""
        fill
        priority
        className="object-contain object-top-left hidden md:block"
        quality={100}
      />

      <Image
        src="/images/hero-mobile.png"
        alt=""
        fill
        priority
        className="object-contain object-top-left  md:hidden"
        quality={100}
        
      />

      {/* Overlay with transparency */}
      {/* <div className="absolute inset-0 bg-brand-blue-900/75 bg-linear-to-t from-brand-blue-900/90 via-brand-blue-500/10 to-transparent" /> */}

      {/* Edge fade gradients to hide image borders */}
      {/* <div className="absolute inset-0 bg-linear-to-l from-brand-blue-900 via-brand-blue-900/90 to-transparent" /> */}

      <div className="relative z-10 container mx-auto px-4  pt-32 sm:px-6 lg:px-8 lg:pt-32">
        <div className="max-w-3xl ml-auto">
          {/* Content */}
          <div className="text-white py-8 pb-20">
            {/* Main Heading */}
            <h1 className="text-2xl md:text-4xl xl:text-5xl font-bold mb-6 leading-tight">
              Transformando Potencial Humano em{" "}
              <span className="text-accent-gold-400">
                Resultados Organizacionais
              </span>
            </h1>

            {/* Subtitle */}
            <p className="text-lg md:text-xl lg:text-2xl mb-12 text-gray-100 leading-relaxed">
              Consultoria organizacional, palestras e treinamentos in-company
              com foco em liderança estratégica e gestão de carreira consciente.
            </p>

            {/* CTAs */}
            <div className="flex flex-col sm:flex-row gap-4">
              <Button
                size={"lg"}
                variant="accent"
                asChild={true}
                className="text-base md:text-lg px-8 py-4"
              >
                <Link href="/#contato">Contrate para sua Empresa</Link>
              </Button>
              <Button
                size={"lg"}
                asChild={true}
                className="text-base md:text-lg px-8 py-4 bg-white text-primary hover:bg-gray-100 border-white"
              >
                <Link href="#livros">Conheça meus Livros</Link>
              </Button>
            </div>
          </div>
        </div>
      </div>

      {/* Scroll Indicator */}
      <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#sobre" aria-label="Rolar para baixo">
          <svg
            className="w-6 h-6 text-white"
            fill="none"
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
          </svg>
        </a>
      </div>
    </section>
  );
}
