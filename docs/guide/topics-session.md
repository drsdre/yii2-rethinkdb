Using the Session component
===========================

To use the `Session` component, in addition to configuring the connection as described in [Installation](installation.md) section,
you also have to configure the `session` component to be `yii\rethinkdb\Session`:

```php
return [
    //....
    'components' => [
        // ...
        'session' => [
            'class' => 'yii\rethinkdb\Session',
        ],
    ]
];
```
