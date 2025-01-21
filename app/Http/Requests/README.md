#　Requestsディレクトリについて

## [zircote/swagger-php](https://github.com/zircote/swagger-php)の利用にあたり、PSR-4に準拠していない

### 何故か

バリデーションエラーのスキーマを定義するにあたり、バリデーションルールを定義しているリクエストクラスと可能な限り近い場所に配置したいため、PSR-4に準拠せず同ファイル内に定義している

### composer.jsonに設定を追加する必要がある

```json
{
    "autoload": {
        "classmap": [
            "app/Http/Requests/"
        ]
    },
}
```

### PSR-4に準拠していない形式のクラス追加/削除時の注意点

classmapによるオートロードのため、クラスを追加/削除した場合は以下のコマンドを実行

```bash
composer dump-autoload
```
