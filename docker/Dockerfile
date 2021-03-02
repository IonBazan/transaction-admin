FROM php:8.0

RUN apt-get update \
    && apt-get install -y -qq \
        git \
        unzip \
    && yes '' | pecl install -f mongodb \
    && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash

WORKDIR /app

EXPOSE 8000

CMD ["/root/.symfony/bin/symfony", "serve", "--no-tls"]
