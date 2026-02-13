# Docker Cache Optimization

## Como o Cache Funciona

O Docker usa um sistema de cache de camadas. Cada instrução no Dockerfile cria uma camada, e o Docker reutiliza camadas que não mudaram.

## Otimizações Implementadas

### 1. Ordem das Camadas no Dockerfile

As camadas estão organizadas da menos frequente para a mais frequente de mudança:

```dockerfile
# Camada 1: Dependências do sistema (raramente muda)
RUN apk add --no-cache libpng-dev libjpeg-turbo-dev ...

# Camada 2: Extensões PHP (raramente muda)
RUN docker-php-ext-install pdo_mysql mbstring ...

# Camada 3: Composer (raramente muda)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Camada 4: Arquivos composer.json/lock (muda quando adiciona dependências)
COPY composer.json composer.lock ./

# Camada 5: Instalação de dependências PHP (usa cache se composer.json não mudou)
RUN composer install --no-dev --no-scripts --no-autoload

# Camada 6: Código da aplicação (muda frequentemente)
COPY . .

# Camada 7: Otimização final
RUN composer dump-autoload --optimize
```

### 2. Benefícios do Cache

- **Dependências do sistema**: Instaladas apenas uma vez, reutilizadas sempre
- **Extensões PHP**: Compiladas apenas uma vez
- **Dependências Composer**: Reinstaladas apenas quando composer.json ou composer.lock mudam
- **Código da aplicação**: Copiado rapidamente, não afeta camadas anteriores

### 3. Volume para Vendor (Desenvolvimento)

Para desenvolvimento local, o `vendor/` é montado como volume, então:

- Mudanças no código não requerem rebuild
- O entrypoint verifica e instala dependências se necessário
- Hot reload funciona perfeitamente

## Comandos Úteis

### Build com Cache

```bash
# Build normal (usa cache)
docker-compose build

# Build forçando download de novas imagens base
docker-compose build --pull
```

### Build sem Cache (quando necessário)

```bash
# Rebuild completo sem usar cache
docker-compose build --no-cache

# Rebuild apenas do serviço PHP
docker-compose build --no-cache php
```

### Limpar Cache do Docker

```bash
# Remover imagens não utilizadas
docker image prune

# Remover tudo (imagens, containers, volumes, networks)
docker system prune -a --volumes
```

## Quando o Cache é Invalidado

O cache de uma camada é invalidado quando:

1. **Você muda o Dockerfile** na linha ou acima da camada
2. **Arquivos copiados mudam** (COPY ou ADD)
3. **Você usa --no-cache** no build

## Exemplo Prático

### Cenário 1: Mudança no código PHP

```bash
# Você edita app/Http/Controllers/AdminProductosController.php
docker-compose up -d
# ✅ Rápido! Apenas copia novos arquivos, reutiliza todas as camadas anteriores
```

### Cenário 2: Adiciona nova dependência Composer

```bash
# Você adiciona um pacote no composer.json
composer require guzzlehttp/guzzle
docker-compose build php
# ⚠️ Reinstala dependências, mas reutiliza camadas de sistema e PHP
```

### Cenário 3: Atualiza versão do PHP

```bash
# Você muda FROM php:7.4-fpm-alpine para php:8.0-fpm-alpine
docker-compose build --no-cache php
# ❌ Rebuild completo necessário
```

## Dicas para Maximizar Cache

1. **Não mude o Dockerfile** desnecessariamente
2. **Use .dockerignore** para excluir arquivos que mudam frequentemente
3. **Separe dependências de código** (já implementado)
4. **Use volumes para desenvolvimento** (já configurado)
5. **Mantenha composer.lock** no controle de versão

## Verificar Uso de Cache

Durante o build, você verá:

```
 => CACHED [2/8] RUN apk add --no-cache libpng-dev...
 => CACHED [3/8] RUN docker-php-ext-configure gd...
 => CACHED [4/8] COPY --from=composer:2...
 => [5/8] COPY composer.json composer.lock ./
```

"CACHED" indica que a camada foi reutilizada! 🎉
