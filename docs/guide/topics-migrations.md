Using Migrations
================

RethinkDB is schemaless and will create any missing collection on the first demand. However there are many cases, when
you may need applying persistent changes to the RethinkDB database. For example: you may need to create a collection with
some specific options or create indexes.
RethinkDB migrations are managed via [[yii\rethinkdb\console\controllers\MigrateController]], which is an analog of regular
[[\yii\console\controllers\MigrateController]].

In order to enable this command you should adjust the configuration of your console application:

```php
return [
    // ...
    'controllerMap' => [
        'rethinkdb-migrate' => 'yii\rethinkdb\console\controllers\MigrateController'
    ],
];
```

Below are some common usages of this command:

```
# creates a new migration named 'create_user_collection'
yii rethinkdb-migrate/create create_user_collection

# applies ALL new migrations
yii rethinkdb-migrate

# reverts the last applied migration
yii rethinkdb-migrate/down
```
