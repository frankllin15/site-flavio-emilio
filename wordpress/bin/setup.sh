#!/bin/sh
# Instala e configura o WordPress local. Roda dentro do container wpcli; pode ser executado de novo.
set -eu

echo "> Aguardando os arquivos do WordPress (container wordpress)..."
i=0
until [ -f /var/www/html/wp-config.php ]; do
  i=$((i + 1))
  if [ "$i" -gt 60 ]; then
    echo "wp-config.php não apareceu. O container 'wordpress' está rodando? (npm run up)" >&2
    exit 1
  fi
  sleep 2
done

if ! wp core is-installed 2>/dev/null; then
  echo "> Instalando o WordPress"
  wp core install \
    --url="$WP_URL" \
    --title="$WP_TITLE" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email
fi

echo "> Idioma e ajustes gerais"
wp language core install pt_BR --activate || true
wp option update timezone_string 'America/Sao_Paulo'
wp option update date_format 'j \d\e F \d\e Y'
wp option update blogdescription 'Consultor Organizacional, Palestrante e Especialista em Gestão de Carreiras'
wp option update default_comment_status closed
wp option update default_ping_status closed
wp rewrite structure '/%postname%/'

echo "> Plugins"
wp plugin delete hello akismet 2>/dev/null || true
wp plugin install secure-custom-fields contact-form-7 flamingo wordpress-seo mailpoet --activate
wp language plugin install --all pt_BR || true

echo "> Tema"
wp theme activate flavio-emilio

wp rewrite flush
echo "> Pronto: $WP_URL  (painel: $WP_URL/wp-admin)"
