FROM php:7.4-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev
 
RUN cd /app && php artisan jwt:secret
RUN cd /app && php artisan cache:clear
RUN cd /app && php artisan config:clear

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh