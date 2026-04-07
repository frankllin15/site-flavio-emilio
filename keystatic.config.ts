import { config, collection, fields } from '@keystatic/core';

const isGithubMode = !!process.env.NEXT_PUBLIC_KEYSTATIC_GITHUB_APP_SLUG;

export default config({
  storage: isGithubMode
    ? {
        kind: 'github',
        repo: 'frankllin15/site-flavio-emilio',
      }
    : { kind: 'local' },

  collections: {
    events: collection({
      label: 'Eventos',
      slugField: 'title',
      path: 'content/events/*',
      format: { data: 'yaml' },
      schema: {
        title: fields.slug({ name: { label: 'Título' } }),
        description: fields.text({ label: 'Descrição', multiline: true }),
        location: fields.text({ label: 'Local' }),
        date: fields.text({
          label: 'Data de exibição',
          description: 'Ex: Novembro 2025',
        }),
        sortDate: fields.date({ label: 'Data para ordenação' }),
        image: fields.image({
          label: 'Imagem',
          directory: 'public/images/events',
          publicPath: '/images/events/',
        }),
      },
    }),

    posts: collection({
      label: 'Blog Posts',
      slugField: 'title',
      path: 'content/posts/*',
      format: { contentField: 'content' },
      schema: {
        title: fields.slug({ name: { label: 'Título' } }),
        excerpt: fields.text({ label: 'Resumo', multiline: true }),
        content: fields.markdoc({
          label: 'Conteúdo',
          options: {
            image: {
              directory: 'public/images/blog/content',
              publicPath: '/images/blog/content/',
            },
          },
        }),
        coverImage: fields.image({
          label: 'Imagem de capa',
          directory: 'public/images/blog',
          publicPath: '/images/blog/',
        }),
        authorName: fields.text({
          label: 'Nome do autor',
          defaultValue: 'Flávio Emílio Cavalcanti',
        }),
        authorAvatar: fields.text({
          label: 'Avatar do autor',
          defaultValue: '/images/flavio-palco.jpg',
        }),
        publishedAt: fields.date({ label: 'Data de publicação' }),
        category: fields.text({ label: 'Categoria' }),
        readTime: fields.integer({
          label: 'Tempo de leitura (min)',
          defaultValue: 5,
        }),
        featured: fields.checkbox({
          label: 'Destaque',
          defaultValue: false,
        }),
      },
    }),
  },
});
