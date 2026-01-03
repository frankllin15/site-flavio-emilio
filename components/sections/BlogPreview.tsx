import Container from '@/components/ui/Container';
import SectionHeading from '@/components/ui/SectionHeading';
import Card, { CardHeader, CardTitle, CardContent } from '@/components/ui/Card';
import { getBlogPosts } from '@/lib/blog';
import Image from 'next/image';
import { Button } from '../ui/Button';
import Link from 'next/link';

export default async function BlogPreview() {
  const posts = await getBlogPosts(3);

  return (
    <section id="blog" className="section-spacing bg-gray-50">
      <Container>
        <SectionHeading
          title="Blog Posts"
          subtitle="Insights práticos sobre liderança, gestão de pessoas e desenvolvimento profissional"
        />

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {posts.map((post) => (
            <Card key={post.id} hover>
              {/* Cover Image */}
              {
                post.coverImage ? (
                  <Image 
                    src={post.coverImage} 
                    alt={post.title} 
                    width={400} 
                    height={225} 
                    className="w-full h-auto rounded-lg mb-4 object-cover"
                  />
                ) :
              
              <div className="aspect-video bg-gradient-to-br from-brand-blue-200 to-brand-blue-400 rounded-lg mb-4 flex items-center justify-center">
                <span className="text-white text-2xl font-bold">
                  {post.category.substring(0, 2).toUpperCase()}
                </span>
              </div>}
              <CardHeader>
                {/* Category & Read Time */}
                <div className="flex items-center justify-between mb-3">
                  <span className="text-xs font-semibold text-primary uppercase tracking-wider">
                    {post.category}
                  </span>
                  <span className="text-xs text-gray-500">
                    {post.readTime} min de leitura
                  </span>
                </div>

                <CardTitle className="text-xl text-foreground">{post.title}</CardTitle>
              </CardHeader>

              <CardContent>
                <p className="text-gray-600 text-sm mb-4 line-clamp-3">
                  {post.excerpt}
                </p>

                {/* Author & Date */}
                <div className="flex items-center justify-between pt-4 border-t border-gray-200">
                  <div className="flex items-center space-x-2">
                    <div className="w-8 h-8 rounded-full bg-brand-blue-500 flex items-center justify-center">
                      <span className="text-white text-xs font-bold">FE</span>
                    </div>
                    <span className="text-sm text-gray-700">{post.author.name}</span>
                  </div>
                  <span className="text-xs text-gray-500">
                    {new Date(post.publishedAt).toLocaleDateString('pt-BR', {
                      day: '2-digit',
                      month: 'short',
                      year: 'numeric'
                    })}
                  </span>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* CTA */}
        <div className="text-center mt-12">
          <Button variant="secondary" asChild>
          <Link href="#contato">
            Ver Todos os Artigos
          </Link>
          </Button>
        </div>
      </Container>
    </section>
  );
}
