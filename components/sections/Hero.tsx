import Image from "next/image";
import Link from "next/link";
import { Button } from "@/components/ui/Button";

export default function Hero() {
  return (
    <section
      id="hero"
      className="relative min-h-screen flex items-center bg-linear-to-br from-brand-blue-900 via-brand-blue-700 to-brand-blue-500 overflow-hidden"
    >
      <div className="container mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-32">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-end">
          {/* Image Column - Left */}
          <div className="order-2 lg:order-1 flex justify-center lg:justify-end">
            <div className="relative w-full max-w-md lg:max-w-lg h-100 sm:h-125 lg:h-150">
              <Image
                src="/images/heros.png"
                alt="Flávio Emílio Cavalcanti"
                fill
                priority
                className="object-contain object-center lg:object-bottom-right"
                quality={100}
              />
            </div>
          </div>

          {/* Content Column - Right */}
          <div className="order-1 lg:order-2 text-white py-8 pb-20">
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
