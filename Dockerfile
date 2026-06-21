FROM php:8.2-apache

# Habilitar mysqli
RUN docker-php-ext-install mysqli

# Eliminar todos los MPMs y dejar solo prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf \
           /etc/apache2/mods-enabled/mpm_event.load \
           /etc/apache2/mods-enabled/mpm_worker.conf \
           /etc/apache2/mods-enabled/mpm_worker.load \
    && a2enmod mpm_prefork

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
