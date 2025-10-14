# ==========================
# 1️⃣ Stage: Build Frontend (Vite)
# ==========================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy and install frontend dependencies
COPY package*.json ./
RUN npm ci

# Copy source and build assets
COPY . .
RUN npm run build


# ==========================
# 2️⃣ Stage: Build Backend (Composer + PHP-FPM)
# ==========================
FROM php:8.3-fpm-alpine AS backend

# Install system dependencies
RUN apk add --no-cache \
    bash \
    zip \
    unzip \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath intl zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install production dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Copy built frontend assets
COPY --from=frontend-builder /app/public/build ./public/build

# Copy Laravel source code
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]
