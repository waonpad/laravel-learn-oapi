# [zircote/swagger-php](https://github.com/zircote/swagger-php)のアプリケーション固有の拡張用ファイルを格納するディレクトリ

## 使用方法

### composer.jsonに設定追加

```json
{
    "autoload": {
        "psr-4": {
            "OpenApi\\": "app/OpenApi/"
        },
        "classmap": [
            "app/OpenApi/SchemaDefinitions/",
        ]
    }
}
```

### namespaceを指定して拡張ファイルを作成

```php
<?php

namespace OpenApi\...;
```

## SchemaDefinitionsディレクトリについて

- 共通して参照されるスキーマを定義するためのディレクトリ
- 作業を楽にするため、PSR-4に準拠せず、1ファイルに複数のクラスを定義

### クラス追加/削除時の注意点

classmapによるオートロードのため、クラスを追加/削除した場合は以下のコマンドを実行

```bash
composer dump-autoload
```
