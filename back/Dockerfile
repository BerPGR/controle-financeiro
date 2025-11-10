FROM php:8.4-cli

#RUN apt-get update && apt-get install -y --no-install-recommends \
#        git unzip libpq-dev libzip-dev \
#    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
#    && a2enmod rewrite \
#    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libzip-dev \
  && docker-php-ext-install pdo pdo_mysql \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip \
  && rm -rf /var/lib/apt/lists/*


#ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
#    sed -ri -e 's!Directory /var/www/!Directory ${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts

COPY . .

RUN composer dump-autoload --optimize

#RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "public/index.php"]