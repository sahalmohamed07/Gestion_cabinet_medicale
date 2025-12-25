FROM php:8.2-apache

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activer mod_rewrite (Laravel)
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le code
WORKDIR /var/www/html
COPY . .

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Exposer le port
EXPOSE 80

CMD ["apache2-foreground"]
