FROM basterserv/basic:php74

RUN ln -sf /usr/share/zoneinfo/Europe/Moscow  /etc/localtime
RUN dpkg-reconfigure -f noninteractive tzdata
RUN apt-get update && apt-get install -y \
    libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
	&& docker-php-ext-enable imagick

WORKDIR /var/www
