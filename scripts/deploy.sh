#!/bin/bash

# Script de Deploy para Produção
# Uso: ./deploy.sh

set -e

echo "🚀 Iniciando processo de deploy..."
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar se está em produção
if [ "$APP_ENV" != "production" ]; then
    echo -e "${YELLOW}⚠️  Aviso: APP_ENV não está definido como 'production'${NC}"
    read -p "Continuar mesmo assim? (s/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Ss]$ ]]; then
        exit 1
    fi
fi

# 1. Modo de manutenção
echo -e "${YELLOW}📋 Ativando modo de manutenção...${NC}"
php artisan down || true

# 2. Atualizar código (se usando Git)
if [ -d .git ]; then
    echo -e "${YELLOW}📥 Atualizando código do repositório...${NC}"
    git pull origin main
fi

# 3. Instalar/Atualizar dependências
echo -e "${YELLOW}📦 Instalando dependências do Composer...${NC}"
composer install --optimize-autoloader --no-dev --no-interaction

# 4. Executar migrations
echo -e "${YELLOW}🗄️  Executando migrations do banco de dados...${NC}"
php artisan migrate --force

# 5. Limpar caches antigos
echo -e "${YELLOW}🧹 Limpando caches antigos...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 6. Gerar novos caches
echo -e "${YELLOW}⚡ Gerando caches otimizados...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Otimizar autoloader
echo -e "${YELLOW}🔧 Otimizando autoloader...${NC}"
composer dump-autoload --optimize --classmap-authoritative

# 8. Otimizar aplicação
echo -e "${YELLOW}🚀 Otimizando aplicação...${NC}"
php artisan optimize

# 9. Reiniciar PHP-FPM
echo -e "${YELLOW}🔄 Reiniciando PHP-FPM...${NC}"
if command -v systemctl &> /dev/null; then
    sudo systemctl reload php8.2-fpm || sudo systemctl reload php-fpm
fi

# 10. Reiniciar workers (se Supervisor estiver instalado)
if command -v supervisorctl &> /dev/null; then
    echo -e "${YELLOW}👷 Reiniciando workers...${NC}"
    sudo supervisorctl restart laravel-worker:* || true
fi

# 11. Desativar modo de manutenção
echo -e "${YELLOW}✅ Desativando modo de manutenção...${NC}"
php artisan up

echo ""
echo -e "${GREEN}✨ Deploy concluído com sucesso!${NC}"
echo ""
echo "📊 Estatísticas:"
echo "   - Versão PHP: $(php -v | head -n 1)"
echo "   - Laravel: $(php artisan --version)"
echo "   - Ambiente: $(php artisan env)"
echo ""
