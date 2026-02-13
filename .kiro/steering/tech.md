# Technology Stack

## Framework & Core

- Laravel 5.7 (PHP 7.1.3+)
- CRUDBooster 5.4 - Admin panel generator
- Composer for dependency management

## Key Dependencies

- Laravel Collective HTML - Form/HTML helpers
- Spatie Laravel Activity Log 2.8.4 - Activity tracking
- RealRashid SweetAlert - User notifications
- Fideloper Proxy - Trusted proxy handling

## Development Tools

- PHPUnit 7.0+ - Testing framework
- Laravel Debugbar - Development debugging
- Laravel Dump Server - Request debugging
- Faker - Test data generation
- Mockery - Mocking framework

## Database

- MySQL/MariaDB (configured via Laravel)
- Eloquent ORM with soft deletes
- Database triggers for stock management

## Code Style

- PSR-4 autoloading
- 4 spaces for indentation
- LF line endings
- UTF-8 encoding

## Common Commands

### Development

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Testing

```bash
# Run all tests
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit --filter TestName
```

### Composer

```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Dump autoload
composer dump-autoload
```

### Artisan

```bash
# List all commands
php artisan list

# Generate application key
php artisan key:generate

# Create controller
php artisan make:controller ControllerName

# Create model
php artisan make:model ModelName

# Create migration
php artisan make:migration migration_name
```
