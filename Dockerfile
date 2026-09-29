FROM php:8.3-fpm-alpine

# 1. Install System Dependencies, PHP Extensions, Node.js, dan NPM
RUN apk add --no-cache \
    bash \
    zip \
    unzip \
    curl \
    git \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath intl zip

# Copy Composer binary dari official image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 2. Copy seluruh source code project ke dalam container
COPY . .

# 3. Install PHP Dependencies (Composer) terlebih dahulu
# Agar folder vendor dan view pagination Laravel tersedia untuk di-scan oleh Tailwind
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-progress

# 4. Install Node Dependencies & Build Asset Vite (Tailwind CSS)
RUN npm ci && npm run build

# 5. Generate Laravel cache untuk optimasi runtime
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache || true

# 6. Set Hak Akses (Ownership & Permissions) untuk storage dan bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Expose port PHP-FPM
EXPOSE 9000

# Jalankan PHP-FPM sebagai proses utama container
CMD ["php-fpm"]