RethinkDb Extension for Yii 2
===========================

This extension provides the [RethinkDB](https://www.rethinkdb.com/) integration for the [Yii framework 2.0](http://www.yiiframework.com).

For license information check the [LICENSE](LICENSE.md)-file.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-rethinkdb/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-rethinkdb)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-rethinkdb/downloads.png)](https://packagist.org/packages/yiisoft/yii2-rethinkdb)
[![Build Status](https://travis-ci.org/yiisoft/yii2-rethinkdb.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-rethinkdb)


Installation
------------

This extension requires [RethinkDB PHP Extension](http://us1.php.net/manual/en/book.rethink.php) version 1.5.0 or higher.

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

Configuration
-------------

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
