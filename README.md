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

## Cloud Run にデプロイ

[クイックスタート: Cloud Run に PHP サービスをデプロイする  |  Cloud Run Documentation  |  Google Cloud](https://cloud.google.com/run/docs/quickstarts/build-and-deploy/deploy-php-service?hl=ja)

### プロジェクトの作成

プロジェクトを作成し、CLIのデフォルトプロジェクトに設定する

```bash
PROJECT_ID=<プロジェクトID> && gcloud projects create $PROJECT_ID && gcloud config set project $PROJECT_ID
```

### プロジェクトの課金を有効にする

#### 請求先に設定するアカウントIDを確認

```bash
gcloud beta billing accounts list
```

#### プロジェクトに請求先アカウントを紐付ける

```bash
gcloud beta billing projects link $(gcloud config get-value project) --billing-account=<請求先アカウントID>
```

### Cloud Run Admin API と Cloud Build API を有効にする

```bash
gcloud services enable run.googleapis.com cloudbuild.googleapis.com
```

### Compute Engine のデフォルト サービス アカウントにロールを付与する

#### Cloud Build を実行するためのロール

```bash
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format='value(projectNumber)')
gcloud projects add-iam-policy-binding $(gcloud config get-value project) --member=serviceAccount:${PROJECT_NUMBER}-compute@developer.gserviceaccount.com --role=roles/cloudbuild.builds.editor
```

#### Cloud Run のサービスに誰でもアクセス可能にする設定を行うためのロール

```bash
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format='value(projectNumber)')
gcloud projects add-iam-policy-binding $(gcloud config get-value project) --member=serviceAccount:${PROJECT_NUMBER}-compute@developer.gserviceaccount.com --role=roles/cloudfunctions.admin
```

### デプロイ

```bash
gcloud builds submit --substitutions COMMIT_SHA='local'
```

デフォルトでは`APP_KEY`を`cloudbuild.yaml`にハードコードしているため、実運用では別で生成したものを固定して使用する

```bash
gcloud builds submit --substitutions COMMIT_SHA='local',_APP_KEY=<APP_KEY>
