# WordPress: ambiente local

Port do site para WordPress. Plano completo: [../docs/plano-migracao-wordpress.md](../docs/plano-migracao-wordpress.md).

## Requisitos

- Docker Desktop
- Node 22.18+ (para compilar o tema e exportar o conteúdo do app Next)

## Primeira vez

```bash
cd wordpress
npm install
npm run env      # cria .env a partir do .env.example
npm run up       # sobe banco, WordPress, Mailpit e Adminer
npm run build    # fontes + CSS + JS do tema
npm run setup    # instala WP em pt-BR, plugins e ativa o tema
npm run seed     # exporta lib/data/*.ts e importa no WordPress
```

| O quê | Onde |
|---|---|
| Site | http://localhost:8080 |
| Painel | http://localhost:8080/wp-admin (usuário/senha no `.env`) |
| E-mails enviados (Mailpit) | http://localhost:8025 |
| Banco (Adminer) | http://localhost:8081, servidor `db`, credenciais do `.env` |

## Dia a dia

```bash
npm run up
npm run dev:css   # Tailwind em watch (em outro terminal: npm run dev:js)
npm run wp -- plugin list   # qualquer comando WP-CLI
npm run down
```

- `npm run seed:import`: cria o que falta e não mexe no que já existe.
- `npm run seed:import -- atualizar`: sobrescreve com os dados do seed (apaga edições feitas no painel local).
- `npm run reset`: apaga banco e arquivos do WordPress. Depois, rode `up`, `setup` e `seed` de novo.

## Estrutura

```
docker-compose.yml          db (MariaDB 10.11), wordpress (PHP 8.3), wpcli, mailpit, adminer
bin/setup.sh                instalação/configuração via WP-CLI (pode rodar de novo)
docker/                     arquivos só do ambiente local (SMTP → Mailpit, limites de upload)
seed/export-data.ts         lib/data/*.ts + textos fixos dos componentes → seed/data.json
seed/import.php             data.json → posts, campos, imagens, menu, páginas Início/Blog
wp-content/mu-plugins/      fec-core: tipos de conteúdo, campos (SCF) e ajustes do painel
wp-content/themes/flavio-emilio/
  assets/src/main.css       Tailwind 4 (portado de app/globals.css)
  assets/src/main.js        header/menu (carrossel entra na fase 5)
  assets/dist, assets/fonts gerados por `npm run build` (não versionados)
```

Só `wp-content/themes/flavio-emilio` (com `assets/dist` e `assets/fonts` já gerados) e `wp-content/mu-plugins` vão para a hospedagem.

## Observações

- Depoimentos e posts do blog foram importados como **rascunho**: no app Next eles são exemplos (nomes e empresas genéricos, posts sem texto). Publique pelo painel depois de revisar.
- `public/images/blog/post-4.jpg` não existe no app Next, então esse post ficou sem imagem destacada.
- Os campos são registrados em PHP (`fec-core/fields.php`). Não altere as chaves `field_fec_*` depois que houver conteúdo em produção.
