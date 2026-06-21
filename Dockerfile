FROM php:8.4-apache

# Instalar extensiones mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Deshabilitar MPMs conflictivos y habilitar solo prefork
RUN a2dismod mpm_event mpm_worker && \
    a2enmod mpm_prefork rewrite

# Configurar Apache para permitir .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Copiar archivos al directorio web
COPY . /var/www/html/

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Exponer puerto 80
EXPOSE 80