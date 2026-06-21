FROM php:8.4-apache

RUN docker-php-ext-install mysqli

RUN find /etc/apache2/mods-enabled/ -name "mpm_*" -delete \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
