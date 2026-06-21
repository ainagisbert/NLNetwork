FROM php:8.2-apache

# Habilitar mysqli (es lo que usa tu capa DL)
RUN docker-php-ext-install mysqli

# Copiar el código al directorio que sirve Apache
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80