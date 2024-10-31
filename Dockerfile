FROM php:8.1-fpm

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    supervisor \
    libzip-dev \
    zip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install -j$(nproc) iconv mysqli pdo pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mbstring exif pcntl bcmath soap \
    && pecl install redis && docker-php-ext-enable redis

# Instalar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copiar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Establecer permisos para las carpetas de Laravel
# RUN mkdir -p /var/www/html/bootstrap/cache && \
#     chown -R www-data:www-data /var/www/html/bootstrap/cache && \
#     chmod -R 775 /var/www/html/bootstrap/cache
