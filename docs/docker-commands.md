# Comandos Docker - Referência Rápida

## 🚀 Inicialização

```bash
# Deploy completo (primeira vez ou atualização)
./docker-deploy.sh

# Subir containers
docker compose up -d

# Subir e ver logs
docker compose up

# Rebuild e subir
docker compose up -d --build
```

## 🛑 Parar e Remover

```bash
# Parar containers
docker compose stop

# Parar e remover containers
docker compose down

# Parar, remover containers e volumes (CUIDADO: apaga dados!)
docker compose down -v

# Remover apenas um serviço
docker compose stop app
docker compose rm app
```

## 📊 Monitoramento

```bash
# Ver status dos containers
docker compose ps

# Ver logs de todos os serviços
docker compose logs -f

# Ver logs de um serviço específico
docker compose logs -f app
docker compose logs -f mysql
docker compose logs -f redis

# Ver últimas 100 linhas
docker compose logs --tail=100 app

# Ver uso de recursos
docker stats

# Ver processos rodando
docker compose top
```

## 🔧 Executar Comandos

```bash
# Entrar no container da aplicação
docker compose exec app bash

# Executar comando Artisan
docker compose exec app php artisan [comando]

# Exemplos de comandos Artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:list
docker compose exec app php artisan tinker

# Executar Composer
docker compose exec app composer install
docker compose exec app composer update
docker compose exec app composer dump-autoload

# Executar comando sem TTY (útil em scripts)
docker compose exec -T app php artisan migrate --force
```

## 🗄️ Banco de Dados

```bash
# Entrar no MySQL
docker compose exec mysql mysql -u root -p

# Executar query diretamente
docker compose exec mysql mysql -u root -p${DB_PASSWORD} -e "SHOW DATABASES;"

# Backup do banco
./backup-db.sh

# Restore do banco
./restore-db.sh backups/backup_file.sql.gz

# Ver logs do MySQL
docker compose logs -f mysql

# Executar migrations
docker compose exec app php artisan migrate

# Rollback migrations
docker compose exec app php artisan migrate:rollback

# Status das migrations
docker compose exec app php artisan migrate:status
```

## 🔴 Redis

```bash
# Entrar no Redis CLI
docker compose exec redis redis-cli

# Comandos úteis no Redis CLI:
# PING                  - Testar conexão
# KEYS *                - Ver todas as chaves
# FLUSHALL              - Limpar tudo (CUIDADO!)
# INFO                  - Informações do servidor
# MONITOR               - Ver comandos em tempo real

# Limpar cache Redis
docker compose exec redis redis-cli FLUSHALL

# Ver logs do Redis
docker compose logs -f redis
```

## 🔄 Reiniciar Serviços

```bash
# Reiniciar todos os serviços
docker compose restart

# Reiniciar serviço específico
docker compose restart app
docker compose restart mysql
docker compose restart redis

# Recarregar configuração (sem downtime)
docker compose up -d --force-recreate --no-deps app
```

## 🧹 Limpeza

```bash
# Limpar containers parados
docker container prune

# Limpar imagens não utilizadas
docker image prune -a

# Limpar volumes não utilizados (CUIDADO!)
docker volume prune

# Limpar tudo (CUIDADO!)
docker system prune -a --volumes

# Ver espaço usado
docker system df
```

## 📦 Build e Imagens

```bash
# Build sem cache
docker compose build --no-cache

# Build apenas um serviço
docker compose build app

# Pull das imagens base
docker compose pull

# Ver imagens
docker images

# Remover imagem específica
docker rmi nome-da-imagem
```

## 🔍 Inspeção e Debug

```bash
# Inspecionar container
docker inspect inventario-app

# Ver configuração do compose
docker compose config

# Validar docker-compose.yml
docker compose config --quiet

# Ver variáveis de ambiente
docker compose exec app env

# Ver processos no container
docker compose exec app ps aux

# Ver uso de disco no container
docker compose exec app df -h

# Ver arquivos de log
docker compose exec app tail -f storage/logs/laravel.log
```

## 🔐 Permissões

```bash
# Ajustar permissões do storage
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache

# Ver permissões
docker compose exec app ls -la storage/
```

## 📝 Logs da Aplicação

```bash
# Ver log do Laravel
docker compose exec app tail -f storage/logs/laravel.log

# Ver últimas 100 linhas
docker compose exec app tail -n 100 storage/logs/laravel.log

# Limpar logs
docker compose exec app truncate -s 0 storage/logs/laravel.log
```

## 🚀 Deploy e Atualização

```bash
# Deploy completo
./docker-deploy.sh

# Atualização rápida (sem rebuild)
git pull
docker compose up -d
docker compose exec app composer install --no-dev
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize

# Atualização com rebuild
git pull
docker compose build app
docker compose up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize
```

## 🏥 Health Check

```bash
# Testar se aplicação está respondendo
curl http://localhost:8080

# Testar com headers
curl -I http://localhost:8080

# Testar MySQL
docker compose exec mysql mysqladmin ping -h localhost

# Testar Redis
docker compose exec redis redis-cli ping
```

## 📊 Performance

```bash
# Ver uso de CPU e memória
docker stats --no-stream

# Ver uso de disco dos volumes
docker system df -v

# Ver logs de performance do PHP
docker compose exec app cat /var/log/php-fpm.log
```

## 🔧 Troubleshooting

```bash
# Container não inicia - ver logs
docker compose logs app

# Rebuild completo
docker compose down
docker compose build --no-cache
docker compose up -d

# Verificar conectividade entre containers
docker compose exec app ping mysql
docker compose exec app ping redis

# Ver configuração do PHP
docker compose exec app php -i

# Ver extensões PHP instaladas
docker compose exec app php -m

# Testar conexão com banco
docker compose exec app php artisan tinker
>>> DB::connection()->getPdo();

# Ver rotas da aplicação
docker compose exec app php artisan route:list
```

## 📋 Backup e Restore

```bash
# Backup completo
./backup-db.sh

# Backup manual
docker compose exec mysql mysqldump -u root -p${DB_PASSWORD} \
    --single-transaction --routines --triggers --events \
    ${DB_DATABASE} > backup.sql

# Restore
./restore-db.sh backups/backup_file.sql.gz

# Backup dos uploads (storage)
tar -czf storage-backup.tar.gz storage/app/public/

# Restore dos uploads
tar -xzf storage-backup.tar.gz
```

## 🌐 Rede

```bash
# Ver redes Docker
docker network ls

# Inspecionar rede
docker network inspect inventario_inventario-network

# Ver IPs dos containers
docker compose exec app hostname -i
docker compose exec mysql hostname -i
docker compose exec redis hostname -i
```
