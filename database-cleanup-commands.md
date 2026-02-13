# 🗑️ Comandos para Limpar Dados do MySQL

Guia de comandos SQL para deletar dados das tabelas do sistema de inventário.

## ⚠️ ATENÇÃO

**CUIDADO:** Estes comandos deletam dados permanentemente. Sempre faça backup antes!

```bash
# Fazer backup antes de deletar
./backup-db.sh
```

---

## 🐳 Usando Docker

### Método 1: Via Docker Compose (Recomendado)

```bash
# Entrar no MySQL
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE}

# Ou em uma linha
docker compose exec -it mysql mysql -u root -p
```

### Método 2: Executar SQL Direto

```bash
# Executar comando SQL direto
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} -e "DELETE FROM cms_logs;"

# Múltiplos comandos
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} <<EOF
DELETE FROM cms_logs;
DELETE FROM activity_log;
EOF
```

---

## 💻 Sem Docker (Tradicional)

```bash
# Entrar no MySQL
mysql -u seu_usuario -p seu_database

# Ou executar direto
mysql -u seu_usuario -p seu_database -e "DELETE FROM cms_logs;"
```

---

## 📋 Comandos SQL por Tabela

### 1. Limpar Logs do Sistema

```sql
-- Logs do CRUDBooster
DELETE FROM cms_logs;

-- Activity Log (Spatie)
DELETE FROM activity_log;

-- Resetar auto-increment (opcional)
ALTER TABLE cms_logs AUTO_INCREMENT = 1;
ALTER TABLE activity_log AUTO_INCREMENT = 1;
```

### 2. Limpar Dados de Negócio

```sql
-- ⚠️ CUIDADO: Ordem importa por causa das foreign keys!

-- 1. Deletar detalhes primeiro
DELETE FROM venta_detalles;
DELETE FROM compra_detalles;

-- 2. Deletar vendas e compras
DELETE FROM ventas;
DELETE FROM compras;

-- 3. Deletar produtos, clientes, fornecedores
DELETE FROM productos;
DELETE FROM clientes;
DELETE FROM proveedores;

-- 4. Deletar categorias
DELETE FROM categorias;

-- Resetar auto-increment
ALTER TABLE venta_detalles AUTO_INCREMENT = 1;
ALTER TABLE compra_detalles AUTO_INCREMENT = 1;
ALTER TABLE ventas AUTO_INCREMENT = 1;
ALTER TABLE compras AUTO_INCREMENT = 1;
ALTER TABLE productos AUTO_INCREMENT = 1;
ALTER TABLE clientes AUTO_INCREMENT = 1;
ALTER TABLE proveedores AUTO_INCREMENT = 1;
ALTER TABLE categorias AUTO_INCREMENT = 1;
```

### 3. Limpar Notificações

```sql
DELETE FROM cms_notifications;
ALTER TABLE cms_notifications AUTO_INCREMENT = 1;
```

### 4. Limpar Email Queue

```sql
DELETE FROM cms_email_queues;
ALTER TABLE cms_email_queues AUTO_INCREMENT = 1;
```

---

## 🔥 Scripts Completos

### Script 1: Limpar APENAS Logs

```sql
-- Limpar logs mantendo dados de negócio
TRUNCATE TABLE cms_logs;
TRUNCATE TABLE activity_log;
```

### Script 2: Limpar Dados de Teste (Manter Estrutura)

```sql
-- Desabilitar verificação de foreign keys temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- Limpar dados de negócio
TRUNCATE TABLE venta_detalles;
TRUNCATE TABLE compra_detalles;
TRUNCATE TABLE ventas;
TRUNCATE TABLE compras;
TRUNCATE TABLE productos;
TRUNCATE TABLE clientes;
TRUNCATE TABLE proveedores;
TRUNCATE TABLE categorias;

-- Limpar logs
TRUNCATE TABLE cms_logs;
TRUNCATE TABLE activity_log;
TRUNCATE TABLE cms_notifications;

-- Reabilitar verificação
SET FOREIGN_KEY_CHECKS = 1;
```

