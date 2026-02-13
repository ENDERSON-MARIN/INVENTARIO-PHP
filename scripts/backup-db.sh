#!/bin/bash

# Script de Backup do Banco de Dados
# Uso: ./backup-db.sh

set -e

# Configurações
BACKUP_DIR="./backups"
DATE=$(date +%Y%m%d_%H%M%S)
CONTAINER_NAME="inventario-mysql"

# Carregar variáveis do .env
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

# Criar diretório de backup se não existir
mkdir -p $BACKUP_DIR

# Nome do arquivo de backup
BACKUP_FILE="$BACKUP_DIR/backup_${DB_DATABASE}_${DATE}.sql"

echo "🗄️  Iniciando backup do banco de dados..."
echo "📦 Database: $DB_DATABASE"
echo "📁 Arquivo: $BACKUP_FILE"

# Executar backup
docker compose exec -T mysql mysqldump \
    -u root \
    -p${DB_PASSWORD} \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    ${DB_DATABASE} > $BACKUP_FILE

# Comprimir backup
echo "🗜️  Comprimindo backup..."
gzip $BACKUP_FILE

echo "✅ Backup concluído: ${BACKUP_FILE}.gz"

# Manter apenas últimos 7 backups
echo "🧹 Limpando backups antigos..."
ls -t $BACKUP_DIR/backup_*.sql.gz | tail -n +8 | xargs -r rm

echo "✨ Processo finalizado!"
