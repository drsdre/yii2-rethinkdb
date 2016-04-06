RethinkId の詳細
==============

## ドキュメント ID からスカラ値を取得する

RethinkDB のドキュメント ID ("_id" フィールド) はスカラ値ではなく [[\RethinkId]] クラスのインスタンスである、ということを記憶してください。
実際の Rethink ID 文字列を取得するためには、[[\RethinkId]] インスタンスを文字列に型キャストしなければなりません。

```php
$query = new Query;
$row = $query->from('customer')->one();
var_dump($row['_id']); // "object(RethinkId)" が出力される
var_dump((string) $row['_id']); // "string 'acdfgdacdhcbdafa'" が出力される
```

このことは、非常に役に立つこともありますが、しばしば問題を発生させます。
URL を作成したり、"_id" を他のストレージに保存しようとしたりする場合に、問題が生じます。
そのような場合には、[[\RethinkId]] を文字列に変換することを忘れないでください。

```php
/* @var $this yii\web\View */
echo $this->createUrl(['item/update', 'id' => (string) $row['_id']]);
```

RethinkDB に保存されるユーザ・アイデンティティを実装する場合にも、同じ事があてはまります。
認証プロセスが働くようにするためには、[[\yii\web\IdentityInterface::getId()]] を実装するときに [[\RethinkId]] クラスをスカラ値に型キャストしなければなりません。

## スカラ値からドキュメント ID を取得する

検索条件を構築する際には、'_id' キーの値は、単純な文字列である場合でも、自動的に [[\RethinkId]] インスタンスにキャストされます。
従って、文字列で表された '_id' をあなたがキャストして戻す必要はありません。

```php
use yii\web\Controller;
use yii\rethinkdb\Query;

class ItemController extends Controller
{
    /**
     * @param string $id RethinkId 文字列 (オブジェクトではない)
     */
    public function actionUpdate($id)
    {
        $query = new Query;
        $row = $query->from('item')
            where(['_id' => $id]) // [[\RethinkId]] へ暗黙に型キャスト
            ->one();
        ...
    }
}
```

ただし、[[\RethinkId]] を含む他のカラムがある場合は、型キャストが必要になるかもしれない可能性について、あなた自身が面倒を見なければなりません。
