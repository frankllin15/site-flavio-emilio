import Container from '@/components/ui/Container';

export default function Welcome() {
  return (
    <section className="py-16 md:py-20 bg-white">
      <Container>
        <div className="max-w-4xl mx-auto text-center">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
            Seja bem-vindo!
          </h2>
          <p className="text-lg md:text-xl text-gray-600 leading-relaxed">
            Este é um espaço dedicado ao desenvolvimento humano e organizacional.
            Aqui você encontrará soluções práticas e estratégicas para transformar
            pessoas, equipes e resultados.
          </p>
        </div>
      </Container>
    </section>
  );
}
