FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /var/www/html


WORKDIR /var/www/html

RUN composer install


EXPOSE 9000

CMD ["php-fpm"]