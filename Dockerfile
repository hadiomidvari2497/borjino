# Borjino - PHP 8.4 + MySQL 8.4 Docker Configuration
# Multi-stage build for production

# ============================================
# Base PHP Image
# ============================================
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    postgresql-dev \
    mysql-client \
    nginx \
    supervisor \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    intl \
    zip \
    opcache \
    bcmath \
    && docker-php-ext-enable opcache

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create app user
RUN addgroup -g 1000 -S app && adduser -u 1000 -S app -G app

# ============================================
# Composer Stage
# ============================================
FROM composer:2.7 AS composer

WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader

# ============================================
# Development Stage
# ============================================
FROM base AS development

WORKDIR /var/www/html

# Install xdebug for development
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy composer cache
COPY --from=composer /app/vendor ./vendor

# Copy application code
COPY --chown=app:app . .

# Set permissions
RUN chown -R app:app /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

USER app

EXPOSE 9000

CMD ["php-fpm"]

# ============================================
# Production Stage
# ============================================
FROM base AS production

WORKDIR /var/www/html

# Copy composer dependencies
COPY --from=composer /app/vendor ./vendor

# Copy application code
COPY --chown=app:app . .

# Set permissions
RUN chown -R app:app /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Configure nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Configure supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER app

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]