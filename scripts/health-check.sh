#!/bin/bash

# Script de Health Check para Produção
# Verifica se todos os serviços estão funcionando corretamente

echo "🏥 Verificação de Saúde do Sistema"
echo "=================================="
echo ""

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

ERRORS=0

# Função para verificar serviço
check_service() {
    if systemctl is-active --quiet $1; then
        echo -e "${GREEN}✓${NC} $1 está rodando"
    else
        echo -e "${RED}✗${NC} $1 NÃO está rodando"
        ((ERRORS++))
    fi
}

# Função para verificar porta
check_port() {
    if nc -z localhost $1 2>/dev/null; then
        echo -e "${GREEN}✓${NC} Porta $1 ($2) está acessível"
    else
        echo -e "${RED}✗${NC} Porta $1 ($2) NÃO está acessível"
        ((ERRORS++))
    fi
}

# 1. Verificar serviços do sistema
echo "📋 Serviços do Sistema:"
check_service nginx
check_service php8.2-fpm || check_service php-fpm
check_service mysql || check_service mariadb
check_service redis-server
check_service supervisor
echo ""

# 2. Verificar portas
echo "🔌 Portas:"
check_port 80 "HTTP"
check_port 443 "HTTPS"
check_port 3306 "MySQL"
check_port 6379 "Redis"
echo ""

# 3. Verificar conexão com banco de dados
echo "🗄️  Banco de Dados:"
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';" 2>/dev/null | grep -q "OK"; then
    echo -e "${GREEN}✓${NC} Conexão com banco de dados OK"
else
    echo -e "${RED}✗${NC} Falha na conexão com banco de dados"
    ((ERRORS++))
fi
echo ""

# 4. Verificar Redis
echo "💾 Redis:"
if redis-cli ping 2>/dev/null | grep -q "PONG"; then
    echo -e "${GREEN}✓${NC} Redis respondendo"
    REDIS_MEMORY=$(redis-cli info memory | grep used_memory_human | cut -d: -f2 | tr -d '\r')
    echo "   Memória usada: $REDIS_MEMORY"
else
    echo -e "${RED}✗${NC} Redis não está respondendo"
    ((ERRORS++))
fi
echo ""

# 5. Verificar permissões de diretórios
echo "📁 Permissões:"
if [ -w storage ] && [ -w bootstrap/cache ]; then
    echo -e "${GREEN}✓${NC} Diretórios storage e bootstrap/cache são graváveis"
else
    echo -e "${RED}✗${NC} Problemas com permissões de escrita"
    ((ERRORS++))
fi
echo ""

# 6. Verificar espaço em disco
echo "💿 Espaço em Disco:"
DISK_USAGE=$(df -h . | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -lt 90 ]; then
    echo -e "${GREEN}✓${NC} Espaço em disco OK ($DISK_USAGE% usado)"
else
    echo -e "${YELLOW}⚠${NC}  Espaço em disco alto ($DISK_USAGE% usado)"
fi
echo ""

# 7. Verificar logs recentes
echo "📝 Logs Recentes:"
if [ -f storage/logs/laravel.log ]; then
    ERROR_COUNT=$(grep -c "ERROR" storage/logs/laravel.log 2>/dev/null | tail -1 || echo "0")
    if [ $ERROR_COUNT -gt 0 ]; then
        echo -e "${YELLOW}⚠${NC}  $ERROR_COUNT erros encontrados no log"
    else
        echo -e "${GREEN}✓${NC} Nenhum erro recente nos logs"
    fi
else
    echo -e "${YELLOW}⚠${NC}  Arquivo de log não encontrado"
fi
echo ""

# 8. Verificar workers (Supervisor)
if command -v supervisorctl &> /dev/null; then
    echo "👷 Workers (Supervisor):"
    WORKER_STATUS=$(sudo supervisorctl status laravel-worker:* 2>/dev/null | grep -c "RUNNING" || echo "0")
    if [ $WORKER_STATUS -gt 0 ]; then
        echo -e "${GREEN}✓${NC} $WORKER_STATUS worker(s) rodando"
    else
        echo -e "${YELLOW}⚠${NC}  Nenhum worker rodando"
    fi
    echo ""
fi

# 9. Verificar OPcache
echo "⚡ OPcache:"
OPCACHE_ENABLED=$(php -r "echo ini_get('opcache.enable');" 2>/dev/null)
if [ "$OPCACHE_ENABLED" = "1" ]; then
    echo -e "${GREEN}✓${NC} OPcache está habilitado"
else
    echo -e "${YELLOW}⚠${NC}  OPcache não está habilitado"
fi
echo ""

# 10. Verificar configuração do Laravel
echo "🔧 Configuração Laravel:"
if [ -f bootstrap/cache/config.php ]; then
    echo -e "${GREEN}✓${NC} Cache de configuração existe"
else
    echo -e "${YELLOW}⚠${NC}  Cache de configuração não encontrado (execute: php artisan config:cache)"
fi

if [ -f bootstrap/cache/routes.php ]; then
    echo -e "${GREEN}✓${NC} Cache de rotas existe"
else
    echo -e "${YELLOW}⚠${NC}  Cache de rotas não encontrado (execute: php artisan route:cache)"
fi
echo ""

# Resumo final
echo "=================================="
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✨ Sistema está saudável!${NC}"
    exit 0
else
    echo -e "${RED}⚠️  Encontrados $ERRORS problema(s)${NC}"
    exit 1
fi
