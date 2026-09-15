# ==========================================
# Stage 1: Build frontend assets
# ==========================================
FROM node:20-alpine AS frontend

WORKDIR /var/www/html

COPY package.json ./

RUN npm install

COPY . .

RUN npm run build


# ==========================================
# Stage 2: Install PHP dependencies
# ==========================================
FROM composer:2 AS composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ==========================================
# Stage 3: PHP-FPM application
# ==========================================
FROM php:8.3-fpm

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Install PHP extensions required by Laravel/packages
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    bcmath \
    exif \
    pcntl \
    gd \
    zip \
    intl \
    xml

# Copy Composer dependencies
COPY --from=composer /var/www/html/vendor ./vendor

# Copy application source code
COPY . .

# Copy Vite production assets
COPY --from=frontend /var/www/html/public/build ./public/build

# Create Laravel required directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

# Laravel uses PHP-FPM on port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]