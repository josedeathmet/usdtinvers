FROM php:8.2-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    unzip \
    git \
    zip \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Copiar archivos del proyecto
COPY . /var/www/html

# Dar permisos a CakePHP
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilitar mod_rewrite de Apache (importante para CakePHP)
RUN a2enmod rewrite

# Configuración para que Apache trabaje con CakePHP
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
