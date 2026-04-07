import type { Metadata } from 'next';
import Image from 'next/image';
import Link from 'next/link';
import Container from '@/components/ui/Container';
import Header from '@/components/layout/Header';
import Footer from '@/components/layout/Footer';
import { getBlogPosts } from '@/lib/blog';
import { Clock, ArrowRight } from 'lucide-react';

export const metadata: Metadata = {
  title: 'Blog | Flávio Emílio Cavalcanti',
  description:
    'Insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional.',
  openGraph: {
    title: 'Blog | Flávio Emílio Cavalcanti',
    description:
      'Insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional.',
  },
};

export default async function BlogPage() {
  const posts = await getBlogPosts();

  const [featuredPost, ...remainingPosts] = posts;

  return (
    <div className="bg-white">
      <Header fixed />
      <main className="min-h-screen pt-20">
        {/* Hero */}
        <section className="section-spacing bg-white border-b border-gray-200">
          <Container>
            <div className="max-w-4xl mx-auto text-center">
              <span className="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest text-brand-blue-500 bg-brand-blue-50 rounded-full mb-6">
                Blog
              </span>
              <h1 className="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Insights & Reflexões
              </h1>
              <p className="text-xl text-gray-600 leading-relaxed">
                Artigos sobre liderança, gestão de pessoas e desenvolvimento
                profissional para transformar sua carreira e organização.
              </p>
            </div>
          </Container>
        </section>

        {/* Featured post */}
        {featuredPost && (
          <section className="section-spacing bg-gray-50">
            <Container>
              <Link
                href={`/blog/${featuredPost.slug}`}
                className="group block"
              >
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                  {/* Image */}
                  <div className="relative aspect-[16/10] rounded-2xl overflow-hidden bg-gray-100 shadow-lg">
                    {featuredPost.coverImage ? (
                      <Image
                        src={featuredPost.coverImage}
                        alt={featuredPost.title}
                        fill
                        priority
                        className="object-cover transition-transform duration-500 group-hover:scale-105"
                        sizes="(max-width: 1024px) 100vw, 50vw"
                      />
                    ) : (
                      <div className="w-full h-full bg-gradient-to-br from-brand-blue-200 to-brand-blue-500 flex items-center justify-center">
                        <span className="text-white text-4xl font-bold">
                          {featuredPost.category
                            .substring(0, 2)
                            .toUpperCase()}
                        </span>
                      </div>
                    )}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                  </div>

                  {/* Content */}
                  <div>
                    <div className="flex items-center gap-3 mb-4">
                      <span className="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest text-accent-gold-600 bg-accent-gold-100 rounded-full">
                        Destaque
                      </span>
                      <span className="text-xs font-semibold text-brand-blue-500 uppercase tracking-wider">
                        {featuredPost.category}
                      </span>
                    </div>

                    <h2 className="font-display text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 group-hover:text-brand-blue-500 transition-colors mb-4 leading-tight">
                      {featuredPost.title}
                    </h2>

                    <p className="text-gray-600 text-lg leading-relaxed mb-6 line-clamp-3">
                      {featuredPost.excerpt}
                    </p>

                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-3">
                        <div className="relative w-10 h-10 rounded-full overflow-hidden ring-2 ring-gray-200">
                          {featuredPost.author.avatar ? (
                            <Image
                              src={featuredPost.author.avatar}
                              alt={featuredPost.author.name}
                              fill
                              className="object-cover"
                              sizes="40px"
                            />
                          ) : (
                            <div className="w-full h-full bg-brand-blue-500 flex items-center justify-center">
                              <span className="text-white text-xs font-bold">
                                FE
                              </span>
                            </div>
                          )}
                        </div>
                        <div>
                          <p className="text-sm font-medium text-gray-900">
                            {featuredPost.author.name}
                          </p>
                          <p className="text-xs text-gray-500">
                            {new Date(
                              featuredPost.publishedAt
                            ).toLocaleDateString('pt-BR', {
                              day: '2-digit',
                              month: 'short',
                              year: 'numeric',
                            })}
                          </p>
                        </div>
                      </div>

                      <span className="inline-flex items-center gap-2 text-sm font-semibold text-brand-blue-500 group-hover:gap-3 transition-all">
                        Ler artigo
                        <ArrowRight className="w-4 h-4" />
                      </span>
                    </div>
                  </div>
                </div>
              </Link>
            </Container>
          </section>
        )}

        {/* Posts grid */}
        {remainingPosts.length > 0 && (
          <section className="section-spacing">
            <Container>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {remainingPosts.map((post) => (
                  <Link
                    key={post.slug}
                    href={`/blog/${post.slug}`}
                    className="group block"
                  >
                    <div className="bg-white rounded-xl overflow-hidden border border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 h-full flex flex-col">
                      {/* Image */}
                      <div className="relative aspect-[16/10] overflow-hidden bg-gray-100">
                        {post.coverImage ? (
                          <Image
                            src={post.coverImage}
                            alt={post.title}
                            fill
                            className="object-cover transition-transform duration-500 group-hover:scale-105"
                            sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                          />
                        ) : (
                          <div className="w-full h-full bg-gradient-to-br from-brand-blue-200 to-brand-blue-400 flex items-center justify-center">
                            <span className="text-white text-2xl font-bold">
                              {post.category
                                .substring(0, 2)
                                .toUpperCase()}
                            </span>
                          </div>
                        )}
                        <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                      </div>

                      {/* Content */}
                      <div className="p-6 flex flex-col flex-1">
                        <div className="flex items-center justify-between mb-3">
                          <span className="text-xs font-semibold text-brand-blue-500 uppercase tracking-wider">
                            {post.category}
                          </span>
                          <div className="flex items-center gap-1 text-xs text-gray-400">
                            <Clock className="w-3.5 h-3.5" />
                            <span>{post.readTime} min</span>
                          </div>
                        </div>

                        <h3 className="font-display text-lg font-bold text-gray-900 group-hover:text-brand-blue-500 transition-colors leading-snug mb-3 line-clamp-2">
                          {post.title}
                        </h3>

                        <p className="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">
                          {post.excerpt}
                        </p>

                        <div className="flex items-center justify-between pt-4 border-t border-gray-100">
                          <span className="text-xs text-gray-500">
                            {new Date(post.publishedAt).toLocaleDateString(
                              'pt-BR',
                              {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                              }
                            )}
                          </span>
                          <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-blue-500 group-hover:gap-2.5 transition-all">
                            Ler
                            <ArrowRight className="w-3.5 h-3.5" />
                          </span>
                        </div>
                      </div>
                    </div>
                  </Link>
                ))}
              </div>
            </Container>
          </section>
        )}
      </main>
      <Footer />
    </div>
  );
}
