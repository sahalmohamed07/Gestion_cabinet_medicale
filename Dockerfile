FROM php:8.2-apache

# Dépendances système + PHP
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Apache : UN SEUL MPM (PROPREMENT)
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork rewrite

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Copier le backend Laravel
COPY back-end/ .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Permissions Laravel
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
