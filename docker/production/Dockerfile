# syntax=docker/dockerfile:1

FROM php:8.1-apache as build

WORKDIR /var/www

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt update && apt install -y --no-install-recommends \
    npm \
    mariadb-client && \
    install-php-extensions @composer \
    calendar \
    bcmath \
    ffi \
    gd \
    gettext \
    igbinary \
    imagick \
    imap \
    intl \
    memcached \
    msgpack \
    mysqli \
    pcntl \
    pdo_mysql \
    shmop \
    soap \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    xmlrpc \
    xsl \
    zip \
    opcache

COPY . /var/www

ARG UNAME=vodev
ARG UID=1000
RUN useradd -m -u $UID -o -s /bin/bash $UNAME && \
usermod -a -G www-data $UNAME

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist && \
    npm install --dev && \
    npm run build && \
    chown $UNAME:www-data -R . && \
    find . -type f -exec chmod 640 {} + && \
    find . -type d -exec chmod 750 {} + && \
    chmod 644 .env && \
    find storage -type d -exec chmod 775 {} + && \
    find storage/* -type f -exec chmod 664 {} + && \
    find bootstrap -type d -exec chmod 775 {} + && \
    find bootstrap/* -type f -exec chmod 664 {} + && \
    find public -type d -exec chmod 775 {} + && \
    find public/* -type f -exec chmod 664 {} +

FROM build AS apache2

WORKDIR /var/www

ADD --chmod=0775 https://github.com/nmaier/mod_xsendfile/archive/refs/heads/master.zip .

COPY ./docker/silerium.conf /etc/apache2/sites-available

RUN apt update && apt install -y apache2-dev unzip && \
    unzip master.zip && \
    apxs -cia mod_xsendfile-master/mod_xsendfile.c && \
    a2enmod expires && \
    a2enmod cache && \
    a2enmod cache_disk && \
    a2enmod cache_socache && \
    a2enmod headers && \
    a2enmod socache_memcache && \
    a2enmod ssl && \
    a2enmod rewrite && \
    a2ensite silerium

RUN rm -r mod_xsendfile-master && rm master.zip && apt remove -y apache2-dev unzip

COPY ./docker/entrypoints/prod.sh /usr/local/bin 

RUN chmod 775 /usr/local/bin/prod.sh && \
mkdir /etc/apache2/cert && \
chown www-data:www-data /etc/apache2/cert && \
chmod 750 /etc/apache2/cert

EXPOSE 4443

ENTRYPOINT ["/usr/local/bin/prod.sh"]

USER $UNAME

CMD ["apachectl", "-D", "FOREGROUND"]