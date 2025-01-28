############################################
# Base Image
############################################
FROM serversideup/php:8.1-fpm-nginx AS base

# Switch to root so we can do root things
USER root

# Save the build arguments as a variable
ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install git -y

RUN docker-php-ext-install bcmath \
    && docker-php-ext-enable bcmath

RUN docker-php-serversideup-set-id www-data $USER_ID:$GROUP_ID \
    && docker-php-serversideup-set-file-permissions --owner $USER_ID:$GROUP_ID --service nginx

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

ENV XDEBUG_SEESION=1

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY . .
COPY ./.deploy/nginx-custom.conf /etc/nginx/server-opts.d/nginx-custom.conf

RUN chown -R www-data:www-data /var/www/html/ \
    && chown -R www-data:www-data /composer \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/

RUN chown -R www-data:www-data storage \
    && chown -R www-data:www-data bootstrap/cache

RUN mkdir -p /var/lib/mysql && chown -R www-data:www-data /var/lib/mysql && chmod -R 750 /var/lib/mysql

# Drop back to our unprivileged user
USER www-data

RUN composer install --no-dev --optimize-autoloader
