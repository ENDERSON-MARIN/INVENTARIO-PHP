#!/bin/bash

set -e

echo "🚀 Iniciando aplicação Laravel..."

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL..."
until php artisan migrate:status 2>/dev/null; do
    echo "MySQL não está pronto - aguardando..."
    sleep 2
done

echo "✅ MySQL conectado!"

# Criar diretórios necessários
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Ajustar permissões
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Verificar se precisa executar migrations
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "🗄️  Executando migrations..."
    php artisan migrate --force
fi

# Gerar caches se em produção
if [ "${APP_ENV}" = "production" ]; then
    echo "⚡ Gerando caches de produção..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "✨ Aplicação pronta!"

# Iniciar supervisor (que gerencia PHP-FPM e Nginx)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
