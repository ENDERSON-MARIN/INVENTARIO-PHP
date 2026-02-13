# Guia Completo: Deploy com Docker no Debian 13

## 📋 Índice

1. [Preparação do Servidor](#1-preparação-do-servidor)
2. [Instalação do Docker](#2-instalação-do-docker)
3. [Configuração dos Arquivos Docker](#3-configuração-dos-arquivos-docker)
4. [Build e Deploy](#4-build-e-deploy)
5. [Configuração SSL/HTTPS](#5-configuração-sslhttps)
6. [Manutenção e Monitoramento](#6-manutenção-e-monitoramento)

---

## 1. Preparação do Servidor

### 1.1 Conectar ao Servidor

```bash
ssh usuario@seu-servidor-ip
```

### 1.2 Atualizar Sistema

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git wget vim
```

### 1.3 Criar Usuário para Deploy (Opcional mas Recomendado)

```bash
sudo adduser deployer
sudo usermod -aG sudo deployer
su - deployer
```

---

## 2. Instalação do Docker

### 2.1 Instalar Docker Engine

```bash
# Remover versões antigas
sudo apt remove docker docker-engine docker.io containerd runc

# Instalar dependências
sudo apt install -y ca-certificates curl gnupg lsb-release

# Adicionar chave GPG oficial do Docker
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Adicionar repositório
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

### 2.2 Configurar Permissões

```bash
# Adicionar usuário ao grupo docker
sudo usermod -aG docker $USER

# Aplicar mudanças (ou fazer logout/login)
newgrp docker

# Testar instalação
docker --version
docker compose version
```

### 2.3 Configurar Docker para Iniciar no Boot

```bash
sudo systemctl enable docker
sudo systemctl start docker
```

---

## 3. Configuração dos Arquivos Docker

### 3.1 Clonar/Copiar Projeto

```bash
# Se usando Git
cd /var/www
sudo mkdir -p inventario
sudo chown $USER:$USER inventario
cd inventario
git clone seu-repositorio.git .

# Ou copiar arquivos via SCP/SFTP
```

### 3.2 Criar Dockerfile

O projeto já tem `.dockerignore`, agora vamos criar o `Dockerfile`:

```bash
cd /var/www/inventario
```

Criar arquivo `Dockerfile` (já vou criar para você).

### 3.3 Criar docker-compose.yml

Criar arquivo `docker-compose.yml` (já vou criar para você).

### 3.4 Configurar Variáveis de Ambiente

```bash
# Copiar template
cp .env.docker .env

# Editar com suas configurações
nano .env
```

Configurações importantes:

- `APP_KEY`: Gerar com `docker compose run --rm app php artisan key:generate --show`
- `DB_HOST=mysql` (nome do serviço no docker-compose)
- `REDIS_HOST=redis` (nome do serviço no docker-compose)
- `DB_PASSWORD`: Senha forte para MySQL

---

## 4. Build e Deploy

### 4.1 Build das Imagens

```bash
cd /var/www/inventario

# Build da aplicação
docker compose build
```

### 4.2 Primeira Execução

```bash
# Subir containers em background
docker compose up -d

# Verificar status
docker compose ps

# Ver logs
docker compose logs -f
```

### 4.3 Configurar Aplicação

```bash
# Gerar APP_KEY (se ainda não gerou)
docker compose exec app php artisan key:generate

# Instalar dependências
docker compose exec app composer install --optimize-autoloader --no-dev

# Executar migrations
docker compose exec app php artisan migrate --force

# Criar usuário admin (CRUDBooster)
docker compose exec app php artisan crudbooster:install

# Otimizar aplicação
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan optimize

# Ajustar permissões
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### 4.4 Verificar Funcionamento

```bash
# Testar conexão
curl http://localhost:8080

# Ou abrir no navegador
# http://seu-servidor-ip:8080
```

---

## 5. Configuração SSL/HTTPS

### 5.1 Instalar Nginx Reverse Proxy (Host)

```bash
sudo apt install -y nginx certbot python3-certbot-nginx
```

### 5.2 Configurar Nginx

```bash
sudo nano /etc/nginx/sites-available/inventario
```

Adicionar configuração (já vou criar arquivo para você).

```bash
# Ativar site
sudo ln -s /etc/nginx/sites-available/inventario /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5.3 Obter Certificado SSL

```bash
# Certbot automático
sudo certbot --nginx -d seu-dominio.com -d www.seu-dominio.com

# Renovação automática já está configurada
sudo certbot renew --dry-run
```

---

## 6. Manutenção e Monitoramento

### 6.1 Comandos Úteis

```bash
# Ver logs em tempo real
docker compose logs -f app

# Ver logs específicos
docker compose logs -f mysql
docker compose logs -f redis
docker compose logs -f nginx

# Entrar no container
docker compose exec app bash
docker compose exec mysql mysql -u root -p

# Reiniciar serviços
docker compose restart app
docker compose restart

# Parar tudo
docker compose down

# Parar e remover volumes (CUIDADO!)
docker compose down -v
```

### 6.2 Backup do Banco de Dados

```bash
# Criar script de backup
nano /var/www/inventario/backup-db.sh
```

Conteúdo (já vou criar para você):

```bash
# Executar backup
chmod +x backup-db.sh
./backup-db.sh
```

### 6.3 Atualizar Aplicação

```bash
cd /var/www/inventario

# Modo manutenção
docker compose exec app php artisan down

# Atualizar código
git pull origin main

# Rebuild se necessário
docker compose build app

# Reiniciar
docker compose up -d

# Migrations e caches
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan optimize

# Sair do modo manutenção
docker compose exec app php artisan up
```

### 6.4 Monitoramento

```bash
# Ver uso de recursos
docker stats

# Ver espaço em disco
docker system df

# Limpar recursos não utilizados
docker system prune -a
```

### 6.5 Logs Automáticos

```bash
# Configurar logrotate
sudo nano /etc/logrotate.d/docker-inventario
```

Conteúdo:

```
/var/lib/docker/containers/*/*.log {
    rotate 7
    daily
    compress
    missingok
    delaycompress
    copytruncate
}
```

---

## 7. Troubleshooting

### Problema: Container não inicia

```bash
# Ver logs detalhados
docker compose logs app

# Verificar configuração
docker compose config

# Rebuild completo
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Problema: Erro de permissões

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Problema: Banco de dados não conecta

```bash
# Verificar se MySQL está rodando
docker compose ps mysql

# Ver logs do MySQL
docker compose logs mysql

# Testar conexão
docker compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### Problema: Redis não conecta

```bash
# Verificar Redis
docker compose ps redis

# Testar conexão
docker compose exec redis redis-cli ping
```

---

## 8. Checklist de Deploy

- [ ] Servidor Debian 13 atualizado
- [ ] Docker e Docker Compose instalados
- [ ] Projeto copiado para `/var/www/inventario`
- [ ] Arquivo `.env` configurado corretamente
- [ ] `APP_KEY` gerado
- [ ] Containers rodando (`docker compose ps`)
- [ ] Migrations executadas
- [ ] Usuário admin criado
- [ ] Caches gerados
- [ ] Nginx reverse proxy configurado
- [ ] SSL/HTTPS configurado
- [ ] Backup automático configurado
- [ ] Firewall configurado (portas 80, 443)
- [ ] Monitoramento configurado

---

## 9. Comandos Rápidos de Referência

```bash
# Subir aplicação
docker compose up -d

# Ver status
docker compose ps

# Ver logs
docker compose logs -f

# Executar comando Laravel
docker compose exec app php artisan [comando]

# Entrar no container
docker compose exec app bash

# Reiniciar
docker compose restart

# Parar
docker compose down

# Backup DB
./backup-db.sh

# Atualizar
git pull && docker compose up -d --build
```

---

## 10. Segurança Adicional

### 10.1 Firewall

```bash
# Instalar UFW
sudo apt install -y ufw

# Configurar regras
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Ativar
sudo ufw enable
sudo ufw status
```

### 10.2 Fail2Ban

```bash
# Instalar
sudo apt install -y fail2ban

# Configurar
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

---

## 📞 Suporte

Em caso de problemas:

1. Verificar logs: `docker compose logs -f`
2. Verificar status: `docker compose ps`
3. Verificar recursos: `docker stats`
4. Consultar documentação Laravel 5.7
5. Consultar documentação Docker

---

**Última atualização**: 2026
**Versão**: 1.0