### Script 3: Reset Completo (Exceto Usuários)

```sql
-- ⚠️ CUIDADO: Deleta TUDO exceto usuários e configurações

SET FOREIGN_KEY_CHECKS = 0;

-- Dados de negócio
TRUNCATE TABLE venta_detalles;
TRUNCATE TABLE compra_detalles;
TRUNCATE TABLE ventas;
TRUNCATE TABLE compras;
TRUNCATE TABLE productos;
TRUNCATE TABLE clientes;
TRUNCATE TABLE proveedores;
TRUNCATE TABLE categorias;

-- Logs e notificações
TRUNCATE TABLE cms_logs;
TRUNCATE TABLE activity_log;
TRUNCATE TABLE cms_notifications;
TRUNCATE TABLE cms_email_queues;

SET FOREIGN_KEY_CHECKS = 1;
```

---

## 🚀 Executar Scripts via Docker

### Criar arquivo SQL

```bash
# Criar arquivo com comandos
cat > cleanup-logs.sql <<'EOF'
TRUNCATE TABLE cms_logs;
TRUNCATE TABLE activity_log;
SELECT 'Logs limpos com sucesso!' as status;
EOF

# Executar
docker compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < cleanup-logs.sql
```

### Script Bash Completo

```bash
#!/bin/bash
# cleanup-database.sh

echo "⚠️  ATENÇÃO: Este script irá deletar dados!"
echo "Tabelas que serão limpas:"
echo "  - cms_logs"
echo "  - activity_log"
echo ""
read -p "Deseja continuar? (digite 'sim'): " -r
echo

if [[ ! $REPLY =~ ^[Ss][Ii][Mm]$ ]]; then
    echo "❌ Operação cancelada"
    exit 1
fi

echo "💾 Criando backup primeiro..."
./backup-db.sh

echo "🗑️  Limpando logs..."
docker compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} <<EOF
TRUNCATE TABLE cms_logs;
TRUNCATE TABLE activity_log;
SELECT 'Logs limpos!' as status;
EOF

echo "✅ Limpeza concluída!"
```

---

## 📊 Verificar Quantidade de Registros

### Antes de Deletar

```sql
-- Ver quantidade de registros em cada tabela
SELECT 'cms_logs' as tabela, COUNT(*) as registros FROM cms_logs
UNION ALL
SELECT 'activity_log', COUNT(*) FROM activity_log
UNION ALL
SELECT 'ventas', COUNT(*) FROM ventas
UNION ALL
SELECT 'compras', COUNT(*) FROM compras
UNION ALL
SELECT 'productos', COUNT(*) FROM productos
UNION ALL
SELECT 'clientes', COUNT(*) FROM clientes
UNION ALL
SELECT 'proveedores', COUNT(*) FROM proveedores
UNION ALL
SELECT 'categorias', COUNT(*) FROM categorias;
```

### Via Docker

```bash
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} -e "
SELECT 'cms_logs' as tabela, COUNT(*) as registros FROM cms_logs
UNION ALL SELECT 'activity_log', COUNT(*) FROM activity_log
UNION ALL SELECT 'ventas', COUNT(*) FROM ventas
UNION ALL SELECT 'compras', COUNT(*) FROM compras;
"
```

---

## 🔍 Deletar com Condições

### Deletar Logs Antigos (Mais de 30 dias)

```sql
-- Logs do CRUDBooster
DELETE FROM cms_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- Activity Log
DELETE FROM activity_log
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Deletar por Data Específica

```sql
-- Deletar vendas de um período
DELETE FROM venta_detalles
WHERE venta_id IN (
    SELECT id FROM ventas
    WHERE fecha BETWEEN '2024-01-01' AND '2024-01-31'
);

DELETE FROM ventas
WHERE fecha BETWEEN '2024-01-01' AND '2024-01-31';
```

### Deletar Registros Específicos

```sql
-- Deletar produto específico
DELETE FROM productos WHERE id = 123;

