# 🐳 Deploy com Docker - Sistema de Inventário

Guia completo para deploy do sistema de inventário usando Docker no Debian 13.

## 📑 Documentação

- **[DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md)** - Guia passo a passo completo
- **[docker-commands.md](docker-commands.md)** - Referência rápida de comandos
- **[.kiro/steering/production-optimization.md](.kiro/steering/production-optimization.md)** - Otimizações sem Docker

## 🚀 Quick Start

### 1. Pré-requisitos

```bash
# Debian 13 atualizado
sudo apt update && sudo apt upgrade -y

# Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker

# Verificar instalação
docker --version
docker compose version
```

### 2. Configurar Projeto

```bash
# Clonar/copiar projeto
cd /var/www
sudo mkdir -p inventario
sudo chown $USER:$USER inventario
cd inventario

# Copiar arquivos (ou git clone)

# Configurar ambiente
cp .env.docker .env
nano .env
```

**Editar .env com suas configurações:**

```env
DB_DATABASE=inventario_db
DB_USERNAME=inventario_user
DB_PASSWORD=SuaSenhaSegura123!
```

### 3. Deploy Automático

```bash
# Tornar scripts executáveis
chmod +x docker-deploy.sh backup-db.sh restore-db.sh

# Executar deploy
./docker-deploy.sh
```

### 4. Configurar Aplicação

```bash
# Criar usuário admin do CRUDBooster
docker compose exec app php artisan crudbooster:install

# Importar banco de dados (se tiver dump)
docker compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < database/dumps/01_Dump20220121_fixed.sql

# Executar triggers
docker compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < database/dumps/02_triggers_PRODUTOS_fixed.sql
```

### 5. Acessar Aplicação

```
http://seu-servidor-ip:8080
```

## 📁 Estrutura de Arquivos Docker

```
.
├── Dockerfile                      # Imagem da aplicação
├── docker-compose.yml              # Orquestração dos serviços
├── .env.docker                     # Template de variáveis
├── docker/
│   ├── nginx/
│   │   └── default.conf           # Configuração Nginx
│   ├── mysql/
│   │   └── my.cnf                 # Configuração MySQL
│   ├── supervisor/
│   │   └── supervisord.conf       # Supervisor (PHP-FPM + Nginx)
│   └── start.sh                   # Script de inicialização
├── docker-deploy.sh               # Script de deploy automático
├── backup-db.sh                   # Script de backup
├── restore-db.sh                  # Script de restore
├── nginx-reverse-proxy.conf       # Config Nginx host (SSL)
└── docker-commands.md             # Referência de comandos
```

## 🐳 Serviços Docker

| Serviço | Container        | Porta | Descrição                 |
| ------- | ---------------- | ----- | ------------------------- |
| app     | inventario-app   | 8080  | Laravel + Nginx + PHP-FPM |
| mysql   | inventario-mysql | 3306  | MySQL 8.0                 |
| redis   | inventario-redis | 6379  | Redis 7 (cache/sessions)  |

## 🔧 Comandos Essenciais

```bash
# Subir aplicação
docker compose up -d

# Ver logs
docker compose logs -f

# Entrar no container
docker compose exec app bash

# Executar Artisan
docker compose exec app php artisan [comando]

# Backup do banco
./backup-db.sh

# Parar tudo
docker compose down
```

## 🌐 Configurar SSL/HTTPS

### 1. Instalar Nginx no Host

```bash
sudo apt install -y nginx certbot python3-certbot-nginx
```

### 2. Configurar Reverse Proxy

```bash
# Copiar configuração
sudo cp nginx-reverse-proxy.conf /etc/nginx/sites-available/inventario

# Editar domínio
sudo nano /etc/nginx/sites-available/inventario
# Trocar: seu-dominio.com pelo seu domínio real

# Ativar site
sudo ln -s /etc/nginx/sites-available/inventario /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 3. Obter Certificado SSL

```bash
sudo certbot --nginx -d seu-dominio.com -d www.seu-dominio.com
```

### 4. Configurar Firewall

```bash
sudo apt install -y ufw
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## 💾 Backup e Restore

### Backup Automático

```bash
# Backup manual
./backup-db.sh

# Configurar backup automático (cron)
crontab -e

# Adicionar linha (backup diário às 2h)
0 2 * * * cd /var/www/inventario && ./backup-db.sh
```

