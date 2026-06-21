FROM php:8.4-apache

# Instalar extensiones mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Eliminar TODOS los módulos MPM manualmente
RUN rm -f /etc/apache2/mods-enabled/mpm_*.conf \
          /etc/apache2/mods-enabled/mpm_*.load

# Habilitar solo mpm_prefork
RUN a2enmod mpm_prefork

# Habilitar rewrite para .htaccess
RUN a2enmod rewrite

# Configurar Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf && \
    sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html/g' /etc/apache2/apache2.conf

# Copiar archivos
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80