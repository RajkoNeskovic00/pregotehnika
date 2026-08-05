FROM php:8.2-apache

# Instalacija neophodnih ekstenzija za Symfony i bazu
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip

# Omogućavanje Apache rewrite modula za Symfony rute
RUN a2enmod rewrite

# Podešavanje DocumentRoot-a na public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instalacija zavisnosti bez dev paketa
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Pravljenje var foldera i postavljanje dozvola
RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 80

# Pokretanje baze, ponovno postavljanje dozvola za www-data i startovanje Apache-a
CMD php bin/console doctrine:schema:update --force && chown -R www-data:www-data var && apache2-foreground