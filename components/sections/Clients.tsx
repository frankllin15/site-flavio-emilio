'use client'
import { customers } from "@/lib/data/socialProof";
import Container from "../ui/Container";
import SectionHeading from "../ui/SectionHeading";
import Image from "next/image";
import { useMemo } from "react";

export default function Clients() {
  // Duplicar os clientes apenas 2x para criar efeito de loop infinito perfeito
  // A animação move exatamente 50% (uma cópia completa), criando loop sem jumps
  const duplicatedCustomers = useMemo(() => [...customers, ...customers], []);

  // Calcular duração baseada na quantidade de itens para manter velocidade constante
  // Cada item tem ~282px (250px min-width + 32px gap)
  // Velocidade fixa: 50px/s
  const animationDuration = useMemo(() => {
    const itemWidth = 282; // largura aproximada do item + gap
    const totalWidth = customers.length * itemWidth;
    const speed = 50; // pixels por segundo
    return totalWidth / speed;
  }, []);

  return (
    <section id="clients" className="section-spacing bg-white">
      <Container className="px-0">
        <SectionHeading
          title="Nossos Clientes"
          subtitle="Empresas que confiam em nosso trabalho"
        />

        <div className="relative overflow-hidden mt-12 space-y-8 md:space-y-12">
          {/* Gradientes nas bordas para efeito de fade */}
          <div className="absolute mb-0 left-0 top-0 bottom-0 w-16 md:w-32 bg-linear-to-r from-gray-50 to-transparent z-10 pointer-events-none" />
          <div className="absolute mb-0 right-0 top-0 bottom-0 w-16 md:w-32 bg-linear-to-l from-gray-50 to-transparent z-10 pointer-events-none" />

          {/* Primeira esteira - movimento para esquerda */}
          <div className="clients-track-wrapper">
            <div
              className="clients-track"
              style={{
                animationDuration: `${animationDuration}s`
              }}
            >
              {duplicatedCustomers.map((customer, index) => (
                <div
                  onClick={() => {
                    if (customer.siteUrl) {
                      window.open(customer.siteUrl, "_blank");
                    }
                  }}
                  key={`track1-${customer.id}-${index}`}
                  className="clients-item"
                >
                  <ClientsItem name={customer.name} logo={customer.logo} />

                </div>
              ))}
            </div>
          </div>

          {/* Segunda esteira - movimento para direita */}
          <div className="clients-track-wrapper">
            <div
              className="clients-track-reverse"
              style={{
                animationDuration: `${animationDuration}s`
              }}
            >
              {duplicatedCustomers.map((customer, index) => (
                <div
                  onClick={() => {
                    if (customer.siteUrl) {
                      window.open(customer.siteUrl, "_blank");
                    }
                  }}
                  key={`track2-${customer.id}-${index}`}
                  className="clients-item"
                >
                  <ClientsItem name={customer.name} logo={customer.logo} />
                </div>
              ))}
            </div>
          </div>
        </div>
      </Container>
    </section>
  );
}

const ClientsItem = ({ name, logo }: { name: string; logo?: string }) => {
  return (
    <div className="flex items-center justify-center h-20 md:h-32 p-3 md:p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
      {logo ? (
        <div className="relative w-full h-full">
          <Image
            src={logo}
            alt={name}
            fill
            className="object-contain"
          />
        </div>
      ) : (
        <span className="text-sm md:text-xl font-display font-bold text-gray-700 uppercase tracking-wide px-2 md:px-4 text-center">
          {name}
        </span>
      )}
    </div>
  );
}
