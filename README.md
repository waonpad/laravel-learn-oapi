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

### ビルドログを保存するための Cloud Storage バケットを作成する

```bash
gcloud storage buckets create gs://<バケット名> --location=asia-northeast1
```

### LiteStream　を使用するため、Cloud Storage のバケットを作成する

```bash
gcloud storage buckets create gs://<バケット名> --location=asia-northeast1
```

### Compute Engine のデフォルト サービス アカウントにロールを付与する

#### Cloud Build を実行するためのロール

```bash
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format='value(projectNumber)')
gcloud projects add-iam-policy-binding $(gcloud config get-value project) \
  --member=serviceAccount:${PROJECT_NUMBER}-compute@developer.gserviceaccount.com \
  --role=roles/cloudbuild.builds.editor
```

#### Cloud Run のサービスに誰でもアクセス可能にする設定を行うためのロール

```bash
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format='value(projectNumber)')
gcloud projects add-iam-policy-binding $(gcloud config get-value project) \
  --member=serviceAccount:${PROJECT_NUMBER}-compute@developer.gserviceaccount.com \
  --role=roles/cloudfunctions.admin
```

#### Litestream が Google Cloud Storage に読み書きするためのロール

```bash
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format='value(projectNumber)')
gcloud projects add-iam-policy-binding $(gcloud config get-value project) \
  --member=serviceAccount:${PROJECT_NUMBER}-compute@developer.gserviceaccount.com \
  --role=roles/storage.admin
```

### GitHub との連携を行う場合の追加手順

#### Secret Manager API を有効にする

```bash
gcloud services enable secretmanager.googleapis.com
```

#### Secret Manager にアクセスするためのロールを付与する

```bash
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format="value(projectNumber)")
gcloud projects add-iam-policy-binding $(gcloud config get-value project) \
  --member=serviceAccount:service-${PROJECT_NUMBER}@gcp-sa-cloudbuild.iam.gserviceaccount.com \
  --role=roles/secretmanager.admin
```

### GitHub への接続を作成

コマンドを実行し、表示されたURLにアクセスしてCloud Build GitHub アプリを承認する

```bash
gcloud builds connections create github <コネクション名> --region=asia-northeast1
```

### GitHub リポジトリを接続

```bash
gcloud builds repositories create <任意のリポジトリ名> \
  --remote-uri=$(git config --get remote.origin.url) \
  --connection=<コネクション名> --region=asia-northeast1
```

### ビルドトリガーを作成

```bash
PROJECT_ID=$(gcloud config get-value project)
PROJECT_NUMBER=$(gcloud projects describe $(gcloud config get-value project) --format='value(projectNumber)')
SERVICE_ACCOUNT=${PROJECT_NUMBER}-compute@developer.gserviceaccount.com
gcloud builds triggers create github \
  --name=trigger \
  --region=asia-northeast1 \
  --require-approval \
  --include-logs-with-status \
  --build-config=cloudbuild.yaml \
  --repository=projects/${PROJECT_ID}/locations/asia-northeast1/connections/<コネクション名>/repositories/<リポジトリ名> \
  --branch-pattern=^release$ \
  --service-account=projects/${PROJECT_ID}/serviceAccounts/${SERVICE_ACCOUNT} \
  --substitutions=_GC_STORAGE_SQLITE_BUCKET=<バケット名>,_GC_STORAGE_BUILD_LOG_BUCKET=<バケット名>
```

#### 既存のビルドトリガーを確認する方法

```bash
gcloud builds triggers list --region=asia-northeast1
```

### デプロイ

```bash
gcloud builds submit \
  --region=asia-northeast1 \
  --substitutions COMMIT_SHA='local',_GC_STORAGE_SQLITE_BUCKET=<バケット名>,_GC_STORAGE_BUILD_LOG_BUCKET=<バケット名>
```

デフォルトでは`APP_KEY`を`cloudbuild.yaml`にハードコードしているため、実運用では別で生成したものを固定して使用する

```bash
gcloud builds submit \
  --region=asia-northeast1 \
  --substitutions COMMIT_SHA='local',_GC_STORAGE_SQLITE_BUCKET=<バケット名>,_GC_STORAGE_BUILD_LOG_BUCKET=<バケット名>,_APP_KEY=<APP_KEY>
