import { blogPosts } from './data/blogPosts';
import type { BlogPost } from '@/types';

/**
 * Abstraction layer for blog posts
 * Currently fetches from static data, but designed to easily switch to DatoCMS
 */

/**
 * Get all blog posts or limit to a specific number
 * @param limit - Optional number of posts to return
 * @returns Array of blog posts
 */
export async function getBlogPosts(limit?: number): Promise<BlogPost[]> {
  // Current: Return static data
  const posts = blogPosts;

  // Future: Fetch from DatoCMS
  // const posts = await fetchFromDatoCMS();

  // Sort by publishedAt descending (newest first)
  const sortedPosts = [...posts].sort((a, b) =>
    new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime()
  );

  return limit ? sortedPosts.slice(0, limit) : sortedPosts;
}

/**
 * Get featured blog posts
 * @param limit - Number of featured posts to return (default: 3)
 * @returns Array of featured blog posts
 */
export async function getFeaturedPosts(limit: number = 3): Promise<BlogPost[]> {
  const posts = await getBlogPosts();
  const featuredPosts = posts.filter(post => post.featured);
  return featuredPosts.slice(0, limit);
}

/**
 * Get a single blog post by slug
 * @param slug - The post slug
 * @returns Blog post or null if not found
 */
export async function getPostBySlug(slug: string): Promise<BlogPost | null> {
  const posts = await getBlogPosts();
  return posts.find(post => post.slug === slug) || null;
}

/**
 * Get posts by category
 * @param category - Category name
 * @param limit - Optional number of posts to return
 * @returns Array of blog posts in the category
 */
export async function getPostsByCategory(
  category: string,
  limit?: number
): Promise<BlogPost[]> {
  const posts = await getBlogPosts();
  const categoryPosts = posts.filter(
    post => post.category.toLowerCase() === category.toLowerCase()
  );
  return limit ? categoryPosts.slice(0, limit) : categoryPosts;
}

// Future DatoCMS implementation (commented out for now)
/*
import { performRequest } from '@/lib/datocms';
import { adaptDatoCMSPost } from '@/types/blog';

async function fetchFromDatoCMS(): Promise<BlogPost[]> {
  const query = `
    query {
      allBlogPosts(orderBy: publishedAt_DESC) {
        id
        slug
        title
        excerpt
        content
        coverImage { url alt width height }
        author { name avatar { url alt } }
        publishedAt
        category { name slug }
        tags
        seo {
          title
          description
          image { url }
        }
        _createdAt
        _updatedAt
      }
    }
  `;

  const { data } = await performRequest({ query });
  return data.allBlogPosts.map(adaptDatoCMSPost);
}
*/
