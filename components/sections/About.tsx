import Container from "@/components/ui/Container";
import SectionHeading from "@/components/ui/SectionHeading";
import Image from "next/image";

export default function About() {
  return (
    <section id="sobre" className="section-spacing bg-white">
      <Container>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Image Column */}
          <div className="order-2 lg:order-1">
            <div className="relative">
              <div className="aspect-square rounded-2xl overflow-hidden shadow-2xl">
                <div className="w-full h-full bg-gradient-to-br from-brand-blue-200 to-brand-blue-400 flex items-center justify-center">
                  <Image
                    src="/images/photos/about-photo.jpg"
                    alt="Flávio Emílio Cavalcanti"
                    fill
                    className="object-cover"
                    sizes="(max-width: 768px) 100vw, 50vw"
                  />
                </div>
              </div>
              {/* Decorative Element */}
              <div className="absolute -bottom-6 -right-6 w-48 h-48 bg-accent-gold-400 rounded-2xl -z-10 hidden md:block"></div>
            </div>
          </div>

          {/* Content Column */}
          <div className="order-1 lg:order-2">
            <SectionHeading
              title="Sobre Flávio Emílio"
              subtitle="Unindo rigor acadêmico e experiência prática para transformar organizações"
              centered={false}
            />

            <div className="space-y-4 text-gray-700 text-lg">
              <p>
                <strong>Mestre em Administração</strong> e professor no UNI-RN,
                Flávio Emílio Cavalcanti é uma referência nacional em gestão de
                pessoas, liderança e desenvolvimento de carreiras.
              </p>

              <p>
                Com uma trajetória que combina solidez acadêmica e vivência
                empresarial, ele traduz conceitos complexos em soluções práticas
                e acessíveis, sempre com foco em resultados sustentáveis e
                desenvolvimento humano.
              </p>

              <p>
                Como autor, palestrante e consultor, já impactou milhares de
                profissionais e dezenas de organizações em todo o Brasil,
                promovendo transformações reais através da liderança consciente
                e da gestão estratégica de pessoas.
              </p>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-3 gap-6 mt-10">
              <div className="text-center">
                <div className="text-4xl font-bold text-primary mb-2">15+</div>
                <div className="text-sm text-gray-600 uppercase tracking-wider">
                  Anos de Experiência
                </div>
              </div>
              <div className="text-center">
                <div className="text-4xl font-bold text-primary mb-2">100+</div>
                <div className="text-sm text-gray-600 uppercase tracking-wider">
                  Empresas Atendidas
                </div>
              </div>
              <div className="text-center">
                <div className="text-4xl font-bold text-primary mb-2">2</div>
                <div className="text-sm text-gray-600 uppercase tracking-wider">
                  Livros Publicados
                </div>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </section>
  );
}
