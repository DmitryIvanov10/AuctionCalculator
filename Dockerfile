ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.21.6

ARG WORKDIR=/app

FROM php:${PHP_VERSION}-fpm-alpine as app_php

RUN docker-php-source extract \
    && apk add --update --virtual .build-deps autoconf g++ make pcre-dev icu-dev openssl-dev libxml2-dev libmcrypt-dev git libpng-dev \
# add php mysql extensions
    && docker-php-ext-install mysqli pdo pdo_mysql \
# Installing pecl modules
	&& pecl install apcu xdebug  \
# Enable pecl modules
    && docker-php-ext-enable apcu opcache xdebug \
# Installing intl
    && apk add icu-libs icu \
    && docker-php-ext-install intl \
# Post run
	&& runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)" \
	&& apk add --no-cache --virtual .app-phpexts-rundeps $runDeps \
	&& pecl clear-cache \
    && docker-php-source delete \
    && apk del --purge .build-deps \
    && rm -rf /tmp/pear \
    && rm -rf /var/cache/apk/*

# install composer
RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

COPY .docker/php/php.ini $PHP_INI_DIR/conf.d/php.ini
COPY .docker/php/php-cli.ini $PHP_INI_DIR/conf.d/php-cli.ini
COPY .docker/php/xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

#RUN mkdir -p ${WORKDIR}
WORKDIR ${WORKDIR}

COPY . .

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock symfony.lock ./
RUN set -eux; \
	composer install --prefer-dist --no-autoloader --no-scripts  --no-progress; \
	composer clear-cache

RUN set -eux \
	&& mkdir -p var/cache var/log \
	&& composer dump-autoload --classmap-authoritative

VOLUME ${WORKDIR}/var

CMD ["php-fpm"]

FROM nginx:${NGINX_VERSION}-alpine as app_nginx

COPY .docker/nginx/conf.d/default.conf /etc/nginx/conf.d

WORKDIR ${WORKDIR}/public