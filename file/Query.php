<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\rethinkdb\file;

use Yii;

/**
 * Query represents Rethink "find" operation for GridFS collection.
 *
 * Query behaves exactly as regular [[\yii\rethinkdb\Query]].
 * Found files will be represented as arrays of file document attributes with
 * additional 'file' key, which stores [[\RethinkGridFSFile]] instance.
 *
 * @property Collection $collection Collection instance. This property is read-only.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Query extends \yii\rethinkdb\Query
{
    /**
     * @inheritdoc
     */
    public function one($db = null)
    {
        $row = parent::one($db);
        if ($row !== false) {
            $models = $this->populate([$row]);
            return reset($models) ?: null;
        } else {
            return null;
        }
    }

    /**
     * Returns the Rethink collection for this query.
     * @param \yii\rethinkdb\Connection $db Rethink connection.
     * @return Collection collection instance.
     */
    public function getCollection($db = null)
    {
        if ($db === null) {
            $db = Yii::$app->get('rethinkdb');
        }

        return $db->getFileCollection($this->from);
    }

    /**
     * Converts the raw query results into the format as specified by this query.
     * This method is internally used to convert the data fetched from database
     * into the format as required by this query.
     * @param array $rows the raw query result from database
     * @return array the converted query result
     */
    public function populate($rows)
    {
        $result = [];
        foreach ($rows as $file) {
            $row = $file->file;
            $row['file'] = $file;
            $result[] = $row;
        }
        return parent::populate($result);
    }
}
