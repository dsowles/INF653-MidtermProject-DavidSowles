FROM php:8.2-apache

# Install PostgreSQL dev headers and PDO driver.
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enables Apache mod_rewrite.
RUN a2enmod rewrite

# Copies project files into the container.
COPY . /var/www/html/

# Set permissions so Apache can read files.
RUN chown -R www-data:www-data /var/www/html

# Port 80, which is the default.
EXPOSE 80