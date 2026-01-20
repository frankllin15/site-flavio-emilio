'use client';

import { testimonials } from "@/lib/data/socialProof"
import Card, { CardContent, CardHeader, CardTitle } from "../ui/Card"
import Container from "../ui/Container"
import SectionHeading from "../ui/SectionHeading"
import Carousel, { CarouselItem } from "../ui/Carousel"
import Image from "next/image"
import { Quote } from "lucide-react"

export default function Testimonials() {
  return (
    <section id="testimonials" className="section-spacing bg-gray-50">
        <Container>
            <SectionHeading
                title="Depoimentos"
                subtitle="O que nossos clientes dizem sobre nós"
                badge="Feedback"
                badgeVariant="secondary"
                icon={Quote}
            />

            <Carousel
                prevLabel="Depoimento anterior"
                nextLabel="Próximo depoimento"
                mobileHintText="Arraste para o lado"
            >
                {testimonials.map((testimonial) => (
                    <CarouselItem key={testimonial.id}>
                        <Card hover className="flex flex-col h-full">
                            <CardHeader>
                                <div className="flex items-center gap-4 mb-2">
                                {testimonial.avatar && (
                                <Image
                                    src={testimonial.avatar}
                                    alt={`Avatar de ${testimonial.author}`}
                                    width={48}
                                    height={48}
                                    className="rounded-full mb-2"
                                />
                                )}
                                <CardTitle>{testimonial.author}</CardTitle>
                                </div>
                                <span className="text-sm text-gray-500">{testimonial.role} - {testimonial.company}</span>
                            </CardHeader>
                            <CardContent className="flex-1">
                                <p className="text-gray-600">
                                    {/*quote*/}
                                    &ldquo;
                                    {testimonial.quote}
                                    &rdquo;
                                </p>
                            </CardContent>
                        </Card>
                    </CarouselItem>
                ))}
            </Carousel>
        </Container>
    </section>
  )
}