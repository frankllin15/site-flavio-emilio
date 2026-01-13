
import { testimonials } from "@/lib/data/socialProof"
import Card, { CardContent, CardHeader, CardTitle } from "../ui/Card"
import Container from "../ui/Container"
import SectionHeading from "../ui/SectionHeading"
import Image from "next/image"
export default async function SocialProof() {
  return (
    <section id="social-proof" className="section-spacing bg-gray-50">
        <Container>
            <SectionHeading
                title="Depoimentos"
                subtitle="O que nossos clientes dizem sobre nós"
            />
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {testimonials.map((testimonial) => (
                    <Card key={testimonial.id} hover>
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
                        <CardContent>
                            <p className="text-gray-600">
                                {/*quote*/}
                                &ldquo;
                                {testimonial.quote}
                                &rdquo;
                                </p>
                        </CardContent>
                    </Card>
                ))}
            </div>
        </Container>
    </section>
  )
}