FROM php:8.0-fpm
RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Install libraries
RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get -y install \
            libonig-dev \
            g++ \
            git \
            curl \
            imagemagick \
            libfreetype6-dev \
            libcurl3-dev \
            libicu-dev \
            libfreetype6-dev \
            libjpeg-dev \
            libjpeg62-turbo-dev \
            libmagickwand-dev \
            libpq-dev \
            libpng-dev \
            libxml2-dev \
            zlib1g-dev \
            mariadb-client \
            openssh-client \
            nano \
            unzip \
            redis-server \
            libzip-dev \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions required for Yii 2.0 Framework
RUN docker-php-ext-configure gd  && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-install \
        soap \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        pdo_pgsql

# Install PECL extensions
# see http://stackoverflow.com/a/8154466/291573) for usage of `printf`
RUN printf "\n" | pecl install \
        imagick && \
    docker-php-ext-enable \
        imagick

# Environment settings
ENV PHP_USER_ID=33 \
    PHP_ENABLE_XDEBUG=0 \
    PATH=/app:/app/vendor/bin:/root/.composer/vendor/bin:/var/www/html:/var/www/html/vendor/bin:$PATH \
    TERM=linux \
    VERSION_PRESTISSIMO_PLUGIN=^0.3.7 \
    COMPOSER_ALLOW_SUPERUSER=1

# Add configuration files
#COPY php/image-files/ /

# Add GITHUB_API_TOKEN support for composer
#RUN chmod 700 \
#        /usr/local/bin/docker-php-entrypoint \
#        /usr/local/bin/composer

# Install composer
#RUN curl -sS https://getcomposer.org/installer | php -- \
#        --filename=composer.phar \
#        --install-dir=/usr/local/bin && \
#    composer clear-cache

# Install composer plugins
#RUN composer global require --optimize-autoloader \
#        "hirak/prestissimo:${VERSION_PRESTISSIMO_PLUGIN}" && \
#    composer global dumpautoload --optimize && \
#composer clear-cache

WORKDIR /var/www/html
RUN usermod -u 1000 www-data
