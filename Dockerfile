FROM php:8.2-apache


RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY . /var/www/html/

EXPOSE 80


# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    unzip \
    git \
    zip \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Habilitar mod_rewrite de Apache (importante para CakePHP)
RUN a2enmod rewrite

# Copiar archivos del proyecto
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755
