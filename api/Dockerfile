# use PHP 8.2
FROM php:8.2-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    iputils-ping \
    netcat-traditional \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

RUN pecl install redis && docker-php-ext-enable redis

# Set the working directory
COPY . /var/www/app
WORKDIR /var/www/app

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install dependencies
RUN composer install --no-interaction --optimize-autoloader
RUN php artisan l5-swagger:generate && php artisan route:cache

# Set the default command to run php-fpm
CMD ["php-fpm"]
