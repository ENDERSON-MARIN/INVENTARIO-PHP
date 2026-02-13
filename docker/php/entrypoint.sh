#!/bin/sh
set -e

echo "Starting PHP-FPM entrypoint script..."

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
MYSQL_HOST="${DB_HOST:-mysql}"
MYSQL_PORT="${DB_PORT:-3306}"
MAX_RETRIES=15
RETRY_COUNT=0

until nc -z "$MYSQL_HOST" "$MYSQL_PORT" > /dev/null 2>&1; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "Error: MySQL not available after 30 seconds"
        exit 1
    fi
    echo "Waiting for MySQL... (attempt $RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done

echo "MySQL is ready!"

# Install Composer dependencies if vendor directory is missing
if [ ! -d "vendor" ]; then
    echo "vendor/ directory not found. Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
    echo "Composer dependencies installed successfully"
else
    echo "vendor/ directory exists, skipping composer install"
fi

# Set proper permissions for Laravel directories
echo "Setting permissions for storage and bootstrap/cache directories..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo "Permissions set successfully"

echo "Entrypoint script completed. Starting PHP-FPM..."

# Execute the main command (php-fpm)
exec "$@"
