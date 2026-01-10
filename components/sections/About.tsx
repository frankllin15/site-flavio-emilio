import Container from "@/components/ui/Container";
import SectionHeading from "@/components/ui/SectionHeading";
import Image from "next/image";

export default function About() {
  return (
    <section id="sobre" className="section-spacing bg-gray-50">
      <Container>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Image Column */}
          <div className="order-2 lg:order-1">
            <div className="relative">
              <div className="aspect-square rounded-2xl overflow-hidden shadow-2xl">
                <div className="w-full h-full bg-linear-to-br from-brand-blue-200 to-brand-blue-400 flex items-center justify-center">
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
              // subtitle="Unindo rigor acadêmico e experiência prática para transformar organizações"
              centered={false}
            />

            <div className="space-y-6 text-gray-700 text-lg leading-relaxed border-l-4 border-accent-gold-400 pl-6">
              <p>
                Sou <span className="text-accent-gold-500 font-medium">Flávio Emílio Monteiro Cavalcanti</span>,{" "}
                <span className="text-accent-gold-500">administrador</span> por formação,
                educador por vocação e apaixonado por desenvolver pessoas e organizações.
                Com mais de 25 anos de atuação como consultor e educador corporativo,
                venho ajudando empresas privadas e organizações públicas a enfrentarem
                desafios estratégicos e a alcançarem resultados consistentes através do
                desenvolvimento de suas lideranças, da melhoria de processos e do
                fortalecimento da cultura organizacional.
              </p>

              <p>
                Sou presidente reeleito do <span className="text-accent-gold-500">Conselho Regional de Administração do RN (CRA-RN)</span>,
                e tenho a satisfação de contribuir para o desenvolvimento da profissão no nosso estado.
                Sou <span className="text-accent-gold-500">mestre em Administração de Recursos Humanos pela UFRN</span>{" "}
                e desde cedo trilhei uma carreira que une a academia e o mercado.
                Já lecionei em diversas instituições de ensino superior e coordenei programas
                de pós-graduação, sempre com foco na aplicação prática do conhecimento e visão de mercado.
              </p>

              <p>
                Nos últimos anos, conduzi projetos de capacitação e consultoria em empresas
                de diversos setores — de hospitais a resorts, de mineradoras a redes de
                varejo em vários estados brasileiros. Atendo organizações que acreditam que
                investir nas pessoas é o caminho mais sustentável para crescer com consistência
                e propósito.
              </p>

              <p>
                Se você busca um parceiro comprometido com resultados, com linguagem acessível
                e profunda bagagem técnica, será um prazer contribuir com sua equipe.
              </p>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-3 gap-6 mt-10">
              <div className="text-center">
                <div className="text-4xl font-bold text-primary mb-2">25+</div>
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
