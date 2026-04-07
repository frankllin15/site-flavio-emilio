import type { Metadata } from "next";
import Header from "@/components/layout/Header";
import Footer from "@/components/layout/Footer";
import Events from "@/components/sections/Events";
import Container from "@/components/ui/Container";

export const metadata: Metadata = {
  title: "Eventos Realizados",
  description: "Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações em todo o Brasil.",
  openGraph: {
    title: "Eventos Realizados | Flávio Emílio Cavalcanti",
    description: "Palestras, workshops e treinamentos que impactaram milhares de profissionais e organizações.",
  },
};

export default function EventosPage() {
  return (
    <div className="bg-white">
      <Header fixed />
      <main className="min-h-screen bg-gray-50 pt-20">
        {/* Hero Section */}
        <section className="section-spacing bg-white border-b border-gray-200">
          <Container>
            <div className="max-w-4xl mx-auto text-center">
              <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Eventos Realizados
              </h1>
              <p className="text-xl text-gray-600 leading-relaxed">
                Palestras, workshops e treinamentos que impactaram milhares de
                profissionais e organizações em todo o Brasil. Cada evento
                representa um momento de transformação, aprendizado e
                fortalecimento de equipes.
              </p>
            </div>
          </Container>
        </section>

        {/* Events Section - Show all events, hide view all button */}
        <Events showViewAllButton={false} showHeading={false} />
      </main>
      <Footer />
    </div>
  );
}
