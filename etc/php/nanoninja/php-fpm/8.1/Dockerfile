FROM php:8.1-fpm

LABEL maintainer="Vincent Letourneau <vincent@nanoninja.com>"

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
    g++ \
    libbz2-dev \
    libc-client-dev \
    libcurl4-gnutls-dev \
    libedit-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libkrb5-dev \
    libldap2-dev \
    libldb-dev \
    libmagickwand-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libpng-dev \
    libpq-dev \
    libsqlite3-dev \
    libssl-dev \
    libreadline-dev \
    libxslt1-dev \
    libzip-dev \
    memcached \
    wget \
    unzip \
    zlib1g-dev \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    bz2 \
    calendar \
    exif \
    gettext \
    mysqli \
    opcache \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    soap \
    xsl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) imap \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-configure ldap \
    && docker-php-ext-install ldap \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets \
    && pecl install xmlrpc-1.0.0RC3 && docker-php-ext-enable xmlrpc \
    && pecl install xdebug && docker-php-ext-enable xdebug \
    && pecl install memcached && docker-php-ext-enable memcached \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && pecl install redis && docker-php-ext-enable redis \
    && yes '' | pecl install imagick && docker-php-ext-enable imagick \
    && docker-php-source delete \
    && apt-get remove -y g++ wget \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

RUN apt-get update && apt-get install -y wget && mkdir /usr/share/php-xhprof && cd /usr/share/php-xhprof/  \
    && wget http://pecl.php.net/get/xhprof-2.3.5.tgz && tar -xvf xhprof-2.3.5.tgz && cd xhprof-2.3.5/extension \
    && phpize && ./configure && make && make install \
    && mkdir /var/tmp/xhprof && chmod 777 /var/tmp/xhprof \
    && apt-get install -y graphviz \
    && apt-get remove -y wget

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"  \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"  \
    && php composer-setup.php  \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer