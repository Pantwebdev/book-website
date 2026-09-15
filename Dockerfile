# ==========================================
# Stage 1: Build frontend assets
# ==========================================
FROM node:20-alpine AS frontend

WORKDIR /var/www/html

# Copy package files
COPY package.json ./

# Install frontend dependencies
RUN npm install

# Copy application source
COPY . .

# Build Vite production assets
RUN npm run build


# ==========================================
# Stage 2: Laravel PHP-FPM Application
# ==========================================
FROM php:8.3-fpm AS app

WORKDIR /var/www/html


# ==========================================
# Install System Dependencies
# ==========================================
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libsqlite3-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*


# ==========================================
# Configure GD
# ==========================================
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg


# ==========================================
# Install PHP Extensions
# ==========================================
RUN docker-php-ext-install \
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


# ==========================================
# Install Composer
# ==========================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ==========================================
# Copy Composer Files
# ==========================================
COPY composer.json composer.lock ./


# ==========================================
# Install Laravel Dependencies
# ==========================================
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ==========================================
# Copy Laravel Application
# ==========================================
COPY . .


# ==========================================
# Copy Vite Production Assets
# ==========================================
COPY --from=frontend /var/www/html/public/build ./public/build


# ==========================================
# Create Laravel Required Directories
# ==========================================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache


# ==========================================
# Set Laravel Permissions
# ==========================================
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache


# ==========================================
# PHP-FPM Port
# ==========================================
EXPOSE 9000


# ==========================================
# Start PHP-FPM
# ==========================================
CMD ["php-fpm"]
