FROM php:5.6

RUN apt-get update && apt-get install -yqq ssh git zlib1g-dev libev4 && \
    docker-php-ext-install zip

WORKDIR /connect4
COPY composer.json composer.lock ./
RUN \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
    composer install --no-autoloader && composer clear-cache

ADD . ./

RUN composer dumpautoload

EXPOSE 1337
EXPOSE 8080
CMD ["php", "scripts/connect4.php", "connect4:server"]
