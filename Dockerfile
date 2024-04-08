FROM php:8.2-fpm
RUN apt-get update && apt-get install -y nodejs npm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /var/www/html


WORKDIR /var/www/html

RUN composer install
RUN npm install 

#EXPOSE 9000

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer clear-cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


CMD ["php-fpm"]