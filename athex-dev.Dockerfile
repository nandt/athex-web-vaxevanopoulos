FROM athex-base

RUN apt-get install git zip tmux -y

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# zend_extension=xdebug
#
# [xdebug]
# xdebug.mode=develop,debug
# ; xdebug.client_host=host.docker.internal
# xdebug.start_with_request=yes


# /usr/local/etc/php/conf.d/error_reporting.ini
# error_reporting=E_ALL


# If you want to run Composer as root you should add this
# ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
RUN composer --version && php -v

RUN sed -ri -e 's!/var/www/html!/var/www/html/web!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/web!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY .docker/imgs-version /version

EXPOSE 80
CMD ["./.docker/dev-launch.sh"]
