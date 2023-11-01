FROM php:8.1-apache AS php-base

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y \
	zlib1g-dev \
	libpng-dev \
	libjpeg-dev \
	libwebp-dev \
	libfreetype6-dev

RUN docker-php-ext-configure gd \
	--with-jpeg \
	--with-webp \
	--with-freetype


RUN docker-php-ext-install gd
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath

RUN a2enmod rewrite