### Restore

```bash
# Listar backups
ls -lh backups/

# Restaurar
./restore-db.sh backups/backup_inventario_db_20260213_140000.sql.gz
```

## 🔄 Atualização da Aplicação

```bash
# Método 1: Script automático
./docker-deploy.sh

# Método 2: Manual
docker compose exec app php artisan down
git pull origin main
docker compose build app
docker compose up -d
docker compose exec app composer install --no-dev
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize
docker compose exec app php artisan up
```

## 📊 Monitoramento

```bash
# Status dos containers
docker compose ps

# Uso de recursos
docker stats

# Logs em tempo real
docker compose logs -f app

# Logs do Laravel
docker compose exec app tail -f storage/logs/laravel.log

# Health check
curl http://localhost:8080
```

## 🐛 Troubleshooting

### Container não inicia

```bash
# Ver logs detalhados
docker compose logs app

# Rebuild sem cache
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Erro de permissões

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Banco não conecta

```bash
# Verificar MySQL
docker compose ps mysql
docker compose logs mysql

# Testar conexão
docker compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### Redis não conecta

```bash
# Verificar Redis
docker compose exec redis redis-cli ping

# Limpar cache
docker compose exec redis redis-cli FLUSHALL
```

### Aplicação lenta

```bash
# Gerar caches
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan optimize

# Verificar recursos
docker stats
```

## 🔐 Segurança

### Checklist de Segurança

- [ ] Senha forte no MySQL (`DB_PASSWORD`)
- [ ] `APP_DEBUG=false` em produção
- [ ] `APP_ENV=production`
- [ ] SSL/HTTPS configurado
- [ ] Firewall configurado (UFW)
- [ ] Fail2Ban instalado
- [ ] Backups automáticos configurados
- [ ] Logs com rotação
- [ ] Atualizações do sistema aplicadas

### Configurar Fail2Ban

```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## 📈 Performance

### Otimizações Aplicadas

✅ **PHP OPcache** - Cache de bytecode  
✅ **Redis** - Cache de aplicação e sessões  
✅ **Nginx Gzip** - Compressão de assets  
✅ **Static Asset Caching** - Cache de 1 ano  
✅ **MySQL Tuning** - Buffer pool e query cache  
✅ **Composer Optimized** - Autoloader otimizado  
✅ **Laravel Caching** - Config, routes e views

### Resultados Esperados

- ⚡ 60-80% redução no tempo de resposta
- 💾 70% redução no uso de bandwidth (gzip)
- 🚀 Eliminação de parsing em cada request
- 📉 Redução drástica no uso de CPU

## 📞 Suporte

### Logs Importantes

```bash
# Aplicação Laravel
docker compose exec app tail -f storage/logs/laravel.log

# Nginx
docker compose logs -f app | grep nginx

# MySQL
docker compose logs -f mysql

# Redis
docker compose logs -f redis
```

### Comandos de Diagnóstico

```bash
# Verificar configuração
docker compose config

# Ver variáveis de ambiente
docker compose exec app env | grep -E 'APP_|DB_|REDIS_'

# Testar rotas
docker compose exec app php artisan route:list

# Ver extensões PHP
docker compose exec app php -m

# Verificar conectividade
docker compose exec app ping mysql
docker compose exec app ping redis
```

## 📚 Recursos Adicionais

- [Documentação Laravel 5.7](https://laravel.com/docs/5.7)
- [Documentação Docker](https://docs.docker.com/)
- [Documentação CRUDBooster](https://crudbooster.com/documentation)
- [MySQL 8.0 Reference](https://dev.mysql.com/doc/refman/8.0/en/)
- [Redis Documentation](https://redis.io/documentation)

## 🎯 Próximos Passos

1. ✅ Deploy básico funcionando
2. ⬜ Configurar SSL/HTTPS
3. ⬜ Configurar backups automáticos
4. ⬜ Configurar monitoramento
5. ⬜ Configurar CI/CD (opcional)
6. ⬜ Configurar logs centralizados (opcional)

---

**Versão:** 1.0  
**Última atualização:** Fevereiro 2026  
**Sistema:** Laravel 5.7 + CRUDBooster 5.4  
**Ambiente:** Docker + Debian 13
