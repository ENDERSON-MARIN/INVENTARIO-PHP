# Design Document: Docker Compose Setup for Laravel 5.7

## Overview

This design provides a complete Docker Compose configuration for local development of the Laravel 5.7 inventory management system. The architecture follows Docker best practices with separate containers for each service (PHP-FPM, Nginx, MySQL, phpMyAdmin), proper networking, volume management, and automated initialization.

The design emphasizes developer experience with hot reload support, automatic database setup, and convenient access to all services. All containers use official or well-maintained images optimized for development workflows.

## Architecture

### Container Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                         Host Machine                         │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │              Docker Compose Network                     │ │
│  │                  (laravel_network)                      │ │
│  │                                                         │ │
│  │  ┌──────────┐      ┌──────────┐      ┌──────────┐    │ │
│  │  │  Nginx   │─────▶│   PHP    │─────▶│  MySQL   │    │ │
│  │  │  :8000   │      │   FPM    │      │  :3306   │    │ │
│  │  └──────────┘      └──────────┘      └──────────┘    │ │
│  │       │                  │                  │          │ │
│  │       │                  │                  │          │ │
│  │       ▼                  ▼                  ▼          │ │
│  │  ┌──────────────────────────────────────────────┐    │ │
│  │  │        Application Code (Volume Mount)       │    │ │
│  │  └──────────────────────────────────────────────┘    │ │
│  │                                                         │ │
│  │  ┌──────────┐                                          │ │
│  │  │phpMyAdmin│──────────────────────────────────────┐  │ │
│  │  │  :8080   │                                       │  │ │
│  │  └──────────┘                                       │  │ │
│  │       │                                             │  │ │
│  │       └─────────────────────────────────────────────┘  │ │
│  │                                                         │ │
│  └─────────────────────────────────────────────────────┘ │
│                                                              │
│  Persistent Volumes:                                        │
│  • mysql_data (Database files)                             │
│  • ./app → /var/www/html (Code sync)                      │
└─────────────────────────────────────────────────────────────┘
```

### Request Flow

1. Browser sends HTTP request to localhost:8000
2. Nginx receives request and checks if it's a static file
3. If static file exists, Nginx serves it directly
4. If not, Nginx forwards to PHP-FPM via FastCGI protocol
5. PHP-FPM executes Laravel application code
6. Laravel connects to MySQL via service name "mysql"
7. Response flows back through PHP-FPM → Nginx → Browser

### Service Communication

- **Nginx → PHP-FPM**: FastCGI protocol on port 9000 (internal)
- **PHP-FPM → MySQL**: MySQL protocol on port 3306 (internal)
- **phpMyAdmin → MySQL**: MySQL protocol on port 3306 (internal)
- **Host → Nginx**: HTTP on port 8000 (exposed)
- **Host → MySQL**: MySQL protocol on port 3306 (exposed)
- **Host → phpMyAdmin**: HTTP on port 8080 (exposed)

## Components and Interfaces

### 1. PHP-FPM Container

**Base Image**: `php:7.4-fpm-alpine`

**Purpose**: Execute PHP code for the Laravel application

**Extensions Required**:

- pdo_mysql (database connectivity)
- mbstring (multibyte string handling)
- xml (XML parsing)
- bcmath (arbitrary precision mathematics)
- gd (image processing)
- zip (archive handling)
- opcache (performance optimization)

**Dockerfile Configuration**:

```dockerfile
FROM php:7.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring xml bcmath gd zip opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
```

**Volume Mounts**:

- `./:/var/www/html` (application code)

**Environment Variables**:

- Reads from `.env` file in application root

**Entrypoint Script**:

```bash
#!/bin/sh
# Wait for MySQL to be ready
until nc -z mysql 3306; do
    echo "Waiting for MySQL..."
    sleep 2
done

