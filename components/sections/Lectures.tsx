'use client';

import Link from 'next/link';
import Image from 'next/image';
import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import Card, { CardHeader, CardTitle, CardContent } from '@/components/ui/Card';
import { Button } from '@/components/ui/Button';
import Carousel, { CarouselItem } from '@/components/ui/Carousel';
import { lectures } from '@/lib/data/lectures';

export default function Lectures() {
  return (
    <section id="palestras" className="section-spacing bg-white">
      <Container>
        <SectionHeading
          title="Nossas Palestras"
          subtitle="Conteúdos inspiradores que transformam perspectivas e impulsionam resultados"
        />

        <Carousel
          prevLabel="Palestra anterior"
          nextLabel="Próxima palestra"
          mobileHintText="Arraste para o lado"
        >
          {lectures.map((lecture) => (
            <CarouselItem key={lecture.id}>
              <Card hover className="flex flex-col h-full">
                <CardHeader>
                  {/* Logo */}
                  <div className="mb-4 flex justify-center">
                    <Image
                      src={lecture.logo}
                      alt={lecture.title}
                      width={200}
                      height={80}
                      className="h-20 w-auto object-contain"
                    />
                  </div>

                  <CardTitle className="text-xl text-center text-foreground">
                    {lecture.title}
                  </CardTitle>
                </CardHeader>

                <CardContent className="flex-1 flex flex-col">
                  {/* Description Resumida - sempre truncada em 4 linhas */}
                  <p className="text-gray-600 mb-6 text-sm md:text-base leading-relaxed line-clamp-4">
                    {lecture.introduction}
                  </p>

                  {/* CTA Button */}
                  <div className="mt-auto">
                    <Button asChild variant="outline" className="w-full rounded-md">
                      <Link href={`/palestras/${lecture.id}`}>
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
              Solicitar Palestra
            </Link>
          </Button>
        </div>
      </Container>
    </section>
  );
}
