RethinkDB DebugPanel を使用する
=============================

Yii 2 RethinkDB エクステンションは、Yii のデバッグモジュールと統合できるデバッグパネルを提供しています。
これは実行された RethinkDB クエリを表示するものです。

これを有効にするためには、下記のコードをあなたのアプリケーションの構成情報に追加してください
(既にデバッグモジュールを有効にしている場合は、パネルの構成を追加するだけで十分です)。

```php
    // ...
    'bootstrap' => ['debug'],
    'modules' => [
        'debug' => [
            'class' => 'yii\\debug\\Module',
            'panels' => [
                'rethinkdb' => [
                    'class' => 'yii\\rethinkdb\\debug\\RethinkDbPanel',
                     // 'db' => 'db', // RethinkDB のコンポーネント ID。デフォルトは `db`。
                                      // RethinkDB のコンポーネントを別の ID で登録した場合は、コメントを外して書き換えること。
                ],
            ],
        ],
    ],
    // ...
```
