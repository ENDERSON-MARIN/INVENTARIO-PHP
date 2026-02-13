# Docker Development Environment

This document provides instructions for running the Laravel 5.7 Inventory Management System using Docker Compose.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **Docker**: Version 20.10 or higher
    - [Install Docker on Windows](https://docs.docker.com/desktop/install/windows-install/)
    - [Install Docker on macOS](https://docs.docker.com/desktop/install/mac-install/)
    - [Install Docker on Linux](https://docs.docker.com/engine/install/)
- **Docker Compose**: Version 2.0 or higher (included with Docker Desktop)

Verify your installation:

```bash
docker --version
docker-compose --version
```

## First-Time Setup

Follow these steps to set up the development environment for the first time:

### 1. Copy Environment Configuration

Copy the Docker-specific environment file to `.env`:

```bash
copy .env.docker .env
```

On Linux/macOS:

```bash
cp .env.docker .env
```

### 2. Generate Application Key

Start the containers and generate the Laravel application key:

```bash
docker-compose up -d
docker-compose exec php php artisan key:generate
```

### 3. Run Database Migrations (if needed)

If you need to run migrations after the initial database setup:

```bash
docker-compose exec php php artisan migrate
```

### 4. Access the Application

- **Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **MySQL**: localhost:3306

Default database credentials:

- Username: `laravel`
- Password: `laravel_secret`
- Database: `laravel_inventory`

## Container Management

### Starting Containers

Start all containers in detached mode:

```bash
docker-compose up -d
```

Start containers and view logs:

```bash
docker-compose up
```

### Stopping Containers

Stop all running containers:

```bash
docker-compose stop
```

Stop and remove containers (data persists in volumes):

```bash
docker-compose down
```

### Restarting Containers

Restart all containers:

```bash
docker-compose restart
```

Restart a specific service:

```bash
docker-compose restart php
docker-compose restart nginx
docker-compose restart mysql
```

### Rebuilding Containers

If you modify Dockerfile or need to rebuild:

```bash
docker-compose up -d --build
```

Rebuild without cache:

```bash
docker-compose build --no-cache
docker-compose up -d
```

## Viewing Logs

View logs from all containers:

```bash
docker-compose logs
```

View logs from a specific service:

```bash
docker-compose logs php
docker-compose logs nginx
docker-compose logs mysql
```

Follow logs in real-time:

```bash
docker-compose logs -f
docker-compose logs -f php
```

View last 100 lines:

```bash
docker-compose logs --tail=100
```

## Accessing Container Shells

### PHP Container

Access the PHP container shell:

```bash
docker-compose exec php sh
```

### MySQL Container

Access the MySQL container shell:

```bash
docker-compose exec mysql bash
```

Connect to MySQL CLI:

```bash
docker-compose exec mysql mysql -ularavel -plaravel_secret laravel_inventory
```

### Nginx Container

Access the Nginx container shell:

```bash
docker-compose exec nginx sh
```

## Running Artisan Commands

Run artisan commands inside the PHP container:

```bash
# Clear caches
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan view:clear

# Run migrations
docker-compose exec php php artisan migrate
docker-compose exec php php artisan migrate:rollback
docker-compose exec php php artisan migrate:status

# Database seeding
docker-compose exec php php artisan db:seed

# List routes
docker-compose exec php php artisan route:list

# Generate application key
docker-compose exec php php artisan key:generate

# Create controllers, models, migrations
docker-compose exec php php artisan make:controller ControllerName
docker-compose exec php php artisan make:model ModelName
docker-compose exec php php artisan make:migration migration_name
```

## Running Composer Commands

Run Composer commands inside the PHP container:

```bash
# Install dependencies
docker-compose exec php composer install

# Update dependencies
docker-compose exec php composer update

# Add a package
docker-compose exec php composer require vendor/package

# Remove a package
docker-compose exec php composer remove vendor/package

# Dump autoload
docker-compose exec php composer dump-autoload
```

## Database Management

### Accessing phpMyAdmin

Open your browser and navigate to http://localhost:8080

The interface will automatically connect to the MySQL database with the configured credentials.

### Backing Up Database

Create a database backup:

```bash
docker-compose exec mysql mysqldump -ularavel -plaravel_secret laravel_inventory > backup.sql
```

### Restoring Database

Restore from a backup file:

```bash
docker-compose exec -T mysql mysql -ularavel -plaravel_secret laravel_inventory < backup.sql
```

### Resetting Database

To completely reset the database and start fresh:

```bash
# Stop containers
docker-compose down

# Remove the database volume
docker volume rm docker-compose-setup_mysql_data

# Start containers (database will reinitialize)
docker-compose up -d
```

Note: Replace `docker-compose-setup_mysql_data` with your actual volume name. Check with:

```bash
docker volume ls
```

### Viewing Database Tables

Connect to MySQL and list tables:

```bash
docker-compose exec mysql mysql -ularavel -plaravel_secret laravel_inventory -e "SHOW TABLES;"
```

## Troubleshooting

### Port Already in Use

**Problem**: Error message "Bind for 0.0.0.0:8000 failed: port is already allocated"

**Solution**: Another service is using the required port. Either stop the conflicting service or modify the port mapping in `docker-compose.yml`:

```yaml
ports:
    - "8001:80" # Change 8000 to 8001 or any available port
```

### Containers Keep Restarting

**Problem**: Containers are in a restart loop

**Solution**: Check the logs to identify the issue:

```bash
docker-compose logs php
docker-compose logs mysql
```

Common causes:

- MySQL not ready when PHP starts (wait a few more seconds)
- Database initialization errors (check SQL dump files)
- Permission issues (see below)

### Permission Denied Errors

**Problem**: Laravel shows "Permission denied" errors for storage or cache directories

**Solution**: Fix permissions from within the PHP container:

```bash
docker-compose exec php chown -R www-data:www-data storage bootstrap/cache
docker-compose exec php chmod -R 775 storage bootstrap/cache
```

### Composer Install Fails

**Problem**: Composer cannot install dependencies

**Solution**:

1. Check your internet connection
2. Clear Composer cache:

```bash
docker-compose exec php composer clear-cache
docker-compose exec php composer install
```

3. If specific packages fail, try updating:

```bash
docker-compose exec php composer update
```

### Database Connection Refused

**Problem**: Laravel cannot connect to MySQL

**Solution**:

1. Verify MySQL container is running:

```bash
docker-compose ps
```

2. Check that `.env` file has correct database settings:

```
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_inventory
DB_USERNAME=laravel
DB_PASSWORD=laravel_secret
```

3. Wait for MySQL to be fully ready (check health status):

```bash
docker-compose ps
```

4. Test MySQL connection:

```bash
docker-compose exec php php artisan migrate:status
```

### Application Shows 500 Error

**Problem**: Application returns HTTP 500 error

**Solution**:

1. Check Laravel logs:

```bash
docker-compose exec php cat storage/logs/laravel.log
```

2. Enable debug mode in `.env`:

```
APP_DEBUG=true
```

3. Clear all caches:

```bash
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan view:clear
```

### Changes Not Reflected

**Problem**: Code changes don't appear in the browser

**Solution**:

1. Clear Laravel caches:

```bash
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan view:clear
```

2. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)

3. Verify file synchronization:

```bash
docker-compose exec php ls -la /var/www/html
```

### MySQL Initialization Failed

**Problem**: Database tables are not created on first start

**Solution**:

1. Check MySQL logs:

```bash
docker-compose logs mysql
```

2. Verify SQL dump files exist:

```bash
ls -la database/dumps/
```

3. Reset and reinitialize:

```bash
docker-compose down
docker volume rm docker-compose-setup_mysql_data
docker-compose up -d
```

4. Manually import if needed:

```bash
docker-compose exec -T mysql mysql -ularavel -plaravel_secret laravel_inventory < database/dumps/01_Dump20220121.sql
docker-compose exec -T mysql mysql -ularavel -plaravel_secret laravel_inventory < database/dumps/02_triggers_PRODUCTOS.sql
```

### Out of Disk Space

**Problem**: Docker reports "no space left on device"

**Solution**:

1. Clean up unused Docker resources:

```bash
docker system prune -a
docker volume prune
```

2. Remove old containers and images:

```bash
docker-compose down
docker system df  # Check disk usage
```

### Slow Performance on Windows

**Problem**: Application runs slowly on Windows

**Solution**:

1. Ensure WSL 2 is enabled (not Hyper-V)
2. Store project files in WSL filesystem, not Windows filesystem
3. Increase Docker Desktop resources (Settings > Resources)
4. Consider using named volumes for vendor directory

## Development Workflow

### Typical Development Session

```bash
# Start containers
docker-compose up -d

# View logs if needed
docker-compose logs -f php

# Make code changes in your editor
# Changes are automatically synced to containers

# Clear caches if needed
docker-compose exec php php artisan cache:clear

# Run migrations if you created new ones
docker-compose exec php php artisan migrate

# Stop containers when done
docker-compose stop
```

### Running Tests

```bash
# Run PHPUnit tests
docker-compose exec php vendor/bin/phpunit

# Run specific test
docker-compose exec php vendor/bin/phpunit --filter TestName
```

### Installing New Packages

```bash
# Add package via Composer
docker-compose exec php composer require vendor/package

# If package requires configuration
docker-compose exec php php artisan vendor:publish
```

## Additional Resources

- [Laravel 5.7 Documentation](https://laravel.com/docs/5.7)
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [CRUDBooster Documentation](https://crudbooster.com/documentation)

## Getting Help

If you encounter issues not covered in this guide:

1. Check container logs: `docker-compose logs`
2. Verify all containers are running: `docker-compose ps`
3. Check Docker disk space: `docker system df`
4. Review Laravel logs: `storage/logs/laravel.log`
5. Consult the main project README for application-specific guidance

## Clean Slate Reset

To completely reset your development environment:

```bash
# Stop and remove all containers
docker-compose down

# Remove all volumes (WARNING: deletes all data)
docker volume rm docker-compose-setup_mysql_data

# Remove vendor directory (optional)
rm -rf vendor

# Start fresh
docker-compose up -d

# Reinstall dependencies
docker-compose exec php composer install

# Generate new key
docker-compose exec php php artisan key:generate

# Run migrations
docker-compose exec php php artisan migrate
```
