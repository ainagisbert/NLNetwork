FROM php:8.2-apache

# Habilitar mysqli
RUN docker-php-ext-install mysqli

# Diagnóstico: mostrar qué módulos MPM están activos antes de tocar nada
RUN ls -la /etc/apache2/mods-enabled/ | grep mpm

# Forzar prefork de forma explícita y verificar
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Verificar tras el cambio
RUN ls -la /etc/apache2/mods-enabled/ | grep mpm

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80