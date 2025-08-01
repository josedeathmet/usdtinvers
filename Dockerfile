FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copiar el código al contenedor
COPY . /var/www/html/

# Habilitar reescritura para CakePHP
RUN a2enmod rewrite
RUN service apache2 restart

# Configurar permisos correctos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Configurar DocumentRoot
WORKDIR /var/www/html

EXPOSE 80
