FROM php:8.0-fpm

#Install xdebug
RUN pecl install xdebug-3.0.3 && docker-php-ext-enable xdebug

RUN apt update  \
    && apt install -y \
        g++ \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        zip \
        zlib1g-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql

CMD ["php-fpm"]
