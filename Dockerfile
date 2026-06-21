FROM php:8.4-apache

# Habilitar extensión de MySQL (mysqli y pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# Copiar todos los archivos del proyecto al directorio web de Apache
COPY . /var/www/html/

# Railway asigna el puerto dinámicamente via $PORT, Apache por defecto usa 80
# Configuramos Apache para escuchar en el puerto que Railway indique
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

EXPOSE ${PORT}

CMD ["sh", "-c", "apache2-foreground"]
