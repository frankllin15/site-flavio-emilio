import Container from '@/components/ui/Container';

export default function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-gray-900 text-white py-12">
      <Container>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* Coluna 1: Sobre */}
          <div>
            <h3 className="text-xl font-bold mb-4 text-white">Flávio Emílio Cavalcanti</h3>
            <p className="text-gray-400 mb-4">
              Consultor Organizacional, Palestrante e Especialista em Gestão de Carreiras.
              Transformando potencial humano em resultados organizacionais.
            </p>
          </div>

          {/* Coluna 2: Links Rápidos */}
          <div>
            <h3 className="text-xl font-bold mb-4 text-white">Links Rápidos</h3>
            <ul className="space-y-2">
              <li>
                <a href="#sobre" className="text-gray-400 hover:text-primary transition-colors">
                  Sobre
                </a>
              </li>
              <li>
                <a href="#solucoes" className="text-gray-400 hover:text-primary transition-colors">
                  Soluções
                </a>
              </li>
              <li>
                <a href="#eventos" className="text-gray-400 hover:text-primary transition-colors">
                  Eventos
                </a>
              </li>
              <li>
                <a href="#blog" className="text-gray-400 hover:text-primary transition-colors">
                  Blog
                </a>
              </li>
              <li>
                <a href="#livros" className="text-gray-400 hover:text-primary transition-colors">
                  Livros
                </a>
              </li>
              <li>
                <a href="#contato" className="text-gray-400 hover:text-primary transition-colors">
                  Contato
                </a>
              </li>
            </ul>
          </div>

          {/* Coluna 3: Contato */}
          <div>
            <h3 className="text-xl font-bold mb-4 text-white">Contato</h3>
            <ul className="space-y-2 text-gray-400">
              <li>
                <a href="mailto:contato@flavioemilio.com.br" className="hover:text-primary transition-colors">
                  contato@flavioemilio.com.br
                </a>
              </li>
              <li>
                <a href="tel:+5584999999999" className="hover:text-primary transition-colors">
                  (84) 99999-9999
                </a>
              </li>
            </ul>

            {/* Redes Sociais */}
            <div className="flex space-x-4 mt-6">
              <a
                href="#"
                className="text-gray-400 hover:text-primary transition-colors"
                aria-label="LinkedIn"
              >
                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                </svg>
              </a>
              <a
                href="#"
                className="text-gray-400 hover:text-primary transition-colors"
                aria-label="Instagram"
              >
                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>
            </div>
          </div>
        </div>

        {/* Copyright */}
        <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>
            &copy; {currentYear} Flávio Emílio Cavalcanti. Todos os direitos reservados.
          </p>
        </div>
      </Container>
    </footer>
  );
}
