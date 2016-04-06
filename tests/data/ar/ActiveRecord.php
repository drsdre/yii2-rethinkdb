<?php

namespace yiiunit\extensions\rethinkdb\data\ar;

/**
 * Test Rethink ActiveRecord
 */
class ActiveRecord extends \yii\rethinkdb\ActiveRecord
{
    public static $db;

    public static function getDb()
    {
        return self::$db;
    }
}
