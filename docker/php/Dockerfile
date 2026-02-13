FROM php:7.4-fpm-alpine

# Install system dependencies (cached layer - rarely changes)
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    netcat-openbsd

# Configure and install PHP extensions (cached layer - rarely changes)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        xml \
        bcmath \
        gd \
        zip \
        opcache

# Install Composer 2 (cached layer - rarely changes)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better cache utilization)
COPY composer.json composer.lock ./

# Install PHP dependencies (cached if composer files don't change)
# Note: Using --dev for local development. For production, use --no-dev
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Copy application code
COPY . .

# Complete composer setup and optimize autoloader
RUN composer dump-autoload --optimize

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start PHP-FPM
CMD ["php-fpm"]
