FROM php:8.1-fpm

RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ADD composer.json composer.lock /var/www/symfony/

WORKDIR /var/www/symfony/
CMD composer install;php-fpm
