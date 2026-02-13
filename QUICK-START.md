# ⚡ Quick Start - Deploy Docker

Guia ultra-rápido para colocar a aplicação no ar em 5 minutos.

## 🎯 Passo 1: Instalar Docker (2 min)

```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
newgrp docker
```

## 🎯 Passo 2: Configurar Projeto (1 min)

```bash
cd /var/www/inventario

# Copiar e editar .env
cp .env.docker .env
nano .env
```

**Trocar apenas estas linhas:**

```env
DB_PASSWORD=SuaSenhaSegura123!
APP_URL=http://seu-ip:8080
```

## 🎯 Passo 3: Deploy (2 min)

```bash
chmod +x docker-deploy.sh
./docker-deploy.sh
```

## 🎯 Passo 4: Criar Admin

```bash
docker compose exec app php artisan crudbooster:install
```

Preencher:

- Nome: Admin
- Email: admin@admin.com
- Senha: admin123

## ✅ Pronto!

Acesse: `http://seu-ip:8080`

---

## 📋 Comandos Úteis

```bash
# Ver logs
docker compose logs -f

# Parar
docker compose down

# Reiniciar
docker compose restart

# Backup
./backup-db.sh

# Entrar no container
docker compose exec app bash
```

## 🔥 Troubleshooting Rápido

**Erro de permissão:**

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

**Banco não conecta:**

```bash
docker compose restart mysql
docker compose logs mysql
```

**Limpar tudo e recomeçar:**

```bash
docker compose down -v
./docker-deploy.sh
```

---

Para guia completo, veja: **[DOCKER-DEPLOY-GUIDE.md](DOCKER-DEPLOY-GUIDE.md)**
