FROM dunglas/frankenphp:1.3.6-php8.3.15

WORKDIR /workspace

ENV TZ Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER 1

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# create php.ini
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# install dependencies
RUN curl -1sLf 'https://dl.cloudsmith.io/public/evilmartians/lefthook/setup.deb.sh' | bash

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    lefthook \
    && docker-php-ext-install zip pcntl \
    && pecl install pcov && docker-php-ext-enable pcov
