import Image from "next/image";
import Link from "next/link";
import Container from "@/components/ui/Container";
import SectionHeading from "@/components/ui/SectionHeading";
import { Button } from "@/components/ui/Button";
import { events, getSortedEvents } from "@/lib/data/events";
import { MapPin } from "lucide-react";

interface EventsProps {
  limit?: number;
  showViewAllButton?: boolean;
  showHeading?: boolean;
}

export default function Events({
  limit,
  showViewAllButton = true,
  showHeading = true,
}: EventsProps) {
  const displayEvents = getSortedEvents(limit);
  return (
    <section id="eventos" className="section-spacing bg-gray-50">
      <Container>
        {showHeading && (
          <SectionHeading
            title="Eventos Realizados"
            subtitle="Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações"
            centered
          />
        )}

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {displayEvents.map((event) => (
            <div key={event.id} className="group cursor-pointer">
              {/* Event Image */}
              <div className="relative aspect-[4/3] rounded-lg overflow-hidden mb-4 bg-gray-100">
                <Image
                  src={event.image}
                  alt={event.title}
                  fill
                  className="object-cover transition-transform duration-300 group-hover:scale-105"
                  sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                />
                {/* Overlay on hover */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </div>

              {/* Event Info */}
              <div>
                {/* Date Badge */}
                {event.date && (
                  <div className="mb-2">
                    <span className="inline-block text-xs font-semibold text-primary uppercase tracking-wider">
                      {event.date}
                    </span>
                  </div>
                )}

                {/* Title */}
                <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                  {event.title}
                </h3>

                {/* Description */}
                <p className="text-gray-600 text-sm md:text-base mb-3 line-clamp-4">
                  {event.description}
                </p>

                {/* Location */}
                <div className="flex items-start text-sm text-gray-700">
                  <MapPin className="mr-2 mt-0.5 shrink-0" />
                  <span>{event.location}</span>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* View All Button - Only show if limit is set and there are more events */}
        {showViewAllButton && limit && events.length > limit && (
          <div className="text-center mt-8">
            <Button variant="secondary" asChild>
              <Link href="/eventos">Ver Todos os Eventos</Link>
            </Button>
          </div>
        )}

        {/* CTA */}
        <div className="text-center mt-12">
          <p className="text-gray-600 mb-6 text-lg">
            Quer levar palestras e treinamentos de excelência para sua
            organização?
          </p>
          <Button asChild size="lg">
            <Link href="/#contato">Solicitar Proposta para Evento</Link>
          </Button>
        </div>
      </Container>
    </section>
  );
}
