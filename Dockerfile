FROM php:8.4-apache

RUN a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html