# Install dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Start PHP-FPM
php-fpm
```

### 2. Nginx Container

**Base Image**: `nginx:alpine`

**Purpose**: Serve static files and proxy PHP requests to PHP-FPM

**Configuration File** (`nginx.conf`):

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Volume Mounts**:

- `./:/var/www/html` (application code, read-only)
- `./docker/nginx/nginx.conf:/etc/nginx/conf.d/default.conf` (configuration)

**Port Mapping**: `8000:80`

**Dependencies**: Waits for PHP container

### 3. MySQL Container

**Base Image**: `mysql:5.7`

**Purpose**: Provide persistent database storage

**Environment Variables**:

- `MYSQL_ROOT_PASSWORD=root_secret`
- `MYSQL_DATABASE=laravel_inventory`
- `MYSQL_USER=laravel`
- `MYSQL_PASSWORD=laravel_secret`

**Volume Mounts**:

- `mysql_data:/var/lib/mysql` (persistent data)
- `./database/dumps:/docker-entrypoint-initdb.d` (initialization scripts)

**Port Mapping**: `3306:3306`

**Initialization Process**:

1. On first start, MySQL creates the database specified in `MYSQL_DATABASE`
2. MySQL executes all `.sql` files in `/docker-entrypoint-initdb.d` in alphabetical order
3. Files are executed as root user
4. If database already exists, initialization is skipped

**Character Set Configuration**:

```
--character-set-server=utf8mb4
--collation-server=utf8mb4_unicode_ci
```

### 4. phpMyAdmin Container

**Base Image**: `phpmyadmin:latest`

**Purpose**: Provide web-based database management interface

**Environment Variables**:

- `PMA_HOST=mysql`
- `PMA_PORT=3306`
- `PMA_USER=laravel`
- `PMA_PASSWORD=laravel_secret`

**Port Mapping**: `8080:80`

**Dependencies**: Waits for MySQL container

### 5. Docker Compose Configuration

**File**: `docker-compose.yml`

**Network**: Custom bridge network `laravel_network`

**Services Definition**:

```yaml
version: "3.8"

services:
    mysql:
        image: mysql:5.7
        container_name: laravel_mysql
        restart: unless-stopped
        environment:
            MYSQL_ROOT_PASSWORD: root_secret
            MYSQL_DATABASE: laravel_inventory
            MYSQL_USER: laravel
            MYSQL_PASSWORD: laravel_secret
        command: --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
        volumes:
            - mysql_data:/var/lib/mysql
            - ./database/dumps:/docker-entrypoint-initdb.d
        ports:
            - "3306:3306"
        networks:
            - laravel_network
        healthcheck:
            test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
            interval: 10s
            timeout: 5s
            retries: 5

    php:
        build:
            context: .
            dockerfile: docker/php/Dockerfile
        container_name: laravel_php
        restart: unless-stopped
        working_dir: /var/www/html
        volumes:
            - ./:/var/www/html
        networks:
            - laravel_network
        depends_on:
            mysql:
                condition: service_healthy
        deploy:
            resources:
                limits:
                    memory: 512M

    nginx:
        image: nginx:alpine
        container_name: laravel_nginx
        restart: unless-stopped
        ports:
            - "8000:80"
        volumes:
            - ./:/var/www/html:ro
            - ./docker/nginx/nginx.conf:/etc/nginx/conf.d/default.conf
        networks:
            - laravel_network
        depends_on:
            - php

    phpmyadmin:
        image: phpmyadmin:latest
        container_name: laravel_phpmyadmin
        restart: unless-stopped
        environment:
            PMA_HOST: mysql
            PMA_PORT: 3306
            PMA_USER: laravel
            PMA_PASSWORD: laravel_secret
        ports:
            - "8080:80"
        networks:
            - laravel_network
        depends_on:
            mysql:
                condition: service_healthy

networks:
    laravel_network:
        driver: bridge

volumes:
    mysql_data:
        driver: local
```

## Data Models

### Environment Configuration Model

**File**: `.env.docker`

```
APP_NAME="Laravel Inventory"
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_inventory
DB_USERNAME=laravel
DB_PASSWORD=laravel_secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

