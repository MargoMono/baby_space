FROM php:7.3-apache

RUN apt-get -qq update && apt-get -qq -y install \
    git \
    libsodium-dev \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install \
    sodium \
    zip \
    gd \
    pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && pecl install xdebug-2.7.1 \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin

RUN a2enmod rewrite
