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

# Postavljanje APP_ENV na prod tokom build-a
ENV APP_ENV=prod

# Instalacija zavisnosti bez dev paketa
RUN composer install --no-dev --optimize-autoloader --no-scripts

# --- NOVO ZA EASYADMIN (Kopira CSS/JS i stvara AssetMap ako se koristi) ---
RUN php bin/console assets:install public --no-debug
# Ako koristiš Symfony AssetMapper (Symfony 6.3+ / 7+), otkomentariši i sledeću liniju:
# RUN php bin/console asset-map:compile
# --------------------------------------------------------------------------

# Pravljenje var foldera i postavljanje dozvola (DODAT I public FOLDER DA BI Apache mogao da čita assets)
RUN mkdir -p var && chown -R www-data:www-data var public

EXPOSE 80

# Pokretanje baze, kreiranje admin korisnika (opciono), dozvole i Apache
CMD php bin/console doctrine:schema:update --force && \
    php bin/console assets:install public --no-debug && \
    chown -R www-data:www-data var public && \
    apache2-foreground