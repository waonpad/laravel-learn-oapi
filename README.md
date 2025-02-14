# Laravel 学習用リポジトリ

## セットアップ

### コンテナを起動する

Dev Container を起動してコマンドの実行を代替しても良い

```bash
docker compose up -d
```

### セットアップスクリプトを実行する

コンテナ内で

```bash
composer setup
```

### Laravelのアプリケーションを起動する

コンテナ内で

```bash
composer dev
```

#### Swagger UI を確認できる

http://localhost:8000/api/documentation

## 本番環境

```bash
docker build .
```
