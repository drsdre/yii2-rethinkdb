RethinkId specifics
=================

## Getting scalar from document ID

Remember: RethinkDB document id (`_id` field) is not scalar, but an instance of [[\RethinkId]] class.
To get actual Rethink ID string your should typecast [[\RethinkId]] instance to string:

```php
$query = new Query;
$row = $query->from('customer')->one();
var_dump($row['_id']); // outputs: "object(RethinkId)"
var_dump((string) $row['_id']); // outputs "string 'acdfgdacdhcbdafa'"
```

Although this fact is very useful sometimes, it often produces some problems.
You may face them in URL composition or attempt of saving "_id" to other storage.
In these cases, ensure you have converted [[\RethinkId]] into the string:

```php
/* @var $this yii\web\View */
echo $this->createUrl(['item/update', 'id' => (string) $row['_id']]);
```

Same applies to implementing user identity which is stored in RethinkDB. When implementing
[[\yii\web\IdentityInterface::getId()]] you should typecast [[\RethinkId]] class to scalar
in order for authentication to work.

## Getting document ID from scalar

While building condition, values for the key '_id' will be automatically cast to [[\RethinkId]] instance,
even if they are plain strings. So it is not necessary for you to perform back cast of string '_id'
representation:

```php
use yii\web\Controller;
use yii\rethinkdb\Query;

class ItemController extends Controller
{
    /**
     * @param string $id RethinkId string (not object)
     */
    public function actionUpdate($id)
    {
        $query = new Query;
        $row = $query->from('item')
            where(['_id' => $id]) // implicit typecast to [[\RethinkId]]
            ->one();
        ...
    }
}
```

However, if you have other columns, containing [[\RethinkId]], you
should take care of possible typecast on your own.
