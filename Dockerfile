FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libonig-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring

# Enable Apache rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . /var/www/html/

WORKDIR /var/www/html

# Install PHP dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --no-dev --prefer-dist

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
