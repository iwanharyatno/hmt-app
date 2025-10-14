# ==========================
# 1️⃣ Stage: Build Frontend (Vite)
# ==========================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package.json & lock file, install deps
COPY package*.json ./
RUN npm ci

# Copy all source and build frontend assets
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

# Copy Composer binary
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy *seluruh* source code dulu
COPY . .

# Copy built assets dari Node build stage
COPY --from=frontend-builder /app/public/build ./public/build

# Install production dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-progress

# Generate Laravel cache files untuk optimize runtime
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache || true

# Set file ownership & permission
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
