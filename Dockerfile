FROM php:8.0-apache

ENV APP_ENV=prod

RUN apt-get update \
    && apt-get install -y -qq \
        git \
        unzip \
    && yes '' | pecl install -f mongodb \
    && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY apache/site.conf /etc/apache2/sites-available/000-default.conf
COPY apache/ports.conf /etc/apache2/ports.conf

WORKDIR /var/www/html

COPY composer.* ./

RUN composer i -n --no-dev --no-scripts

COPY . ./

RUN composer i -n --no-dev -o \
    && chown -R www-data:www-data var

ENV PORT=80
