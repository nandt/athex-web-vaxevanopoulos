
FROM athex-base AS composer-deps

RUN apt-get install git zip -y

# If you want to run Composer as root you should add this
# ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
RUN composer --version && php -v
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install


FROM athex-base AS runner

RUN apt-get clean

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Patch to disable PHP exposure
RUN sed -ri 's/^expose_php\s+\=\w+\s*$/expose_php = off/g' /usr/local/etc/php/php.ini


WORKDIR /var/www/html
COPY --from=composer-deps /app .
COPY web web

RUN sed -ri -e 's!/var/www/html!/var/www/html/web!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/web!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]
