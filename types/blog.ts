// Blog post type for internal application use
export interface BlogPost {
  id: string;
  slug: string;
  title: string;
  excerpt: string;
  content?: string; // Full content for detail pages
  coverImage: string;
  author: {
    name: string;
    avatar?: string;
  };
  publishedAt: string; // ISO date string
  category: string;
  readTime: number; // in minutes
  featured?: boolean;
}

// DatoCMS blog post structure (mirrors future GraphQL schema)
export interface DatoCMSBlogPost {
  id: string;
  slug: string;
  title: string;
  excerpt: string;
  content: string; // Rich text from DatoCMS
  coverImage: {
    url: string;
    alt: string;
    width: number;
    height: number;
  };
  author: {
    name: string;
    avatar?: {
      url: string;
      alt: string;
    };
  };
  publishedAt: string;
  category: {
    name: string;
    slug: string;
  };
  tags?: string[];
  seo?: {
    title: string;
    description: string;
    image?: {
      url: string;
    };
  };
  _createdAt: string;
  _updatedAt: string;
}

// Adapter function to convert DatoCMS data to app BlogPost type
export function adaptDatoCMSPost(post: DatoCMSBlogPost): BlogPost {
  return {
    id: post.id,
    slug: post.slug,
    title: post.title,
    excerpt: post.excerpt,
    content: post.content,
    coverImage: post.coverImage.url,
    author: {
      name: post.author.name,
      avatar: post.author.avatar?.url,
    },
    publishedAt: post.publishedAt,
    category: post.category.name,
    readTime: estimateReadTime(post.content),
    featured: false, // Could be a field in DatoCMS
  };
}

// Helper function to estimate reading time
function estimateReadTime(content: string): number {
  const wordsPerMinute = 200;
  const wordCount = content.split(/\s+/).length;
  return Math.ceil(wordCount / wordsPerMinute);
}
