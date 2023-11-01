FROM athex-base

RUN apt-get install git zip tmux -y

# If you want to run Composer as root you should add this
# ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
RUN composer --version && php -v

RUN sed -ri -e 's!/var/www/html!/var/www/html/web!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/web!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
CMD ["tmux", "new-session", "-n", "apache", "apache2ctl -D FOREGROUND"]
