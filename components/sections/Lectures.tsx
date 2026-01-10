'use client';

import { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import Card, { CardHeader, CardTitle, CardContent } from '@/components/ui/Card';
import { Button } from '@/components/ui/Button';
import { Check, ChevronDown, ChevronUp } from 'lucide-react';
import { lectures } from '@/lib/data/lectures';

export default function Lectures() {
  const [expandedCards, setExpandedCards] = useState<Record<string, boolean>>({});

  const toggleCard = (id: string) => {
    setExpandedCards(prev => ({
      ...prev,
      [id]: !prev[id]
    }));
  };

  return (
    <section id="palestras" className="section-spacing bg-white">
      <Container>
        <SectionHeading
          title="Palestras Motivacionais"
          subtitle="Conteúdos inspiradores que transformam perspectivas e impulsionam resultados"
        />

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {lectures.map((lecture) => {
            const isExpanded = expandedCards[lecture.id];

            return (
              <Card key={lecture.id} hover className="flex flex-col h-full">
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
                  {/* Introduction - sempre visível, mas truncada */}
                  <p className={`text-gray-600 mb-4 text-sm leading-relaxed ${!isExpanded && 'line-clamp-3'}`}>
                    {lecture.introduction}
                  </p>

                  {/* Conteúdo expandível */}
                  {isExpanded && (
                    <div className="space-y-4 animate-in fade-in duration-300">
                      {/* Subtitle (opcional) */}
                      {lecture.subtitle && (
                        <h4 className="font-semibold text-gray-800 text-sm">
                          {lecture.subtitle}
                        </h4>
                      )}

                      {/* Topics List (opcional) */}
                      {lecture.topics && (
                        <ul className="space-y-2">
                          {lecture.topics.map((topic, i) => (
                            <li key={i} className="flex items-start text-sm">
                              <Check className="w-4 h-4 text-accent-gold-500 mr-2 mt-0.5 flex-shrink-0" />
                              <span className="text-gray-700">{topic}</span>
                            </li>
                          ))}
                        </ul>
                      )}

                      {/* Conclusion */}
                      <p className="text-sm text-gray-600 italic pt-4 border-t border-gray-200">
                        {lecture.conclusion}
                      </p>
                    </div>
                  )}

                  {/* Botão Expandir/Recolher */}
                  <button
                    onClick={() => toggleCard(lecture.id)}
                    className="mt-4 flex items-center justify-center gap-2 text-sm font-medium text-primary hover:text-primary/80 transition-colors"
                  >
                    {isExpanded ? (
                      <>
                        Ver menos
                        <ChevronUp className="w-4 h-4" />
                      </>
                    ) : (
                      <>
                        Saiba mais
                        <ChevronDown className="w-4 h-4" />
                      </>
                    )}
                  </button>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* CTA */}
        <div className="text-center mt-12">
          <Button asChild size="lg">
            <Link href="#contato">
              Solicitar Palestra
            </Link>
          </Button>
        </div>
      </Container>
    </section>
  );
}
