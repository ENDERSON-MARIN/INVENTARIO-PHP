# Database Dumps - Restauração

Este diretório contém os dumps do banco de dados `inventas` para restauração.

## Arquivos

1. **00_create_database.sql** - Cria o banco de dados com codificação UTF-8 correta
2. **01_Dump20220121.sql** - Dump principal com todas as tabelas e dados
3. **02_triggers_PRODUCTOS.sql** - Triggers para controle automático de estoque

## Restauração Rápida (Windows)

Execute o script automatizado na raiz do projeto:

```cmd
restore_database.bat
```

O script irá solicitar a senha do MySQL e executar todos os passos automaticamente.

## Restauração Manual

### Opção 1: Linha de Comando

```cmd
# 1. Criar banco de dados
mysql -u root -p < database/dumps/00_create_database.sql

# 2. Restaurar dump principal
mysql -u root -p inventas < database/dumps/01_Dump20220121.sql

# 3. Criar triggers
mysql -u root -p inventas < database/dumps/02_triggers_PRODUCTOS.sql
```

### Opção 2: MySQL Workbench

1. Abra o MySQL Workbench
2. Conecte ao servidor MySQL
3. Execute cada arquivo SQL na ordem:
    - File → Open SQL Script → Selecione `00_create_database.sql` → Execute
    - File → Open SQL Script → Selecione `01_Dump20220121.sql` → Execute
    - File → Open SQL Script → Selecione `02_triggers_PRODUCTOS.sql` → Execute

### Opção 3: phpMyAdmin

1. Acesse phpMyAdmin
2. Clique em "SQL" no menu superior
3. Copie e cole o conteúdo de `00_create_database.sql` → Execute
4. Selecione o banco `inventas` na barra lateral
5. Clique em "Importar"
6. Importe `01_Dump20220121.sql`
7. Clique em "SQL" novamente
8. Copie e cole o conteúdo de `02_triggers_PRODUCTOS.sql` → Execute

## Configuração do Laravel

Após restaurar o banco de dados, atualize o arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventas
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Limpe o cache de configuração:

```cmd
php artisan config:clear
php artisan cache:clear
```

## Verificação

Verifique se o banco foi criado corretamente:

```sql
USE inventas;

-- Verificar codificação
SHOW CREATE DATABASE inventas;

-- Listar tabelas
SHOW TABLES;

-- Verificar triggers
SHOW TRIGGERS;

-- Contar registros
SELECT 'categorias' as tabela, COUNT(*) as registros FROM categorias
UNION ALL
SELECT 'productos', COUNT(*) FROM productos
UNION ALL
SELECT 'clientes', COUNT(*) FROM clientes
UNION ALL
SELECT 'proveedores', COUNT(*) FROM proveedores;
```

## Estrutura do Banco

O banco `inventas` contém:

- **Tabelas CMS**: cms_users, cms_privileges, cms_logs, etc.
- **Entidades de Negócio**: categorias, productos, clientes, proveedores
- **Transações**: compras, ventas, compra_detalles, venta_detalles
- **Triggers**: Controle automático de estoque em produtos

## Codificação

O banco é criado com:

- **Character Set**: utf8mb4
- **Collation**: utf8mb4_unicode_ci

Isso garante suporte completo para caracteres especiais em espanhol e emojis.

## Troubleshooting

### Erro: "Database exists"

Execute: `DROP DATABASE inventas;` antes de restaurar

### Erro: "Access denied"

Verifique as credenciais do MySQL no comando

### Erro: "Unknown database"

Execute primeiro o `00_create_database.sql`

### Caracteres estranhos após restauração

Certifique-se de que executou o `00_create_database.sql` primeiro
