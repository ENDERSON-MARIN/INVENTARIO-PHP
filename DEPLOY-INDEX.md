# 📚 Índice de Documentação - Deploy e Produção

Guia completo de toda a documentação disponível para deploy do Sistema de Inventário.

## 🚀 Começar Aqui

### Para Deploy com Docker (Recomendado)

1. **[QUICK-START.md](QUICK-START.md)** ⚡ - Deploy em 5 minutos
2. **[README-DOCKER.md](README-DOCKER.md)** 📖 - Visão geral Docker
3. **[DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md)** 📋 - Guia completo passo a passo

### Para Deploy Tradicional (Sem Docker)

1. **[.kiro/steering/production-optimization.md](.kiro/steering/production-optimization.md)** - Otimizações para produção

---

## 📁 Documentação por Categoria

### 🐳 Docker

| Arquivo                                              | Descrição                    | Quando Usar                |
| ---------------------------------------------------- | ---------------------------- | -------------------------- |
| **[QUICK-START.md](QUICK-START.md)**                 | Deploy rápido em 5 minutos   | Primeira vez, teste rápido |
| **[README-DOCKER.md](README-DOCKER.md)**             | Visão geral e referência     | Consulta geral             |
| **[DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md)** | Guia completo passo a passo  | Deploy detalhado           |
| **[docker-commands.md](docker-commands.md)**         | Referência de comandos       | Operações do dia a dia     |
| **[docker-compose.yml](docker-compose.yml)**         | Configuração produção        | Deploy produção            |
| **[docker-compose.dev.yml](docker-compose.dev.yml)** | Configuração desenvolvimento | Desenvolvimento local      |
| **[Dockerfile](Dockerfile)**                         | Imagem da aplicação          | Build customizado          |

### 🔧 Scripts de Automação

| Script                                   | Descrição                       | Uso                              |
| ---------------------------------------- | ------------------------------- | -------------------------------- |
| **[docker-deploy.sh](docker-deploy.sh)** | Deploy automático completo      | `./docker-deploy.sh`             |
| **[backup-db.sh](backup-db.sh)**         | Backup do banco de dados        | `./backup-db.sh`                 |
| **[restore-db.sh](restore-db.sh)**       | Restore do banco de dados       | `./restore-db.sh arquivo.sql.gz` |
| **[deploy.sh](deploy.sh)**               | Deploy tradicional (sem Docker) | `./deploy.sh`                    |

### ⚙️ Configurações

| Arquivo                                                    | Descrição                     | Quando Editar     |
| ---------------------------------------------------------- | ----------------------------- | ----------------- |
| **[.env.docker](.env.docker)**                             | Template variáveis Docker     | Copiar para .env  |
| **[.env.production](.env.production)**                     | Template produção tradicional | Deploy sem Docker |
| **[nginx-reverse-proxy.conf](nginx-reverse-proxy.conf)**   | Nginx host (SSL)              | Configurar HTTPS  |
| **[docker/nginx/default.conf](docker/nginx/default.conf)** | Nginx container               | Customizar Nginx  |
| **[docker/mysql/my.cnf](docker/mysql/my.cnf)**             | MySQL otimizado               | Tuning MySQL      |

### 📖 Documentação Técnica

| Arquivo                                                                                    | Descrição            | Público         |
| ------------------------------------------------------------------------------------------ | -------------------- | --------------- |
| **[.kiro/steering/tech.md](.kiro/steering/tech.md)**                                       | Stack tecnológico    | Desenvolvedores |
| **[.kiro/steering/structure.md](.kiro/steering/structure.md)**                             | Estrutura do projeto | Desenvolvedores |
| **[.kiro/steering/product.md](.kiro/steering/product.md)**                                 | Visão do produto     | Todos           |
| **[.kiro/steering/production-optimization.md](.kiro/steering/production-optimization.md)** | Otimizações produção | DevOps          |

---

## 🎯 Fluxos de Trabalho

### 1️⃣ Primeiro Deploy (Docker)

```bash
# 1. Instalar Docker
curl -fsSL https://get.docker.com | sh

# 2. Configurar
cp .env.docker .env
nano .env

# 3. Deploy
./docker-deploy.sh

# 4. Criar admin
docker compose exec app php artisan crudbooster:install
```

📖 **Guia:** [QUICK-START.md](QUICK-START.md)

### 2️⃣ Configurar SSL/HTTPS

```bash
# 1. Instalar Nginx e Certbot
sudo apt install nginx certbot python3-certbot-nginx

# 2. Configurar reverse proxy
sudo cp nginx-reverse-proxy.conf /etc/nginx/sites-available/inventario
sudo nano /etc/nginx/sites-available/inventario  # Editar domínio
sudo ln -s /etc/nginx/sites-available/inventario /etc/nginx/sites-enabled/

# 3. Obter certificado
sudo certbot --nginx -d seu-dominio.com
```

