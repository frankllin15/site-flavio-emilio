import Link from 'next/link';
import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import Card, { CardHeader, CardTitle, CardContent } from '@/components/ui/Card';
import { Button } from '@/components/ui/Button';
import { services } from '@/lib/data/services';

// Icon mapping (simple placeholders - can be replaced with actual icon library)
const icons: Record<string, string> = {
  users: '👥',
  target: '🎯',
  heart: '💙',
  compass: '🧭',
};

export default function Services() {
  return (
    <section id="solucoes" className="section-spacing bg-gray-50">
      <Container>
        <SectionHeading
          title="Soluções"
          subtitle="Estratégias personalizadas para desenvolver pessoas e transformar organizações"
        />

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {services.map((service) => (
            <Card key={service.id} hover>
              <CardHeader>
                {/* Icon */}
                <div className="text-5xl mb-4">
                  {icons[service.icon] || '📊'}
                </div>

                <CardTitle>{service.title}</CardTitle>
              </CardHeader>

              <CardContent>
                <p className="text-gray-600 mb-4">{service.description}</p>

                {/* Features List */}
                <ul className="space-y-2 mb-4">
                  {service.features.map((feature, index) => (
                    <li key={index} className="flex items-start">
                      <svg
                        className="w-5 h-5 text-primary mr-2 mt-1 flex-shrink-0"
                        fill="none"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path d="M5 13l4 4L19 7"></path>
                      </svg>
                      <span className="text-gray-700">{feature}</span>
                    </li>
                  ))}
                </ul>

                {/* Target Audience */}
                <div className="pt-4 border-t border-gray-200">
                  <span className="text-sm text-gray-500">Ideal para:</span>
                  <span className="text-sm font-semibold text-gray-700 ml-2">
                    {service.target}
                  </span>
                </div>
              </CardContent>
            </Card>
          ))}
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
