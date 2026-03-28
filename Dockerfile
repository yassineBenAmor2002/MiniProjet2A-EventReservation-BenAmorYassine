# Dockerfile
FROM php:8.2-fpm

# Installer les extensions nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install intl mbstring pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le code dans le conteneur
WORKDIR /var/www/html
COPY . .

# Installer les dépendances Symfony
RUN composer install

# Permissions (Linux)
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor