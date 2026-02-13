#!/bin/bash

# Script de Deploy Automatizado com Docker
# Uso: ./docker-deploy.sh

set -e

echo "🚀 Iniciando deploy com Docker..."
echo ""

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Verificar se docker está instalado
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker não está instalado!${NC}"
    exit 1
fi

if ! command -v docker compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose não está instalado!${NC}"
    exit 1
fi

# Verificar se .env existe
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  Arquivo .env não encontrado!${NC}"
    echo "Copiando .env.docker para .env..."
    cp .env.docker .env
    echo -e "${YELLOW}⚠️  Configure o arquivo .env antes de continuar!${NC}"
    exit 1
fi

# Backup do banco de dados (se já existir)
if docker compose ps mysql | grep -q "Up"; then
    echo -e "${YELLOW}💾 Criando backup do banco de dados...${NC}"
    ./backup-db.sh || echo "Backup falhou, continuando..."
fi

# Pull das imagens base
echo -e "${YELLOW}📥 Atualizando imagens base...${NC}"
docker compose pull

# Build da aplicação
echo -e "${YELLOW}🔨 Construindo imagem da aplicação...${NC}"
docker compose build --no-cache app

# Parar containers antigos
echo -e "${YELLOW}🛑 Parando containers antigos...${NC}"
docker compose down

# Subir containers
echo -e "${YELLOW}🚀 Iniciando containers...${NC}"
docker compose up -d

# Aguardar containers estarem prontos
echo -e "${YELLOW}⏳ Aguardando containers iniciarem...${NC}"
sleep 10

# Verificar status
echo -e "${YELLOW}📊 Verificando status dos containers...${NC}"
docker compose ps

# Instalar dependências
echo -e "${YELLOW}📦 Instalando dependências...${NC}"
docker compose exec -T app composer install --optimize-autoloader --no-dev

# Gerar APP_KEY se necessário
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}🔑 Gerando APP_KEY...${NC}"
    docker compose exec -T app php artisan key:generate
fi

# Executar migrations
echo -e "${YELLOW}🗄️  Executando migrations...${NC}"
docker compose exec -T app php artisan migrate --force

# Gerar caches
echo -e "${YELLOW}⚡ Gerando caches...${NC}"
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose exec -T app php artisan optimize

# Ajustar permissões
echo -e "${YELLOW}🔐 Ajustando permissões...${NC}"
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker compose exec -T app chmod -R 775 storage bootstrap/cache

# Verificar saúde da aplicação
echo -e "${YELLOW}🏥 Verificando saúde da aplicação...${NC}"
sleep 5

if curl -f http://localhost:8080 > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Aplicação está respondendo!${NC}"
else
    echo -e "${RED}❌ Aplicação não está respondendo!${NC}"
    echo "Verificando logs..."
    docker compose logs --tail=50 app
    exit 1
fi

echo ""
echo -e "${GREEN}✨ Deploy concluído com sucesso!${NC}"
echo ""
echo "📊 Informações:"
echo "   - URL: http://localhost:8080"
echo "   - MySQL: localhost:3306"
echo "   - Redis: localhost:6379"
echo ""
echo "📝 Comandos úteis:"
echo "   - Ver logs: docker compose logs -f"
echo "   - Entrar no container: docker compose exec app bash"
echo "   - Parar: docker compose down"
echo "   - Reiniciar: docker compose restart"
echo ""
