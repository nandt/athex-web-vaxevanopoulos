FROM php:8.1-apache AS php-base

# Dependency installation
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y \
	zlib1g-dev \
	libpng-dev \
	libjpeg-dev \
	libwebp-dev \
	libfreetype6-dev \
	libaio1 \
	unzip \
	build-essential

# PHP extention configuration
RUN docker-php-ext-configure gd \
	--with-jpeg \
	--with-webp \
	--with-freetype

# PHP extention installation
RUN docker-php-ext-install gd
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath

# Oracle InstantClient & PHP Extension installation
ENV ORACLE_VERSION="21.12.0.0.0"
ENV ORACLE_VERSION_MAJOR_ONLY="21"
ENV BASIC_DOWNLOAD_URL="https://download.oracle.com/otn_software/linux/instantclient/2112000/instantclient-basic-linux.x64-${ORACLE_VERSION}dbru.zip"
ENV SDK_DOWNLOAD_URL="https://download.oracle.com/otn_software/linux/instantclient/2112000/instantclient-sdk-linux.x64-${ORACLE_VERSION}dbru.zip"
ENV INSTALL_DIR="/usr/lib/oracle/$ORACLE_VERSION_MAJOR_ONLY/client64"

RUN mkdir -p "$INSTALL_DIR"

RUN curl -L -o /tmp/instantclient-basic.zip --header "Cookie: oraclelicense=accept-securebackup-cookie" "$BASIC_DOWNLOAD_URL"
RUN unzip /tmp/instantclient-basic.zip -d "$INSTALL_DIR"
RUN rm /tmp/instantclient-basic.zip

RUN curl -L -o /tmp/instantclient-sdk.zip --header "Cookie: oraclelicense=accept-securebackup-cookie" "$SDK_DOWNLOAD_URL"
RUN unzip /tmp/instantclient-sdk.zip -d "$INSTALL_DIR"
RUN rm /tmp/instantclient-sdk.zip

RUN mv "$INSTALL_DIR"/*/** "$INSTALL_DIR"/
RUN rm -r "$INSTALL_DIR"/instantclient_*

RUN docker-php-ext-configure oci8 \
	--with-oci8=instantclient,"$INSTALL_DIR"

RUN docker-php-ext-install oci8

RUN echo "$INSTALL_DIR" | tee /etc/ld.so.conf.d/oracle-instantclient.conf
RUN ldconfig

# Apache extension activation
RUN a2enmod rewrite
