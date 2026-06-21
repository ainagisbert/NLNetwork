FROM php:8.2-apache

# Habilitar mysqli
RUN docker-php-ext-install mysqli

# Eliminar la carga de mpm_event y mpm_worker, dejar solo mpm_prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
    && a2enmod mpm_prefork

# Copiar el código al directorio que sirve Apache
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80