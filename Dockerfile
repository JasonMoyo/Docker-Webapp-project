FROM php:8.1-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . /var/www/html/

WORKDIR /var/www/html

# Install dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --no-dev --prefer-dist

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
