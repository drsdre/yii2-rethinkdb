Using the Cache component
=========================

To use the `Cache` component, in addition to configuring the connection as described in [Installation](installation.md) section,
you also have to configure the `cache` component to be `yii\rethinkdb\Cache`:

```php
return [
    //....
    'components' => [
        // ...
        'cache' => [
            'class' => 'yii\rethinkdb\Cache',
        ],
    ]
];
```
