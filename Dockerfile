FROM php:7.3-apache

RUN apt-get -qq update && apt-get -qq -y install \
    git \
    libsodium-dev \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    libicu-dev \
    libxslt-dev \
    libedit-dev \
    libbz2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
    sodium zip gd readline mbstring bcmath bz2 \
    json intl xsl opcache xml soap \
    pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && pecl install xdebug-2.7.1 \
    && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --version=2.0.0 \
        --install-dir=/usr/local/bin

RUN a2enmod rewrite
