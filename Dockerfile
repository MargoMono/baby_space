FROM php:7.4-apache

RUN apt-get -qq update && apt-get -qq -y install \
    git \
    libsodium-dev \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    sodium \
    zip \
    gd \
    pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin

RUN a2enmod rewrite
