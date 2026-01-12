import { notFound } from "next/navigation";
import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";
import Container from "@/components/ui/Container";
import { Button } from "@/components/ui/Button";
import { Check } from "lucide-react";
import { lectures } from "@/lib/data/lectures";
import Header from "@/components/layout/Header";
import Footer from "@/components/layout/Footer";

export async function generateStaticParams() {
  return lectures.map((lecture) => ({
    id: lecture.id,
  }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ id: string }>;
}): Promise<Metadata> {
  const { id } = await params;
  const lecture = lectures.find((l) => l.id === id);

  if (!lecture) {
    return {
      title: "Palestra não encontrada",
    };
  }

  return {
    title: `${lecture.title} | Flávio Emílio Cavalcanti`,
    description: lecture.introduction.substring(0, 160),
  };
}

export default async function LecturePage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const lecture = lectures.find((l) => l.id === id);

  if (!lecture) {
    notFound();
  }

  return (
    <div className="bg-white">
      <Header fixed />
      <main className="min-h-screen bg-white pt-20">
        {/* Hero Section */}
        <section className="section-spacing">
          <Container>
            <div className="max-w-4xl mx-auto">
              {/* Logo */}
              <div className="mb-8 flex justify-center">
                <Image
                  src={lecture.logo}
                  alt={lecture.title}
                  width={300}
                  height={120}
                  className="h-24 w-auto object-contain"
                />
              </div>

              {/* Title */}
              <h1 className="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-8">
                {lecture.title}
              </h1>

              {/* Introduction */}
              <div className="prose prose-lg max-w-none mb-12">
                <p className="text-xl text-gray-700 leading-relaxed">
                  {lecture.introduction}
                </p>
              </div>

              {/* Subtitle + Topics */}
              {lecture.topics && (
                <div className="bg-gray-50 rounded-2xl p-8 mb-12">
                  {lecture.subtitle && (
                    <h2 className="text-2xl font-semibold text-gray-900 mb-6">
                      {lecture.subtitle}
                    </h2>
                  )}

                  <ul className="grid grid-cols-1 gap-4">
                    {lecture.topics.map((topic, i) => (
                      <li key={i} className="flex items-start">
                        <Check className="w-6 h-6 text-accent-gold-500 mr-3 mt-1 flex-shrink-0" />
                        <span className="text-lg text-gray-700">{topic}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              )}

              {/* Conclusion */}
              {lecture.conclusion && (
                <div className="bg-accent-gold-50 border-l-4 border-accent-gold-500 rounded-r-lg p-6 mb-12">
                  <p className="text-lg text-gray-800 italic leading-relaxed">
                    {lecture.conclusion}
                  </p>
                </div>
              )}

              {/* CTA */}
              <div className="text-center">
                <Button asChild size="lg" className="text-lg px-8 py-6">
                  <Link href="/#contato">Solicitar Palestra</Link>
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
