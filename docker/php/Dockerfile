FROM php:7.1-fpm

RUN apt-get update -y
RUN apt-get install -y apt-transport-https lsb-release ca-certificates wget git
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
RUN apt-get update -y

RUN apt-get install --no-install-recommends -y libpq-dev php7.1 phpunit php7.1-xml
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app
