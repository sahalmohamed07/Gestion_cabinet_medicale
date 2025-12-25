FROM php:8.2-apache

# 🔥 EXORCISME APACHE : supprimer TOUS les MPM
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf

# Dépendances système + PHP
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# ✅ Activer UN SEUL MPM + rewrite
RUN a2enmod mpm_prefork rewrite

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
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

CMD ["bash", "-c", "rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf && a2enmod mpm_prefork rewrite && apache2-foreground"]

