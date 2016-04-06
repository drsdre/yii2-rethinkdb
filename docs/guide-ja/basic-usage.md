基本的な使用方法
================

接続のインスタンスを使用して、データベースとコレクションにアクセスすることが出来ます。
ほとんどの RethinkDB コマンドに [[\yii\rethinkdb\Collection]] によってアクセスすることが出来ます。

```php
$collection = Yii::$app->rethinkdb->getCollection('customer');
$collection->insert(['name' => 'John Smith', 'status' => 1]);
```

"find" クエリを実行するためには、[[\yii\rethinkdb\Query]] を使わなければなりません。

```php
use yii\rethinkdb\Query;

$query = new Query;
// クエリを構築する
$query->select(['name', 'status'])
    ->from('customer')
    ->limit(10);
// クエリを実行する
$rows = $query->all();
```

このエクステンションは、ロギングとプロファイリングをサポートしています。
ただし、ログメッセージは実行されたクエリの実際のテキストを含んでいません。
ログメッセージは PHP Rethink 拡張のクラスから抽出することが出来る値から構成された、クエリテキストの「近似値」しか含んでいません。
実際のクエリテキストを見るためには、そのための特別なツールを使用する必要があります。
