FROM php:8.0-fpm

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

ARG PHP_EXTENSIONS="pdo_mysql mbstring exif pcntl bcmath gd"
ARG user
ARG uid

WORKDIR /var/www/back

RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && docker-php-ext-install $PHP_EXTENSIONS \
    && pecl install redis && docker-php-ext-enable redis.so \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN useradd -g root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer && \
        chown -R $uid:$uid /home/$user

USER $user
