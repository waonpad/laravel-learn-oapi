# 後からFrankenPHPをインストールするとPHPのバージョンが合わずエラーになるため、FrankenPHPのイメージをベースにする
FROM dunglas/frankenphp:1.4.3-php8.3.17

WORKDIR /workspace

ENV TZ=Asia/Tokyo
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer /usr/bin/composer /usr/bin/composer

# php.iniは元から用意されているものを使用する
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# LeftHookをインストール（apt-get install よりも先に実行する必要がある）
RUN curl -1sLf 'https://dl.cloudsmith.io/public/evilmartians/lefthook/setup.deb.sh' | bash

# Laravelの動作に必要なパッケージをインストール
# - Linuxパッケージ
#   - unzip
#   - libzip-dev
# - PHP拡張
#   - zip
#   - pcntl
#
# 開発に必要なパッケージをインストール
# - Linuxパッケージ
#   - git
#   - lefthook
# - PHP拡張
#   - pcov
RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    lefthook \
    && docker-php-ext-install zip pcntl \
    && pecl install pcov && docker-php-ext-enable pcov

# 開発用のnpmパッケージを動作させるためにBunをインストール
RUN curl -fsSL https://bun.sh/install | bash

# コンテナを起動したままにしておくために無限ループを実行
CMD ["/bin/bash", "-c", "while :; do sleep 10; done"]
