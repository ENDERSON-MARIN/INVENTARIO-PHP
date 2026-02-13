---
inclusion: manual
---

# Otimizações para Produção - Debian 13

## 1. Configuração do Ambiente (.env)

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

# Cache e Performance
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Logs otimizados
LOG_CHANNEL=daily
LOG_LEVEL=error

# Session segura
SESSION_SECURE_COOKIE=true
SESSION_LIFETIME=120
```

## 2. Instalação de Dependências no Debian 13

```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# PHP 7.4+ e extensões necessárias
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-redis \
    php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd \
    php8.2-intl php8.2-bcmath php8.2-opcache

# Redis para cache
sudo apt install -y redis-server

# Nginx (recomendado) ou Apache
sudo apt install -y nginx

# Supervisor para queues
sudo apt install -y supervisor

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## 3. Otimizações do PHP (php.ini)

Editar `/etc/php/8.2/fpm/php.ini`:

```ini
; OPcache - Cache de bytecode
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.enable_cli=0

; Performance
memory_limit=512M
max_execution_time=60
upload_max_filesize=20M
post_max_size=20M

; Realpath cache
realpath_cache_size=4096K
realpath_cache_ttl=600
```

## 4. Configuração do Redis

Editar `/etc/redis/redis.conf`:

```conf
maxmemory 256mb
maxmemory-policy allkeys-lru
save ""
appendonly no
```

Reiniciar Redis:

```bash
sudo systemctl restart redis-server
sudo systemctl enable redis-server
```

## 5. Comandos de Otimização Laravel

```bash
# Instalar dependências de produção
composer install --optimize-autoloader --no-dev

# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimizar autoloader
composer dump-autoload --optimize --classmap-authoritative

# Limpar caches de desenvolvimento
php artisan cache:clear
php artisan view:clear
```

## 6. Configuração do Nginx

Criar `/etc/nginx/sites-available/seu-app`:

```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    root /var/www/seu-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript
               application/x-javascript application/xml+rss
               application/json application/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;

        # Buffers para performance
        fastcgi_buffer_size 128k;
        fastcgi_buffers 256 16k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Ativar site:

```bash
sudo ln -s /etc/nginx/sites-available/seu-app /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 7. PHP-FPM Pool Configuration

Editar `/etc/php/8.2/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500

; Status page para monitoramento
pm.status_path = /status
```

Reiniciar PHP-FPM:

```bash
sudo systemctl restart php8.2-fpm
```

## 8. Configuração de Queues com Supervisor

Criar `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/seu-app/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/seu-app/storage/logs/worker.log
stopwaitsecs=3600
```

Ativar:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## 9. Cron Jobs

Adicionar ao crontab (`sudo crontab -e -u www-data`):

```cron
* * * * * cd /var/www/seu-app && php artisan schedule:run >> /dev/null 2>&1
```

## 10. Otimizações do MySQL

Editar `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
# InnoDB optimizations
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT

# Query cache (se MySQL < 8.0)
query_cache_type = 1
query_cache_size = 64M
query_cache_limit = 2M

# Connections
max_connections = 200
```

## 11. Permissões de Arquivos

```bash
cd /var/www/seu-app

# Proprietário correto
sudo chown -R www-data:www-data .

# Permissões
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

# Storage e cache precisam ser graváveis
sudo chmod -R 775 storage bootstrap/cache
```

## 12. Segurança Adicional

```bash
# Desabilitar Debugbar em produção
# Já está configurado para não carregar em produção via APP_DEBUG=false

# Remover arquivos desnecessários
rm -rf tests/ .env.example README.md

# Proteger .env
chmod 600 .env
```

## 13. Monitoramento e Logs

```bash
# Rotação de logs Laravel
# Criar /etc/logrotate.d/laravel:

/var/www/seu-app/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

## 14. Script de Deploy

Criar `deploy.sh`:

```bash
#!/bin/bash
set -e

echo "🚀 Iniciando deploy..."

# Modo manutenção
php artisan down

# Atualizar código
git pull origin main

# Dependências
composer install --optimize-autoloader --no-dev

# Migrations
php artisan migrate --force

# Limpar e recriar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
composer dump-autoload --optimize --classmap-authoritative

# Reiniciar services
sudo systemctl reload php8.2-fpm
sudo supervisorctl restart laravel-worker:*

# Sair do modo manutenção
php artisan up

echo "✅ Deploy concluído!"
```

## 15. Checklist Final

- [ ] APP_DEBUG=false no .env
- [ ] APP_ENV=production no .env
- [ ] Redis instalado e configurado
- [ ] OPcache habilitado
- [ ] Caches do Laravel gerados
- [ ] Nginx configurado com gzip e cache
- [ ] PHP-FPM otimizado
- [ ] Supervisor configurado para queues
- [ ] Cron job do Laravel configurado
- [ ] Permissões de arquivos corretas
- [ ] SSL/HTTPS configurado (Certbot)
- [ ] Logs com rotação configurada
- [ ] Backup automático do banco de dados
- [ ] Monitoramento configurado

## 16. Comandos Úteis de Manutenção

```bash
# Verificar status dos services
sudo systemctl status nginx php8.2-fpm redis-server mysql

# Monitorar workers
sudo supervisorctl status

# Ver logs em tempo real
tail -f storage/logs/laravel.log

# Limpar cache Redis
redis-cli FLUSHALL

# Recriar todos os caches
php artisan optimize

# Verificar performance do OPcache
php -i | grep opcache
```

## Performance Esperada

Com essas otimizações, você deve obter:

- Redução de 60-80% no tempo de resposta
- Cache de configuração/rotas elimina parsing em cada request
- OPcache reduz drasticamente uso de CPU
- Redis acelera sessions e cache
- Gzip reduz bandwidth em ~70%
- Static asset caching reduz requests ao servidor
