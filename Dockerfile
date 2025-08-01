FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    unzip \
    git \
    zip \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos
COPY . /var/www/html

# Establecer directorio
WORKDIR /var/www/html

# Instalar dependencias
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar Apache
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install intl pdo pdo_mysql zip
RUN docker-php-ext-install pdo pdo_mysql
