# 📦 Sistema de Inventário

Sistema de gestão de inventário desenvolvido com Laravel 5.7 e CRUDBooster 5.4 para controle de produtos, compras, vendas e estoque.

## 🎯 Funcionalidades

- ✅ Gestão de produtos com categorias
- ✅ Controle de fornecedores (proveedores)
- ✅ Gestão de clientes
- ✅ Registro de compras com itens
- ✅ Registro de vendas com itens
- ✅ Controle automático de estoque via triggers
- ✅ Autenticação e controle de acesso
- ✅ Log de atividades
- ✅ Painel administrativo completo

## 🚀 Deploy Rápido

### Opção 1: Docker (Recomendado)

```bash
# 1. Instalar Docker
curl -fsSL https://get.docker.com | sh

# 2. Configurar
cp .env.docker .env
nano .env  # Editar DB_PASSWORD

# 3. Deploy
chmod +x docker-deploy.sh
./docker-deploy.sh

# 4. Criar admin
docker compose exec app php artisan crudbooster:install
```

**Acesse:** http://seu-ip:8080

📖 **Guia completo:** [QUICK-START.md](QUICK-START.md)

### Opção 2: Tradicional

```bash
# 1. Instalar dependências
composer install --no-dev

# 2. Configurar
cp .env.example .env
php artisan key:generate

# 3. Banco de dados
php artisan migrate

# 4. Otimizar
php artisan optimize
```

📖 **Guia completo:** [.kiro/steering/production-optimization.md](.kiro/steering/production-optimization.md)

## 📚 Documentação

### 🎯 Começar Aqui

| Documento                                      | Descrição                   | Para Quem  |
| ---------------------------------------------- | --------------------------- | ---------- |
| **[QUICK-START.md](QUICK-START.md)**           | Deploy em 5 minutos         | Iniciantes |
| **[DEPLOY-CHECKLIST.md](DEPLOY-CHECKLIST.md)** | Checklist completo          | Todos      |
| **[DEPLOY-INDEX.md](DEPLOY-INDEX.md)**         | Índice de toda documentação | Referência |

### 🐳 Docker

| Documento                                            | Descrição          |
| ---------------------------------------------------- | ------------------ |
| **[README-DOCKER.md](README-DOCKER.md)**             | Visão geral Docker |
| **[DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md)** | Guia passo a passo |
| **[docker-commands.md](docker-commands.md)**         | Comandos úteis     |

### 📖 Técnica

| Documento                                                      | Descrição            |
| -------------------------------------------------------------- | -------------------- |
| **[.kiro/steering/tech.md](.kiro/steering/tech.md)**           | Stack tecnológico    |
| **[.kiro/steering/structure.md](.kiro/steering/structure.md)** | Estrutura do projeto |
| **[.kiro/steering/product.md](.kiro/steering/product.md)**     | Visão do produto     |

## 🛠️ Stack Tecnológico

- **Framework:** Laravel 5.7
- **Admin Panel:** CRUDBooster 5.4
- **PHP:** 7.4+
- **Database:** MySQL 8.0
- **Cache:** Redis 7
- **Web Server:** Nginx
- **Container:** Docker + Docker Compose

## 📋 Requisitos

### Para Docker

- Debian 13 (ou Ubuntu 20.04+)
- Docker Engine 20.10+
- Docker Compose 2.0+
- 2GB RAM mínimo
- 10GB espaço em disco

### Para Deploy Tradicional

- PHP 7.4+ com extensões: mbstring, xml, curl, zip, gd, mysql, redis
- MySQL 8.0 ou MariaDB 10.3+
- Redis 5.0+
- Nginx ou Apache
- Composer 2.0+

## 🔧 Comandos Úteis

### Docker

```bash
# Subir aplicação
docker compose up -d

# Ver logs
docker compose logs -f

# Executar Artisan
docker compose exec app php artisan [comando]

# Backup
./backup-db.sh

# Parar
docker compose down
```