### Volume Mount Model

**Application Code Mount**:

- **Source**: `./` (project root on host)
- **Target**: `/var/www/html` (container working directory)
- **Mode**: Read-write for PHP container, read-only for Nginx
- **Excludes**: None (vendor and node_modules are in .dockerignore)

**Database Data Mount**:

- **Type**: Named volume
- **Name**: `mysql_data`
- **Target**: `/var/lib/mysql`
- **Persistence**: Survives container removal

**Database Initialization Mount**:

- **Source**: `./database/dumps`
- **Target**: `/docker-entrypoint-initdb.d`
- **Mode**: Read-only
- **Purpose**: Automatic SQL import on first start

### Network Model

**Network Type**: Bridge network

**Network Name**: `laravel_network`

**Service Hostnames**:

- `mysql` → MySQL container
- `php` → PHP-FPM container
- `nginx` → Nginx container
- `phpmyadmin` → phpMyAdmin container

**DNS Resolution**: Automatic via Docker's embedded DNS server

## Correctness Properties

_A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees._

For Docker infrastructure setup, most properties are specific configuration validations rather than universal algorithmic properties. The following properties verify that the Docker Compose configuration correctly implements the requirements.

### Configuration Properties

**Property 1: PHP Runtime Completeness**
_For any_ Laravel 5.7 application requirement, the PHP container should have all necessary extensions and tools installed to execute the application without missing dependencies.
**Validates: Requirements 1.1, 1.2, 1.3**

**Property 2: Service Accessibility**
_For any_ exposed service port (web server, database, phpMyAdmin), a connection from the host machine to that port should successfully reach the corresponding container service.
**Validates: Requirements 2.4, 3.5, 5.2**

**Property 3: Database Initialization Idempotency**
_For any_ number of container restarts, the database initialization scripts should execute exactly once on first start and be skipped on subsequent starts, maintaining data integrity.
**Validates: Requirements 4.1, 4.2, 4.3**

**Property 4: Code Synchronization**
_For any_ file modification in the application directory on the host, the change should be immediately visible in both the PHP and Nginx containers without requiring a container restart.
**Validates: Requirements 6.1, 6.2, 6.4**

**Property 5: Configuration Consistency**
_For any_ database connection parameter (host, database name, username, password), the value in .env.docker should match the corresponding value in docker-compose.yml, ensuring the application can connect to the database.
**Validates: Requirements 7.2, 7.3**

**Property 6: Service Dependency Order**
_For any_ service with dependencies, the dependent service should not start until all its dependency services report healthy status, preventing connection failures during startup.
**Validates: Requirements 8.1, 8.2, 8.3, 8.4**

**Property 7: Network Communication**
_For any_ pair of services that need to communicate (PHP→MySQL, Nginx→PHP, phpMyAdmin→MySQL), the services should be able to resolve each other's hostnames and establish connections using service names.
**Validates: Requirements 9.2, 9.3, 9.4**

**Property 8: Data Persistence Across Lifecycle**
_For any_ data written to the MySQL database, the data should persist across container stops, starts, and even container removal (as long as the volume is not explicitly deleted).
**Validates: Requirements 11.1, 11.2, 11.3**

### Example-Based Validations

The following are specific configuration checks that validate exact requirements:

**Example 1: PHP Version Verification**
Verify that `php -v` in the PHP container returns version 7.4.x.
**Validates: Requirements 1.1**

**Example 2: Nginx Document Root**
Verify that nginx.conf contains `root /var/www/html/public;`.
**Validates: Requirements 2.2**

**Example 3: Laravel URL Rewriting**
Verify that a request to a Laravel route (e.g., `/api/test`) is processed by index.php and not returned as 404.
**Validates: Requirements 2.5**

**Example 4: MySQL Version and Character Set**
Verify that MySQL version is 5.7.x and default character set is utf8mb4_unicode_ci.
**Validates: Requirements 3.1, 3.6**

