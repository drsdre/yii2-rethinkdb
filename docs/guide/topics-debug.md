Using the RethinkDB DebugPanel
============================

The yii2 RethinkDB extensions provides a debug panel that can be integrated with the yii debug module
and shows the executed RethinkDB queries.

Add the following to you application config to enable it (if you already have the debug module
enabled, it is sufficient to just add the panels configuration):

```php
    // ...
    'bootstrap' => ['debug'],
    'modules' => [
        'debug' => [
            'class' => 'yii\\debug\\Module',
            'panels' => [
                'rethinkdb' => [
                    'class' => 'yii\\rethinkdb\\debug\\RethinkDbPanel',
                    // 'db' => 'db', // RethinkDB component ID, defaults to `db`. Uncomment and change this line, if you registered RethinkDB component with a different ID.
                ],
            ],
        ],
    ],
    // ...
```
