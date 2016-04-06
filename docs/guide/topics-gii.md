Using Gii generator
===================

This extension provides a code generator, which can be integrated with yii 'gii' module. It allows generation of the
Active Record code. In order to enable it, you should adjust your application configuration in following way:

```php
return [
    //....
    'modules' => [
        // ...
        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'rethinkDbModel' => [
                    'class' => 'yii\rethinkdb\gii\model\Generator'
                ]
            ],
        ],
    ]
];
```

> Note: since RethinkDB is schemaless, there is not much information, which generated code may base on. So generated code
  is very basic and definitely requires adjustments.