**Example 5: Database Creation**
Verify that database "laravel_inventory" exists after first container start.
**Validates: Requirements 3.2**

**Example 6: Authentication Credentials**
Verify that connection to MySQL using username "laravel" and password "laravel_secret" succeeds.
**Validates: Requirements 3.3**

**Example 7: SQL Dump Import**
Verify that tables from Dump20220121.sql exist in the database after first start.
**Validates: Requirements 4.1**

**Example 8: Trigger Creation**
Verify that triggers defined in triggers_PRODUCTOS.sql exist in information_schema.triggers.
**Validates: Requirements 4.2**

**Example 9: phpMyAdmin Connectivity**
Verify that accessing localhost:8080 loads phpMyAdmin and shows the laravel_inventory database.
**Validates: Requirements 5.1, 5.2, 5.3**

**Example 10: File Permissions**
Verify that www-data user can write to storage/ and bootstrap/cache/ directories.
**Validates: Requirements 1.4, 6.3**

**Example 11: Automatic Composer Install**
Verify that starting the container without a vendor/ directory triggers `composer install` and creates the vendor/ directory.
**Validates: Requirements 1.5**

**Example 12: Environment Variable Loading**
Verify that Laravel can read DB_HOST from .env and it equals "mysql".
**Validates: Requirements 7.6**

**Example 13: Health Check Configuration**
Verify that docker-compose.yml contains healthcheck directives for MySQL service.
**Validates: Requirements 8.5**

**Example 14: Network Creation**
Verify that `docker network ls` shows "laravel_network" with bridge driver after `docker-compose up`.
**Validates: Requirements 9.1**

**Example 15: Volume Configuration**
Verify that docker-compose.yml defines a named volume "mysql_data" and it's mounted to /var/lib/mysql.
**Validates: Requirements 11.1**

**Example 16: Resource Limits**
Verify that docker-compose.yml contains memory limits of 512M for PHP and 1GB for MySQL.
**Validates: Requirements 12.1, 12.2**

**Example 17: Alpine Base Images**
Verify that PHP and Nginx containers use Alpine-based images.
**Validates: Requirements 12.3**

## Error Handling

### Container Startup Failures

**MySQL Connection Timeout**:

- **Scenario**: PHP container starts before MySQL is ready
- **Handling**: Entrypoint script includes retry loop with `nc -z mysql 3306`
- **Timeout**: 30 seconds (15 retries × 2 seconds)
- **Error Message**: "Waiting for MySQL..." (informational), "MySQL not available after 30 seconds" (error)

**Composer Install Failure**:

- **Scenario**: Composer dependencies cannot be installed
- **Handling**: Script exits with error code, container stops
- **Error Message**: Composer's native error output
- **Recovery**: User must fix composer.json or network issues and restart

**Database Initialization Failure**:

