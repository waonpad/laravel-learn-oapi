FROM dunglas/frankenphp:1.3.6-php8.3.15

WORKDIR /workspace

ENV TZ Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER 1

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# create php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pcntl

# copy source
COPY . /workspace

# install composer dependencies
RUN composer install --no-dev

# init
RUN composer run post-root-package-install && composer run post-create-project-cmd

CMD composer run start