📖 **Guia:** [DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md#5-configuração-sslhttps)

### 3️⃣ Backup e Restore

```bash
# Backup
./backup-db.sh

# Backup automático (cron)
crontab -e
# Adicionar: 0 2 * * * cd /var/www/inventario && ./backup-db.sh

# Restore
./restore-db.sh backups/backup_file.sql.gz
```

📖 **Guia:** [README-DOCKER.md](README-DOCKER.md#-backup-e-restore)

### 4️⃣ Atualizar Aplicação

```bash
# Método automático
./docker-deploy.sh

# Método manual
docker compose exec app php artisan down
git pull
docker compose up -d --build
docker compose exec app composer install --no-dev
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize
docker compose exec app php artisan up
```

📖 **Guia:** [README-DOCKER.md](README-DOCKER.md#-atualização-da-aplicação)

### 5️⃣ Troubleshooting

```bash
# Ver logs
docker compose logs -f app

# Verificar status
docker compose ps

# Rebuild completo
docker compose down
docker compose build --no-cache
docker compose up -d

# Ajustar permissões
docker compose exec app chmod -R 775 storage bootstrap/cache
```

📖 **Guia:** [docker-commands.md](docker-commands.md#-troubleshooting)

---

## 🔍 Encontrar Informação Rápida

### Preciso de...

**Comandos Docker básicos**  
→ [docker-commands.md](docker-commands.md)

**Deploy rápido**  
→ [QUICK-START.md](QUICK-START.md)

**Configurar SSL**  
→ [DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md#5-configuração-sslhttps)

**Fazer backup**  
→ [README-DOCKER.md](README-DOCKER.md#-backup-e-restore)

**Resolver problemas**  
→ [README-DOCKER.md](README-DOCKER.md#-troubleshooting)

**Otimizar performance**  
→ [.kiro/steering/production-optimization.md](.kiro/steering/production-optimization.md)

**Entender a estrutura**  
→ [.kiro/steering/structure.md](.kiro/steering/structure.md)

**Ver tecnologias usadas**  
→ [.kiro/steering/tech.md](.kiro/steering/tech.md)

---

## 📊 Comparação: Docker vs Tradicional

| Aspecto            | Docker     | Tradicional |
| ------------------ | ---------- | ----------- |
| **Setup**          | Automático | Manual      |
| **Dependências**   | Isoladas   | Sistema     |
| **Portabilidade**  | Alta       | Baixa       |
| **Manutenção**     | Simples    | Complexa    |
| **Backup**         | Volumes    | Arquivos    |
| **Escalabilidade** | Fácil      | Difícil     |
| **Recursos**       | Otimizado  | Variável    |

**Recomendação:** Use Docker para produção moderna.

---

## 🎓 Níveis de Conhecimento

### Iniciante

1. [QUICK-START.md](QUICK-START.md) - Deploy básico
2. [README-DOCKER.md](README-DOCKER.md) - Conceitos gerais
3. [docker-commands.md](docker-commands.md) - Comandos essenciais

### Intermediário

1. [DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md) - Deploy completo
2. [nginx-reverse-proxy.conf](nginx-reverse-proxy.conf) - SSL/HTTPS
3. [backup-db.sh](backup-db.sh) - Backups

### Avançado

1. [Dockerfile](Dockerfile) - Customização de imagem
2. [docker-compose.yml](docker-compose.yml) - Orquestração
3. [.kiro/steering/production-optimization.md](.kiro/steering/production-optimization.md) - Otimizações

---

## 🆘 Suporte

### Problemas Comuns

**Container não inicia**

```bash
docker compose logs app
docker compose build --no-cache
```

**Banco não conecta**

```bash
docker compose restart mysql
docker compose logs mysql
```

**Permissões erradas**

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

**Performance ruim**

```bash
docker compose exec app php artisan optimize
docker stats
```

### Onde Buscar Ajuda

1. **Logs:** `docker compose logs -f`
2. **Status:** `docker compose ps`
3. **Documentação:** Este índice
4. **Laravel Docs:** https://laravel.com/docs/5.7
5. **Docker Docs:** https://docs.docker.com/

---

## ✅ Checklist de Produção

### Antes do Deploy

- [ ] Docker instalado
- [ ] .env configurado
- [ ] Senhas fortes definidas
- [ ] Domínio apontado para servidor

### Após Deploy

- [ ] Aplicação acessível
- [ ] SSL/HTTPS configurado
- [ ] Backup automático configurado
- [ ] Firewall configurado
- [ ] Logs funcionando
- [ ] Monitoramento ativo

### Manutenção Regular

- [ ] Backups testados semanalmente
- [ ] Logs revisados
- [ ] Atualizações aplicadas
- [ ] Performance monitorada
- [ ] Segurança auditada

---

**Última atualização:** Fevereiro 2026  
**Versão da documentação:** 1.0  
**Sistema:** Laravel 5.7 + CRUDBooster 5.4 + Docker
