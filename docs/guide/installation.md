Installation
============

## Requirements

This extension requires [RethinkDB PHP Extension](http://us1.php.net/manual/en/book.rethink.php) version 1.5.0 or higher.

## Getting Composer package

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-rethinkdb
```

or add

```
"yiisoft/yii2-rethinkdb": "~2.0.0"
```

to the require section of your composer.json.

## Configuring application

To use this extension, simply add the following code in your application configuration:

```php
return [
    //....
    'components' => [
        'rethinkdb' => [
            'class' => '\yii\rethinkdb\Connection',
            'dsn' => 'rethinkdb://developer:password@localhost:27017/mydatabase',
        ],
    ],
];
```
