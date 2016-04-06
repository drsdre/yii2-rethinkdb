<?php

namespace yiiunit\extensions\rethinkdb\data\ar\file;

/**
 * Test Rethink ActiveRecord
 */
class ActiveRecord extends \yii\rethinkdb\file\ActiveRecord
{
    public static $db;

    public static function getDb()
    {
        return self::$db;
    }
}