-- Deletar cliente específico
DELETE FROM clientes WHERE id = 456;
```

---

## 🛡️ Segurança e Boas Práticas

### 1. Sempre Fazer Backup

```bash
# Backup antes de qualquer operação
./backup-db.sh

# Ou manual
docker compose exec mysql mysqldump -u root -p${DB_PASSWORD} \
    ${DB_DATABASE} > backup_antes_delete_$(date +%Y%m%d_%H%M%S).sql
```

### 2. Usar Transações

```sql
-- Iniciar transação
START TRANSACTION;

-- Executar deletes
DELETE FROM cms_logs;
DELETE FROM activity_log;

-- Verificar resultado
SELECT COUNT(*) FROM cms_logs;

-- Se estiver OK, confirmar
COMMIT;

-- Se algo deu errado, reverter
-- ROLLBACK;
```

### 3. Testar em Desenvolvimento Primeiro

```bash
# Usar docker-compose.dev.yml para testar
docker compose -f docker-compose.dev.yml up -d
docker compose -f docker-compose.dev.yml exec mysql mysql -u root -p
```

---

## 📝 Exemplos Práticos

### Exemplo 1: Limpar Logs Semanalmente

```bash
# Adicionar ao cron
crontab -e

# Executar todo domingo às 3h
0 3 * * 0 cd /var/www/inventario && docker compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} -e "DELETE FROM cms_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);"
```

### Exemplo 2: Limpar Dados de Teste

```bash
# Script para ambiente de desenvolvimento
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} <<EOF
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE venta_detalles;
TRUNCATE TABLE compra_detalles;
TRUNCATE TABLE ventas;
TRUNCATE TABLE compras;
TRUNCATE TABLE productos;
SET FOREIGN_KEY_CHECKS = 1;
SELECT 'Dados de teste limpos!' as status;
EOF
```

### Exemplo 3: Limpar Tudo e Reimportar

```bash
# 1. Backup
./backup-db.sh

# 2. Limpar
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} <<EOF
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE venta_detalles;
TRUNCATE TABLE compra_detalles;
TRUNCATE TABLE ventas;
TRUNCATE TABLE compras;
TRUNCATE TABLE productos;
TRUNCATE TABLE clientes;
TRUNCATE TABLE proveedores;
TRUNCATE TABLE categorias;
SET FOREIGN_KEY_CHECKS = 1;
EOF

# 3. Reimportar dados
docker compose exec -T mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < database/dumps/01_Dump20220121_fixed.sql
```

---

## ⚡ Comandos Rápidos

```bash
# Limpar apenas logs
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} -e "TRUNCATE TABLE cms_logs; TRUNCATE TABLE activity_log;"

# Ver tamanho das tabelas
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} -e "
SELECT
    table_name AS 'Tabela',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamanho (MB)'
FROM information_schema.TABLES
WHERE table_schema = '${DB_DATABASE}'
ORDER BY (data_length + index_length) DESC;
"

# Contar registros em todas as tabelas
docker compose exec mysql mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} -e "
SELECT table_name AS 'Tabela', table_rows AS 'Registros'
FROM information_schema.tables
WHERE table_schema = '${DB_DATABASE}'
ORDER BY table_rows DESC;
"
```

---

## 🆘 Troubleshooting

### Erro: Cannot delete or update a parent row

```sql
-- Desabilitar foreign key checks temporariamente
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM sua_tabela;
SET FOREIGN_KEY_CHECKS = 1;
```

### Erro: Table is full

```sql
-- Otimizar tabela
OPTIMIZE TABLE cms_logs;
OPTIMIZE TABLE activity_log;
```

### Recuperar Dados Deletados

```bash
# Se fez backup antes
./restore-db.sh backups/backup_file.sql.gz
```

---

**⚠️ LEMBRE-SE:** Sempre faça backup antes de deletar dados em produção!
