FROM ubuntu:16.04

MAINTAINER Adrian Paiva

RUN apt-get clean && apt-get update && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update \
    && apt-get install -y curl zip unzip git software-properties-common build-essential nasm autoconf libtool pkg-config nasm \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.2-fpm php7.2-cli php7.2-gd php7.2-mysql php7.2-zip \
       php7.2-pgsql php7.2-imap php-memcached php7.2-mbstring php7.2-xml php7.2-curl php-redis \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin/ --filename=composer \
    && curl -sL https://deb.nodesource.com/setup_8.x | bash - \
    && apt-get install -y nodejs \
    && npm install n -g \
    && n latest \
    && npm install npm -g \
    && mkdir /run/php \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ADD php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
ADD php.ini /etc/php/7.2/fpm/php.ini
ADD www.conf /etc/php/7.2/fpm/pool.d/www.conf

EXPOSE 9000
CMD ["php-fpm7.2"]
