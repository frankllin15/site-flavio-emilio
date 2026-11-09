# Site Flávio Emílio Cavalcanti

Site de Flávio Emílio Cavalcanti, consultor organizacional, palestrante e especialista em gestão de carreiras, com o blog **Drops de Carreira**. Feito em WordPress (tema próprio `flavio-emilio` + mu-plugin `fec-core`).

- **Ambiente local, build e produção:** [wordpress/README.md](wordpress/README.md)
- **Plano da migração para WordPress:** [docs/plano-migracao-wordpress.md](docs/plano-migracao-wordpress.md)
- **Plano do blog:** [docs/plano-blog.md](docs/plano-blog.md)

A versão anterior em Next.js está na branch `legacy-nextjs`.

## Começo rápido

Requisitos: Docker Desktop e Node 22+.

```bash
cd wordpress
npm install
npm run env
npm run up
npm run build
npm run setup
npm run seed
```

Site em http://localhost:8080, e-mails em http://localhost:8025.
