'use client';

import { useState, useCallback, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import Card, { CardHeader, CardTitle, CardContent } from '@/components/ui/Card';
import { Button } from '@/components/ui/Button';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { services } from '@/lib/data/services';

// Helper function to get color class for placeholder
const getColorClass = (color?: string) => {
  const colorMap: Record<string, string> = {
    blue: 'bg-brand-blue-500',
    gold: 'bg-accent-gold-500',
    purple: 'bg-purple-500',
  };
  return colorMap[color || 'blue'] || 'bg-gray-500';
};

export default function Services() {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [itemsPerView, setItemsPerView] = useState(3);

  // Detect screen size and adjust items per view
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth < 768) {
        setItemsPerView(1); // Mobile
      } else if (window.innerWidth < 1024) {
        setItemsPerView(2); // Tablet
      } else {
        setItemsPerView(3); // Desktop
      }
    };

    handleResize(); // Initial check
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const totalPages = Math.ceil(services.length / itemsPerView);

  // Reset to first page when items per view changes
  useEffect(() => {
    setCurrentIndex(0);
  }, [itemsPerView]);

  const goToNext = useCallback(() => {
    setCurrentIndex((prevIndex) =>
      prevIndex >= totalPages - 1 ? 0 : prevIndex + 1
    );
  }, [totalPages]);

  const goToPrevious = useCallback(() => {
    setCurrentIndex((prevIndex) =>
      prevIndex === 0 ? totalPages - 1 : prevIndex - 1
    );
  }, [totalPages]);

  const goToSlide = useCallback((index: number) => {
    setCurrentIndex(index);
  }, []);

  return (
    <section id="servicos" className="section-spacing bg-gray-50">
      <Container>
        <SectionHeading
          title="O que oferecemos"
          subtitle="Estratégias personalizadas para desenvolver pessoas e transformar organizações"
        />

        {/* Carousel Container */}
        <div className="relative">
          {/* Navigation Buttons */}
          <button
            onClick={goToPrevious}
            className="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors hidden md:flex items-center justify-center"
            aria-label="Serviço anterior"
          >
            <ChevronLeft className="w-6 h-6 text-gray-700" />
          </button>

          <button
            onClick={goToNext}
            className="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors hidden md:flex items-center justify-center"
            aria-label="Próximo serviço"
          >
            <ChevronRight className="w-6 h-6 text-gray-700" />
          </button>

          {/* Carousel Track */}
          <div className="overflow-hidden">
            <div
              className="flex transition-transform duration-500 ease-out"
              style={{
                transform: `translateX(-${currentIndex * 100}%)`
              }}
            >
          {services.map((service) => {
            return (
              <div
                key={service.id}
                className="px-2"
                style={{
                  minWidth: `${100 / itemsPerView}%`,
                  width: `${100 / itemsPerView}%`
                }}
              >
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
                      <CardTitle className="text-3xl font-extrabold font-display text-center text-slate-700 mb-4 tracking-tight uppercase">
                        {service.title}
                      </CardTitle>
                    )}
                  </CardHeader>

                  <CardContent className="flex-1 flex flex-col">
                    {/* Description Resumida - sempre truncada em 3 linhas */}
                    <p className="text-gray-600 mb-6 text-sm leading-relaxed line-clamp-3">
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
              </div>
            );
          })}
            </div>
          </div>

          {/* Indicators/Dots */}
          <div className="flex justify-center gap-2 mt-8">
            {Array.from({ length: totalPages }).map((_, index) => (
              <button
                key={index}
                onClick={() => goToSlide(index)}
                className={`h-2 rounded-full transition-all ${
                  index === currentIndex
                    ? 'w-8 bg-accent-gold-500'
                    : 'w-2 bg-gray-300 hover:bg-gray-400'
                }`}
                aria-label={`Ir para página ${index + 1}`}
              />
            ))}
          </div>
        </div>

        {/* CTA */}
        <div className="text-center mt-12">
          <Button asChild size="lg">
            <Link href="#contato">
              Solicitar Proposta
            </Link>
          </Button>
        </div>
      </Container>
    </section>
  );
}
