import Link from 'next/link';
import Container from '@/components/ui/Container';
import { Button } from '@/components/ui/Button';

export default function NotFound() {
  return (
    <main className="min-h-screen bg-white flex items-center justify-center">
      <Container>
        <div className="text-center max-w-2xl mx-auto py-16">
          <h1 className="text-6xl font-bold text-gray-900 mb-4">404</h1>
          <h2 className="text-3xl font-semibold text-gray-700 mb-6">
            Serviço não encontrado
          </h2>
          <p className="text-lg text-gray-600 mb-8">
            O serviço que você está procurando não existe ou foi removido.
          </p>
          <Button asChild size="lg">
            <Link href="/#servicos">
              Ver Todos os Serviços
            </Link>
          </Button>
        </div>
      </Container>
    </main>
  );
}
