FROM dunglas/frankenphp:php8.4

# Instalar extensiones de PHP necesarias
RUN install-php-extensions mysqli pdo_mysql

# Copiar tu código PHP
COPY . /app/public

# Exponer el puerto
EXPOSE 80