#!/bin/bash

# Script de Restore do Banco de Dados
# Uso: ./restore-db.sh backup_file.sql.gz

set -e

if [ -z "$1" ]; then
    echo "❌ Erro: Especifique o arquivo de backup"
    echo "Uso: ./restore-db.sh backup_file.sql.gz"
    echo ""
    echo "Backups disponíveis:"
    ls -lh backups/
    exit 1
fi

BACKUP_FILE=$1
CONTAINER_NAME="inventario-mysql"

# Carregar variáveis do .env
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

if [ ! -f "$BACKUP_FILE" ]; then
    echo "❌ Arquivo não encontrado: $BACKUP_FILE"
    exit 1
fi

echo "⚠️  ATENÇÃO: Este processo irá SUBSTITUIR o banco de dados atual!"
echo "📦 Database: $DB_DATABASE"
echo "📁 Arquivo: $BACKUP_FILE"
echo ""
read -p "Deseja continuar? (digite 'sim' para confirmar): " -r
echo

if [[ ! $REPLY =~ ^[Ss][Ii][Mm]$ ]]; then
    echo "❌ Operação cancelada"
    exit 1
fi

echo "🗄️  Iniciando restore..."

# Descomprimir se necessário
if [[ $BACKUP_FILE == *.gz ]]; then
    echo "🗜️  Descomprimindo backup..."
    gunzip -c $BACKUP_FILE | docker compose exec -T mysql mysql \
        -u root \
        -p${DB_PASSWORD} \
        ${DB_DATABASE}
else
    docker compose exec -T mysql mysql \
        -u root \
        -p${DB_PASSWORD} \
        ${DB_DATABASE} < $BACKUP_FILE
fi

echo "✅ Restore concluído!"
echo "🔄 Limpando caches..."

docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

echo "✨ Processo finalizado!"
