# 後からFrankenPHPをインストールするとPHPのバージョンが合わずエラーになるため、FrankenPHPのイメージをベースにする
FROM dunglas/frankenphp:1.3.6-php8.3.15

WORKDIR /workspace

ENV TZ=Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer /usr/bin/composer /usr/bin/composer

# php.iniは元から用意されているものを使用する
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Laravelの動作に必要なパッケージをインストール
# - Linuxパッケージ
#   - unzip
#   - libzip-dev
# - PHP拡張
#   - zip
#   - pcntl
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pcntl

# SQLiteをレプリケーションするためにLitestreamを使用する
# Litestream CLI のバージョンを指定
ENV LITESTREAM_VERSION=v0.3.13
# Litestream CLI をインストール
ADD https://github.com/benbjohnson/litestream/releases/download/$LITESTREAM_VERSION/litestream-$LITESTREAM_VERSION-linux-amd64.tar.gz /tmp/litestream.tar.gz
RUN tar -C /usr/local/bin -xzf /tmp/litestream.tar.gz

COPY . /workspace

RUN composer install --no-dev

EXPOSE 8000

CMD ["composer", "start:prod"]
