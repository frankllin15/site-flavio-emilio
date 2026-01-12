'use client';

import Link from 'next/link';
import Image from 'next/image';
import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import Card, { CardHeader, CardTitle, CardContent } from '@/components/ui/Card';
import { Button } from '@/components/ui/Button';
import Carousel, { CarouselItem } from '@/components/ui/Carousel';
import { services } from '@/lib/data/services';

export default function Services() {
  return (
    <section id="servicos" className="section-spacing bg-gray-50">
      <Container>
        <SectionHeading
          title="O que oferecemos"
          subtitle="Estratégias personalizadas para desenvolver pessoas e transformar organizações"
        />

        <Carousel
          prevLabel="Serviço anterior"
          nextLabel="Próximo serviço"
          mobileHintText="Arraste para o lado"
        >
          {services.map((service) => (
            <CarouselItem key={service.id}>
              <Card hover className="flex flex-col h-full">
                <CardHeader>
                  {service.logo ? (
                    // Quando houver logo, exibir apenas a logo
                    <div className="mb-4 flex justify-center">
                      <Image
                        src={service.logo}
                        alt={service.title || 'Service Logo'}
                        width={200}
                        height={80}
                        className="h-20 w-auto object-contain"
                      />
                    </div>
                  ) : (
                    // Quando não houver logo, exibir título com fonte destacada
                    <CardTitle className="text-2xl md:text-3xl font-extrabold font-display text-center text-slate-700 mb-4 tracking-tight uppercase">
                      {service.title}
                    </CardTitle>
                  )}
                </CardHeader>

                <CardContent className="flex-1 flex flex-col">
                  {/* Description Resumida - sempre truncada em 4 linhas */}
                  <p className="text-gray-600 mb-6 text-sm md:text-base leading-relaxed line-clamp-4">
                    {service.description}
                  </p>

                  {/* CTA Button */}
                  <div className="mt-auto">
                    <Button asChild variant="outline" className="w-full rounded-md">
                      <Link href={`/servicos/${service.id}`}>
                        Ver Detalhes
                      </Link>
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </CarouselItem>
          ))}
        </Carousel>

        {/* CTA */}
        <div className="text-center mt-12">
          <Button asChild size="lg">
            <Link href="/#contato">
              Solicitar Proposta
            </Link>
          </Button>
        </div>
      </Container>
    </section>
  );
}