### Laravel

```bash
# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Gerar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrations
php artisan migrate
php artisan migrate:rollback
```

## 💾 Backup e Restore

### Backup

```bash
# Manual
./backup-db.sh

# Automático (cron)
0 2 * * * cd /var/www/inventario && ./backup-db.sh
```

### Restore

```bash
./restore-db.sh backups/backup_file.sql.gz
```

## 🔒 Segurança

### Checklist de Segurança

- ✅ `APP_DEBUG=false` em produção
- ✅ `APP_ENV=production`
- ✅ Senha forte no banco de dados
- ✅ SSL/HTTPS configurado
- ✅ Firewall ativo (UFW)
- ✅ Fail2Ban instalado
- ✅ Backups automáticos
- ✅ Logs com rotação

## 📊 Performance

### Otimizações Aplicadas

- ✅ OPcache habilitado
- ✅ Redis para cache e sessões
- ✅ Gzip compression
- ✅ Static asset caching
- ✅ MySQL tuning
- ✅ Laravel caching (config, routes, views)

### Resultados Esperados

- ⚡ 60-80% redução no tempo de resposta
- 💾 70% redução no uso de bandwidth
- 🚀 Eliminação de parsing em cada request

## 🐛 Troubleshooting

### Problemas Comuns

**Container não inicia:**

```bash
docker compose logs app
docker compose build --no-cache
```

**Erro de permissões:**

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

**Banco não conecta:**

```bash
docker compose restart mysql
docker compose logs mysql
```

**Performance ruim:**

```bash
docker compose exec app php artisan optimize
```

📖 **Mais soluções:** [README-DOCKER.md](README-DOCKER.md#-troubleshooting)

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📝 Estrutura do Projeto

```
.
├── app/                    # Código da aplicação
│   ├── Http/
│   │   ├── Controllers/   # Controllers (Admin*)
│   │   ├── Middleware/    # Middlewares
│   │   └── Requests/      # Form Requests
│   └── Models/            # Models Eloquent
├── config/                # Configurações
├── database/
│   ├── migrations/        # Migrations
│   └── dumps/            # SQL dumps
├── docker/               # Configurações Docker
├── public/               # Document root
├── resources/            # Views e assets
├── routes/               # Rotas
├── storage/              # Logs, cache, uploads
└── vendor/               # Dependências

Documentação:
├── README.md                    # Este arquivo
├── QUICK-START.md              # Deploy rápido
├── README-DOCKER.md            # Guia Docker
├── DOCKER-DEPLOY-GUIDE.md      # Guia completo
├── DEPLOY-CHECKLIST.md         # Checklist
├── DEPLOY-INDEX.md             # Índice
└── docker-commands.md          # Comandos
```

## 📞 Suporte

### Documentação

- [Índice Completo](DEPLOY-INDEX.md)
- [Laravel 5.7 Docs](https://laravel.com/docs/5.7)
- [CRUDBooster Docs](https://crudbooster.com/documentation)
- [Docker Docs](https://docs.docker.com/)

### Logs

```bash
# Aplicação
docker compose logs -f app

# Laravel
docker compose exec app tail -f storage/logs/laravel.log

# MySQL
docker compose logs -f mysql
```

## 📄 Licença

Este projeto é proprietário. Todos os direitos reservados.

## 👥 Autores

- Sistema desenvolvido para gestão de inventário
- Deploy e documentação: 2026

---

## 🎯 Próximos Passos

Após o deploy:

1. ✅ Configurar SSL/HTTPS
2. ✅ Configurar backups automáticos
3. ✅ Configurar monitoramento
4. ⬜ Treinar usuários
5. ⬜ Documentar processos de negócio
6. ⬜ Configurar CI/CD (opcional)

---

**Versão:** 1.0  
**Última atualização:** Fevereiro 2026  
**Status:** Produção
