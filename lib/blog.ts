import { getReader } from '@/lib/keystatic';
import type { BlogPost } from '@/types';

async function getAllPosts(): Promise<BlogPost[]> {
  const reader = getReader();
  const slugs = await reader.collections.posts.list();
  const entries = await Promise.all(
    slugs.map(async (slug) => {
      const data = await reader.collections.posts.read(slug);
      if (!data) return null;
      return {
        id: slug,
        slug,
        title: data.title || '',
        excerpt: data.excerpt || '',
        coverImage: data.coverImage || '',
        author: {
          name: data.authorName || '',
          avatar: data.authorAvatar || undefined,
        },
        publishedAt: data.publishedAt || '',
        category: data.category || '',
        readTime: data.readTime ?? 5,
        featured: data.featured,
      } satisfies BlogPost;
    })
  );
  return entries.filter((p) => p !== null);
}

export async function getBlogPosts(limit?: number): Promise<BlogPost[]> {
  const posts = await getAllPosts();
  const sorted = [...posts].sort(
    (a, b) =>
      new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime()
  );
  return limit ? sorted.slice(0, limit) : sorted;
}

export async function getFeaturedPosts(limit = 3): Promise<BlogPost[]> {
  const posts = await getBlogPosts();
  return posts.filter((p) => p.featured).slice(0, limit);
}

export async function getPostBySlug(
  slug: string
): Promise<BlogPost | null> {
  const posts = await getBlogPosts();
  return posts.find((p) => p.slug === slug) || null;
}

export async function getPostsByCategory(
  category: string,
  limit?: number
): Promise<BlogPost[]> {
  const posts = await getBlogPosts();
  const filtered = posts.filter(
    (p) => p.category.toLowerCase() === category.toLowerCase()
  );
  return limit ? filtered.slice(0, limit) : filtered;
}

export async function getPostWithContent(slug: string) {
  const reader = getReader();
  const data = await reader.collections.posts.read(slug, {
    resolveLinkedFiles: true,
  });
  if (!data) return null;

  return {
    post: {
      id: slug,
      slug,
      title: data.title || '',
      excerpt: data.excerpt || '',
      coverImage: data.coverImage || '',
      author: {
        name: data.authorName || '',
        avatar: data.authorAvatar || undefined,
      },
      publishedAt: data.publishedAt || '',
      category: data.category || '',
      readTime: data.readTime ?? 5,
      featured: data.featured,
    } satisfies BlogPost,
    content: data.content,
  };
}
