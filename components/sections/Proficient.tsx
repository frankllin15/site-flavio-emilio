import Container from "@/components/ui/Container";
import Image from "next/image";
import { Shield, Zap, Heart, Target, TrendingUp } from "lucide-react";
import { WaveDivider } from "@/components/ui/decorative";

export default function Proficient() {
  const values = [
    {
      icon: Shield,
      term: "Integridade",
      description: "como base de todas as relações"
    },
    {
      icon: Zap,
      term: "Praticidade",
      description: "aliada à excelência técnica"
    },
    {
      icon: Heart,
      term: "Respeito",
      description: "às pessoas, suas histórias e contextos"
    },
    {
      icon: Target,
      term: "Compromisso",
      description: "com resultados reais"
    },
    {
      icon: TrendingUp,
      term: "Aprendizado contínuo",
      description: "como caminho de evolução"
    }
  ];

  return (
    <section id="proficient" className="relative section-spacing bg-white overflow-hidden">    
      <Container>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Left Column - Logo and Content */}
          <div className="order-1">
            {/* Logo */}
            <div className="flex justify-center lg:justify-start mb-8">
              <Image
                src="/images/logos/proficient.png"
                alt="Proficient"
                width={300}
                height={80}
                className="h-16 w-auto"
              />
            </div>

            {/* Mission Statement */}
            <p className="text-xl md:text-2xl text-center lg:text-left text-gray-700 font-medium mb-8 leading-relaxed">
              Compartilhar competências de gestão para o desenvolvimento de pessoas e
              negócios, promovendo resultados sustentáveis e transformações positivas
              no ambiente organizacional
            </p>

            {/* Introduction */}
            <div className="text-center lg:text-left">
              <p className="text-lg text-gray-700 leading-relaxed">
                Ao longo da minha trajetória, desenvolvi projetos em empresas de
                diversos portes e segmentos, sempre com foco em transformar estratégias
                em resultados e lideranças em agentes de mudança. Escrevi livros, criei
                programas de desenvolvimento e idealizei a Proficient com o propósito
                claro: compartilhar competências de gestão que geram impacto real na
                vida das pessoas e no desempenho dos negócios.
              </p>
            </div>
          </div>

          {/* Right Column - Values */}
          <div className="order-2">
            <h3 className="text-2xl md:text-3xl font-bold text-center lg:text-left text-gray-900 mb-8">
              Nossos Valores
            </h3>

            {/* Values Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
              {values.map((value, index) => {
                const Icon = value.icon;
                return (
                  <div
                    key={index}
                    className="flex items-start gap-4 group hover:transform hover:-translate-x-1 transition-all duration-300"
                  >
                    {/* Icon */}
                    <div className="w-12 h-12 flex-shrink-0 bg-accent-gold-100 rounded-full flex items-center justify-center group-hover:bg-accent-gold-200 transition-colors">
                      <Icon className="w-7 h-7 text-accent-gold-500" strokeWidth={2} />
                    </div>

                    {/* Content */}
                    <div>
                      <h4 className="text-lg font-semibold text-gray-900 mb-1">
                        {value.term}
                      </h4>
                      <p className="text-gray-600 leading-relaxed">
                        {value.description}
                      </p>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </Container>

      {/* Wave Divider - Bottom */}
      <WaveDivider variant="white-to-gray" position="bottom" />
    </section>
  );
}
