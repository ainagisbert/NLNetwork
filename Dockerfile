FROM php:8.4-apache

# Instalar extensión mysqli
RUN docker-php-ext-install mysqli pdo_mysql

# Habilitar mod_rewrite (útil para URLs limpias)
RUN a2enmod rewrite

# Copiar tu código
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

# Configurar Apache para permitir .htaccess (opcional)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80