- **Scenario**: SQL dump contains errors or invalid syntax
- **Handling**: MySQL logs error and continues (doesn't stop container)
- **Error Message**: MySQL error log in container output
- **Recovery**: User must fix SQL file and recreate volume/container

### Permission Errors

**Storage Directory Not Writable**:

- **Scenario**: www-data cannot write to storage/
- **Handling**: Entrypoint script sets permissions with `chown -R www-data:www-data`
- **Fallback**: If chown fails, container continues but Laravel will error on first write
- **Error Message**: Laravel's native permission error

**Volume Mount Permission Issues**:

- **Scenario**: Host filesystem permissions conflict with container user
- **Handling**: Use www-data (UID 82 in Alpine) for all operations
- **Recommendation**: Document that users may need to adjust host permissions

### Network Errors

**Service Name Resolution Failure**:

- **Scenario**: Container cannot resolve service hostname
- **Handling**: Docker's embedded DNS should handle this automatically
- **Error Message**: "Could not resolve host: mysql"
- **Recovery**: Verify all services are on the same network in docker-compose.yml

**Port Already in Use**:

- **Scenario**: Host ports 8000, 3306, or 8080 already bound
- **Handling**: Docker Compose fails to start with clear error
- **Error Message**: "Bind for 0.0.0.0:8000 failed: port is already allocated"
- **Recovery**: User must stop conflicting service or change port mapping

### Volume Errors

**Volume Mount Failure**:

- **Scenario**: Application directory cannot be mounted
- **Handling**: Docker Compose fails to start
- **Error Message**: Docker's native mount error
- **Recovery**: Verify path exists and Docker has access

**Disk Space Exhausted**:

- **Scenario**: No space for MySQL data or logs
- **Handling**: MySQL fails to write, container may crash
- **Error Message**: "No space left on device"
- **Recovery**: User must free disk space and restart

## Testing Strategy

### Unit Testing Approach

Since this is infrastructure configuration rather than application code, traditional unit tests are not applicable. Instead, we use **integration tests** that verify the Docker Compose setup works correctly.

### Integration Testing with Docker

**Test Framework**: Shell scripts or Docker-based test containers

**Test Categories**:

1. **Container Health Tests**
    - Verify all containers start successfully
    - Verify health checks pass
    - Verify no containers are in restart loop

2. **Service Connectivity Tests**
    - Test HTTP connection to Nginx on port 8000
    - Test MySQL connection on port 3306
    - Test phpMyAdmin access on port 8080
    - Test PHP→MySQL connection from within PHP container
    - Test Nginx→PHP FastCGI connection

3. **Configuration Validation Tests**
    - Verify PHP version and extensions
    - Verify Nginx configuration syntax
    - Verify MySQL character set and collation
    - Verify environment variables are loaded
    - Verify volume mounts are correct

4. **Functional Tests**
    - Test Laravel application loads (HTTP 200 response)
    - Test database query execution
    - Test file write to storage directory
    - Test hot reload (modify file, verify change reflected)
    - Test Composer install on first start

5. **Persistence Tests**
    - Write data to database
    - Stop and restart containers
    - Verify data still exists
    - Test volume persistence after `docker-compose down`

6. **Initialization Tests**
    - Start with fresh volume
    - Verify SQL dump imported
    - Verify triggers created
    - Restart containers
    - Verify initialization doesn't run again

### Test Execution

**Setup**:

```bash
# Clean environment
docker-compose down -v
docker volume prune -f

# Start services
docker-compose up -d

# Wait for services to be ready
sleep 10
```

**Example Test Script**:

```bash
#!/bin/bash

# Test 1: Verify all containers are running
echo "Test 1: Container health"
if [ $(docker-compose ps | grep "Up" | wc -l) -eq 4 ]; then
    echo "✓ All containers running"
else
    echo "✗ Some containers not running"
    exit 1
fi

# Test 2: Verify web server responds
echo "Test 2: Web server accessibility"
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200\|302"; then
    echo "✓ Web server accessible"
else
    echo "✗ Web server not accessible"
    exit 1
fi

# Test 3: Verify MySQL connection
echo "Test 3: MySQL connectivity"
if docker-compose exec -T mysql mysql -ularavel -plaravel_secret -e "SELECT 1" > /dev/null 2>&1; then
    echo "✓ MySQL connection successful"
else
    echo "✗ MySQL connection failed"
    exit 1
fi

# Test 4: Verify database exists
echo "Test 4: Database creation"
if docker-compose exec -T mysql mysql -ularavel -plaravel_secret -e "SHOW DATABASES" | grep -q "laravel_inventory"; then
    echo "✓ Database exists"
else
    echo "✗ Database not found"
    exit 1
fi

# Test 5: Verify PHP version
echo "Test 5: PHP version"
if docker-compose exec -T php php -v | grep -q "PHP 7.4"; then
    echo "✓ PHP 7.4 installed"
else
    echo "✗ Wrong PHP version"
    exit 1
fi

# Test 6: Verify PHP extensions
echo "Test 6: PHP extensions"
REQUIRED_EXTS="pdo_mysql mbstring xml bcmath gd zip"
for ext in $REQUIRED_EXTS; do
    if docker-compose exec -T php php -m | grep -q "$ext"; then
        echo "✓ Extension $ext installed"
    else
        echo "✗ Extension $ext missing"
        exit 1
    fi
done

# Test 7: Verify file synchronization
echo "Test 7: Hot reload"
echo "<?php echo 'test';" > test_sync.php
sleep 2
if docker-compose exec -T php test -f /var/www/html/test_sync.php; then
    echo "✓ File synchronized"
    rm test_sync.php
else
    echo "✗ File not synchronized"
    exit 1
fi

# Test 8: Verify data persistence
echo "Test 8: Data persistence"
docker-compose exec -T mysql mysql -ularavel -plaravel_secret laravel_inventory -e "CREATE TABLE IF NOT EXISTS test_persist (id INT)"
docker-compose restart mysql
sleep 5
if docker-compose exec -T mysql mysql -ularavel -plaravel_secret laravel_inventory -e "SHOW TABLES" | grep -q "test_persist"; then
    echo "✓ Data persisted"
    docker-compose exec -T mysql mysql -ularavel -plaravel_secret laravel_inventory -e "DROP TABLE test_persist"
else
    echo "✗ Data not persisted"
    exit 1
fi

echo "All tests passed!"
```

### Manual Testing Checklist

After running automated tests, perform these manual verifications:

1. **Access Application**
    - Open http://localhost:8000 in browser
    - Verify Laravel welcome page or application loads
    - Check browser console for errors

2. **Access phpMyAdmin**
    - Open http://localhost:8080 in browser
    - Verify automatic login works
    - Browse laravel_inventory database
    - Verify tables from SQL dump exist

3. **Test Laravel Functionality**
    - Navigate to CRUDBooster admin panel
    - Test login functionality
    - Verify database queries work
    - Test CRUD operations on any entity

4. **Test Development Workflow**
    - Modify a PHP file
    - Refresh browser without restarting containers
    - Verify change is reflected
    - Run `docker-compose exec php php artisan cache:clear`
    - Verify command executes successfully

5. **Test Artisan Commands**
    - Run `docker-compose exec php php artisan migrate:status`
    - Run `docker-compose exec php php artisan route:list`
    - Verify commands execute without errors

### Continuous Validation

**Pre-commit Hook** (optional):

- Run docker-compose config validation
- Verify all required files exist
- Check for syntax errors in configuration files

**CI/CD Integration** (optional):

- Run full test suite on pull requests
- Verify Docker Compose setup works in clean environment
- Test on multiple host operating systems (Linux, macOS, Windows)

### Property-Based Testing

For this infrastructure setup, property-based testing is less applicable than for application code. However, we can apply property thinking to some scenarios:

**Property Test 1: Service Restart Resilience**
_For any_ sequence of container stops and starts, the system should eventually reach a healthy state where all services are running and communicating.

**Implementation**: Use a property test framework to generate random sequences of `docker-compose stop <service>` and `docker-compose start <service>` commands, then verify system health.

**Property Test 2: Configuration Consistency**
_For any_ configuration value that appears in multiple files (e.g., database credentials), all occurrences should have the same value.

**Implementation**: Parse docker-compose.yml and .env.docker, extract common configuration keys, verify values match.

**Property Test 3: Volume Mount Bidirectionality**
_For any_ file created in a mounted directory (either in container or on host), the file should be visible from both sides.

**Implementation**: Generate random filenames, create them alternately in container and on host, verify visibility from both sides.

These property tests would be implemented using a testing framework like QuickCheck (Haskell), Hypothesis (Python), or fast-check (JavaScript), with minimum 100 iterations per test.

### Test Documentation

All tests should be documented in a `tests/docker/README.md` file with:

- Purpose of each test
- How to run tests
- Expected output
- Troubleshooting common failures
- How to add new tests
