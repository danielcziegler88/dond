FROM php:fpm-alpine
RUN apk add --no-cache composer
# RUN apk add --no-cache $PHPIZE_DEPS oniguruma-dev libzip-dev curl-dev \
#     && docker-php-ext-install pdo_mysql mbstring zip curl \
#     && pecl install xdebug redis \
#     && docker-php-ext-enable xdebug redis
RUN apk add --no-cache $PHPIZE_DEPS oniguruma-dev libzip-dev curl-dev \
    && docker-php-ext-install pdo_mysql mbstring zip curl
RUN mkdir /app
VOLUME /app
WORKDIR /app
EXPOSE 8000
# CMD php -S lumen:8000 -t public
CMD php artisan migrate && php artisan db:seed && php -S lumen:8000 -t public