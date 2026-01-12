import Container from "@/components/ui/Container";
import Link from "next/link";

export default function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-gray-900 text-white pt-12 pb-8 border-t border-gray-800">
      <Container>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
          {/* Coluna 1: Sobre */}
          <div>
            <h3 className="text-xl font-bold mb-4 text-white">
              Flávio Emílio Cavalcanti
            </h3>
            <p className="text-gray-400">
              Consultor Organizacional, Palestrante e Especialista em Gestão de
              Carreiras. Transformando potencial humano em resultados
              organizacionais.
            </p>
          </div>

          {/* Coluna 2: Links Rápidos */}
          <div className="md:w-1/2">
            <h3 className="text-xl font-bold mb-4 text-white">Links Rápidos</h3>
            <div className="grid grid-cols-2 gap-x-8 gap-y-2">
              <Link
                href="/#sobre"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Sobre
              </Link>
              <Link
                href="/#servicos"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Serviços
              </Link>
              <Link
                href="/#palestras"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Palestras
              </Link>
              <Link
                href="/#eventos"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Eventos
              </Link>
              <Link
                href="/#blog"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Blog
              </Link>
              <Link
                href="/#livros"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Livros
              </Link>
              <Link
                href="/#contato"
                className="text-gray-400 hover:text-accent transition-colors"
              >
                Contato
              </Link>
            </div>
          </div>
        </div>

        {/* Copyright */}
        <div className="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
          <p>
            &copy; {currentYear} Flávio Emílio Cavalcanti. Todos os direitos
            reservados.
          </p>
        </div>
      </Container>
    </footer>
  );
}
