import { notFound } from 'next/navigation';
import type { Metadata } from 'next';
import Image from 'next/image';
import Link from 'next/link';
import MarkdocRenderer from '@/components/client/MarkdocRenderer';
import Container from '@/components/ui/Container';
import { Button } from '@/components/ui/Button';
import Header from '@/components/layout/Header';
import Footer from '@/components/layout/Footer';
import { getPostWithContent, getBlogPosts } from '@/lib/blog';
import {
  ArrowLeft,
  Clock,
  Calendar,
  Tag,
  ChevronRight,
} from 'lucide-react';

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const result = await getPostWithContent(slug);

  if (!result) {
    return { title: 'Post não encontrado' };
  }

  const { post } = result;
  return {
    title: `${post.title} | Blog - Flávio Emílio Cavalcanti`,
    description: post.excerpt,
    openGraph: {
      title: post.title,
      description: post.excerpt,
      type: 'article',
      publishedTime: post.publishedAt,
      authors: [post.author.name],
      images: post.coverImage ? [{ url: post.coverImage }] : undefined,
    },
  };
}

export default async function BlogPostPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const result = await getPostWithContent(slug);

  if (!result) {
    notFound();
  }

  const { post, content } = result;
  const allPosts = await getBlogPosts();
  const relatedPosts = allPosts
    .filter((p) => p.slug !== slug)
    .slice(0, 3);

  const publishDate = new Date(post.publishedAt).toLocaleDateString('pt-BR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  });

  return (
    <div className="bg-white">
      <Header fixed />
      <main className="min-h-screen pt-20">
        {/* Hero — full-width cover image with overlay */}
        <section className="relative">
          {post.coverImage ? (
            <div className="relative h-[50vh] min-h-[400px] max-h-[560px] w-full overflow-hidden">
              <Image
                src={post.coverImage}
                alt={post.title}
                fill
                priority
                className="object-cover"
                sizes="100vw"
              />
              {/* Gradient overlay */}
              <div className="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-gray-900/20" />

              {/* Content on top of image */}
              <div className="absolute inset-0 flex items-end">
                <Container className="pb-12 md:pb-16 relative z-10">
                  <div className="max-w-3xl">
                    {/* Breadcrumb */}
                    <nav className="flex items-center gap-2 text-sm text-white/70 mb-6">
                      <Link
                        href="/"
                        className="hover:text-white transition-colors"
                      >
                        Home
                      </Link>
                      <ChevronRight className="w-3.5 h-3.5" />
                      <Link
                        href="/blog"
                        className="hover:text-white transition-colors"
                      >
                        Blog
                      </Link>
                      <ChevronRight className="w-3.5 h-3.5" />
                      <span className="text-white/50 truncate max-w-[200px]">
                        {post.title}
                      </span>
                    </nav>

                    {/* Category badge */}
                    <span className="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest text-accent-gold-400 bg-accent-gold-500/15 border border-accent-gold-500/30 rounded-full mb-5">
                      {post.category}
                    </span>

                    {/* Title */}
                    <h1 className="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight mb-6">
                      {post.title}
                    </h1>

                    {/* Meta row */}
                    <div className="flex flex-wrap items-center gap-5 text-sm text-white/70">
                      {/* Author */}
                      <div className="flex items-center gap-3">
                        <div className="relative w-10 h-10 rounded-full overflow-hidden ring-2 ring-white/20">
                          {post.author.avatar ? (
                            <Image
                              src={post.author.avatar}
                              alt={post.author.name}
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
                        <span className="font-medium text-white">
                          {post.author.name}
                        </span>
                      </div>

                      <span className="w-px h-4 bg-white/20" />

                      <div className="flex items-center gap-1.5">
                        <Calendar className="w-4 h-4" />
                        <span>{publishDate}</span>
                      </div>

                      <span className="w-px h-4 bg-white/20" />

                      <div className="flex items-center gap-1.5">
                        <Clock className="w-4 h-4" />
                        <span>{post.readTime} min de leitura</span>
                      </div>
                    </div>
                  </div>
                </Container>
              </div>
            </div>
          ) : (
            /* Fallback: no cover image */
            <div className="bg-gradient-to-br from-brand-blue-600 via-brand-blue-500 to-brand-blue-800 py-20 md:py-28">
              <Container>
                <div className="max-w-3xl">
                  <nav className="flex items-center gap-2 text-sm text-white/70 mb-6">
                    <Link
                      href="/"
                      className="hover:text-white transition-colors"
                    >
                      Home
                    </Link>
                    <ChevronRight className="w-3.5 h-3.5" />
                    <Link
                      href="/blog"
                      className="hover:text-white transition-colors"
                    >
                      Blog
                    </Link>
                  </nav>

                  <span className="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest text-accent-gold-300 bg-accent-gold-500/15 border border-accent-gold-400/30 rounded-full mb-5">
                    {post.category}
                  </span>

                  <h1 className="font-display text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight mb-6">
                    {post.title}
                  </h1>

                  <div className="flex flex-wrap items-center gap-5 text-sm text-white/70">
                    <div className="flex items-center gap-3">
                      <div className="relative w-10 h-10 rounded-full overflow-hidden ring-2 ring-white/20">
                        {post.author.avatar ? (
                          <Image
                            src={post.author.avatar}
                            alt={post.author.name}
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
                      <span className="font-medium text-white">
                        {post.author.name}
                      </span>
                    </div>
                    <span className="w-px h-4 bg-white/20" />
                    <div className="flex items-center gap-1.5">
                      <Calendar className="w-4 h-4" />
                      <span>{publishDate}</span>
                    </div>
                    <span className="w-px h-4 bg-white/20" />
                    <div className="flex items-center gap-1.5">
                      <Clock className="w-4 h-4" />
                      <span>{post.readTime} min de leitura</span>
                    </div>
                  </div>
                </div>
              </Container>
            </div>
          )}
        </section>

        {/* Article body */}
        <article className="section-spacing">
          <Container>
            <div className="max-w-3xl mx-auto">
              {/* Lead / excerpt */}
              <p className="text-xl md:text-2xl text-gray-600 leading-relaxed font-light border-l-4 border-accent-gold-400 pl-6 mb-12">
                {post.excerpt}
              </p>

              {/* Markdoc content */}
              <div className="blog-content">
                <MarkdocRenderer content={content} />
              </div>

              {/* Divider */}
              <div className="my-16 flex items-center gap-4">
                <div className="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent" />
                <div className="w-2 h-2 bg-accent-gold-400 rounded-full" />
                <div className="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent" />
              </div>

              {/* Author card */}
              <div className="bg-gray-50 rounded-2xl p-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <div className="relative w-20 h-20 rounded-full overflow-hidden ring-4 ring-white shadow-md flex-shrink-0">
                  {post.author.avatar ? (
                    <Image
                      src={post.author.avatar}
                      alt={post.author.name}
                      fill
                      className="object-cover"
                      sizes="80px"
                    />
                  ) : (
                    <div className="w-full h-full bg-brand-blue-500 flex items-center justify-center">
                      <span className="text-white text-xl font-bold">FE</span>
                    </div>
                  )}
                </div>
                <div className="text-center sm:text-left">
                  <p className="text-sm font-semibold text-brand-blue-500 uppercase tracking-wider mb-1">
                    Sobre o Autor
                  </p>
                  <h3 className="text-xl font-bold text-gray-900 mb-2">
                    {post.author.name}
                  </h3>
                  <p className="text-gray-600 leading-relaxed">
                    Consultor Organizacional especializado em Gestão de Pessoas,
                    Liderança e Gestão de Carreiras. Com décadas de experiência
                    transformando organizações e desenvolvendo líderes em todo o
                    Brasil.
                  </p>
                </div>
              </div>

              {/* Back + CTA */}
              <div className="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4">
                <Link
                  href="/blog"
                  className="inline-flex items-center gap-2 text-gray-600 hover:text-brand-blue-500 transition-colors font-medium"
                >
                  <ArrowLeft className="w-4 h-4" />
                  Voltar ao Blog
                </Link>
                <Button asChild>
                  <Link href="/#contato">Fale com o Flávio</Link>
                </Button>
              </div>
            </div>
          </Container>
        </article>

        {/* Related posts */}
        {relatedPosts.length > 0 && (
          <section className="section-spacing bg-gray-50 border-t border-gray-200">
            <Container>
              <div className="text-center mb-12">
                <span className="inline-block px-3 py-1 text-xs font-bold uppercase tracking-widest text-brand-blue-500 bg-brand-blue-50 rounded-full mb-4">
                  Continue lendo
                </span>
                <h2 className="font-display text-3xl md:text-4xl font-bold text-gray-900">
                  Artigos Relacionados
                </h2>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {relatedPosts.map((related) => (
                  <Link
                    key={related.slug}
                    href={`/blog/${related.slug}`}
                    className="group block"
                  >
                    <div className="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                      {/* Image */}
                      <div className="relative aspect-[16/10] overflow-hidden bg-gray-100">
                        {related.coverImage ? (
                          <Image
                            src={related.coverImage}
                            alt={related.title}
                            fill
                            className="object-cover transition-transform duration-500 group-hover:scale-105"
                            sizes="(max-width: 768px) 100vw, 33vw"
                          />
                        ) : (
                          <div className="w-full h-full bg-gradient-to-br from-brand-blue-200 to-brand-blue-400 flex items-center justify-center">
                            <span className="text-white text-2xl font-bold">
                              {related.category
                                .substring(0, 2)
                                .toUpperCase()}
                            </span>
                          </div>
                        )}
                        <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                      </div>

                      {/* Content */}
                      <div className="p-5">
                        <div className="flex items-center justify-between mb-3">
                          <span className="text-xs font-semibold text-brand-blue-500 uppercase tracking-wider">
                            {related.category}
                          </span>
                          <span className="text-xs text-gray-400">
                            {related.readTime} min
                          </span>
                        </div>
                        <h3 className="font-display font-bold text-gray-900 group-hover:text-brand-blue-500 transition-colors leading-snug line-clamp-2">
                          {related.title}
                        </h3>
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
