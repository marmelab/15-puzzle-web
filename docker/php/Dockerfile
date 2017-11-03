FROM bitnami/php-fpm:7.1

RUN apt-get update -y
RUN apt-get install -y apt-transport-https lsb-release ca-certificates wget phpunit
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
RUN apt-get update -y

RUN apt-get install --no-install-recommends -y php7.1

RUN composer install -d /app; true
