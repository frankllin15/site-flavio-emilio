import { notFound } from "next/navigation";
import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import Container from "@/components/ui/Container";
import { Button } from "@/components/ui/Button";
import { Check, ArrowLeft } from "lucide-react";
import { services } from "@/lib/data/services";
import Header from "@/components/layout/Header";
import Footer from "@/components/layout/Footer";

// Helper function (mesma do Services.tsx)
const getColorClass = (color?: string) => {
  const colorMap: Record<string, string> = {
    blue: "bg-brand-blue-500",
    gold: "bg-accent-gold-500",
    purple: "bg-purple-500",
  };
  return colorMap[color || "blue"] || "bg-gray-500";
};

export async function generateStaticParams() {
  return services.map((service) => ({
    id: service.id,
  }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ id: string }>;
}): Promise<Metadata> {
  const { id } = await params;
  const service = services.find((s) => s.id === id);

  if (!service) {
    return {
      title: "Serviço não encontrado",
    };
  }

  return {
    title: `${service.title} | Proficient Consultoria`,
    description: service.description.substring(0, 160),
  };
}

export default async function ServicePage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const service = services.find((s) => s.id === id);

  if (!service) {
    notFound();
  }

  return (
    <div className="bg-white">
      <Header />
      <main className="min-h-screen bg-white mt-20">
        {/* Breadcrumb / Back Button */}
        {/* <div className="bg-gray-50 border-b border-gray-200">
        <Container>
          <div className="py-6">
            <Link
              href="/#servicos"
              className="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary transition-colors"
            >
              <ArrowLeft className="w-4 h-4" />
              Voltar para Serviços
            </Link>
          </div>
        </Container>
      </div> */}

        {/* Hero Section */}
        <section className="section-spacing">
          <Container>
            <div className="max-w-4xl mx-auto">
              {/* Logo ou Placeholder */}
              <div className="mb-8 flex justify-center">
                {service.logo ? (
                  <Image
                    src={service.logo}
                    alt={service.title || "Logo do Serviço"}
                    width={300}
                    height={120}
                    className="h-24 w-auto object-contain"
                  />
                ) : (
                  <h1 className="text-4xl font-display md:text-5xl font-bold text-center text-gray-900 mb-6">
                    {service.title}
                  </h1>
                )}
              </div>

              {/* Title */}

              {/* Description */}
              <div className="prose prose-lg max-w-none mb-12">
                <p className="text-xl text-gray-700 leading-relaxed text-center">
                  {service.description}
                </p>
              </div>

              {/* Subtitle + Topics/Learning Points */}
              {(service.topics || service.learningPoints) && (
                <div className="bg-gray-50 rounded-2xl p-8 mb-12">
                  {service.subtitle && (
                    <h2 className="text-2xl font-semibold text-gray-900 mb-6">
                      {service.subtitle}
                    </h2>
                  )}

                  {/* Topics List */}
                  {service.topics && (
                    <ul className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                      {service.topics.map((topic, i) => (
                        <li key={i} className="flex items-start">
                          <Check className="w-6 h-6 text-accent-gold-500 mr-3 mt-1 flex-shrink-0" />
                          <span className="text-lg text-gray-700">{topic}</span>
                        </li>
                      ))}
                    </ul>
                  )}

                  {/* Learning Points */}
                  {service.learningPoints && (
                    <div className="space-y-6">
                      {service.learningPoints.map((point, i) => (
                        <div
                          key={i}
                          className="border-l-4 border-accent-gold-400 pl-6"
                        >
                          <h3 className="text-xl font-semibold text-gray-900 mb-2">
                            {point.title}
                          </h3>
                          <p className="text-gray-700 leading-relaxed">
                            {point.description}
                          </p>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              )}

              {/* Footer */}
              {service.footer && (
                <div className="bg-accent-gold-50 border-l-4 border-accent-gold-500 rounded-r-lg p-6 mb-12">
                  <p className="text-lg text-gray-800 italic leading-relaxed">
                    {service.footer}
                  </p>
                </div>
              )}

              {/* CTA */}
              <div className="text-center">
                <Button asChild size="lg" className="text-lg px-8 py-6">
                  <Link href="#contato">Solicitar Proposta</Link>
                </Button>
              </div>
            </div>
          </Container>
        </section>
      </main>
      <Footer />
    </div>
  );
}
