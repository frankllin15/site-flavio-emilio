import Image from 'next/image';
import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import { books } from '@/lib/data/books';

export default function Books() {
  return (
    <section id="livros" className="section-spacing bg-gradient-to-br from-gray-50 to-white">
      <Container>
        <SectionHeading
          title="Livros"
          subtitle="Conhecimento prático e inspiração para profissionais e organizações"
        />

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
          {books.map((book) => (
            <div key={book.id} className="group">
              {/* Book Cover */}
              <div className="relative mb-6 overflow-hidden rounded-lg shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                <div className="aspect-[2/3] relative bg-gray-100">
                  <Image
                    src={book.coverImage}
                    alt={`Capa do livro ${book.title}`}
                    fill
                    className="object-cover"
                    sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                  />
                </div>
              </div>

              {/* Book Info */}
              <div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">{book.title}</h3>
                {book.subtitle && (
                  <p className="text-sm text-gray-500 mb-3">{book.subtitle}</p>
                )}
                <p className="text-gray-600 mb-4 text-sm">{book.description}</p>

                {/* Highlights */}
                <ul className="space-y-1 mb-4">
                  {book.highlights.slice(0, 3).map((highlight, index) => (
                    <li key={index} className="flex items-start text-sm">
                      <svg
                        className="w-4 h-4 text-accent-gold-500 mr-2 mt-0.5 flex-shrink-0"
                        fill="none"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path d="M5 13l4 4L19 7"></path>
                      </svg>
                      <span className="text-gray-700">{highlight}</span>
                    </li>
                  ))}
                </ul>

                {/* Purchase Links */}
                <div className="flex gap-3">
                  <a
                    href={book.purchaseUrl}
                    className="btn-primary text-sm py-2 px-4 flex-1 text-center"
                  >
                    Adquirir
                  </a>
                  {/* {book.amazonUrl && (
                    <a
                      href={book.amazonUrl}
                      className="btn-secondary text-sm py-2 px-4"
                      aria-label="Ver na Amazon"
                    >
                      Amazon
                    </a>
                  )} */}
                </div>
              </div>
            </div>
          ))}
        </div>
      </Container>
    </section>
  );
}
