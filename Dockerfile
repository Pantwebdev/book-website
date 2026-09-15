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
# Stage 2: PHP-FPM + Composer dependencies
# ==========================================
FROM php:8.3-fpm AS app

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

# Install PHP extensions
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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Composer files first
COPY composer.json composer.lock ./

# Install Laravel dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy application source
COPY . .

# Copy Vite production assets
COPY --from=frontend /var/www/html/public/build ./public/build

# Create Laravel directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set Laravel permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache

# PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
