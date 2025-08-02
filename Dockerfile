FROM php:8.2-apache

# Instalar extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zip \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        zip

# Habilitar mod_rewrite para CakePHP
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto (y cambiar dueño directamente)
COPY --chown=www-data:www-data . .

# Cambiar permisos (si no estás en Railway, puedes usar chown también)
RUN chmod -R 755 .

# Exponer puerto 80
EXPOSE 80

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ejecutar composer install dentro del contenedor
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
