FROM php:8.2-apache

# Habilitar mysqli
RUN docker-php-ext-install mysqli

# Forzar un único MPM (evita el conflicto "More than one MPM loaded")
RUN a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork

# Copiar el código al directorio que sirve Apache
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80