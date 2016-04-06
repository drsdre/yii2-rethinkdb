<?php

namespace yiiunit\extensions\rethinkdb\data\ar\file;

use yii\rethinkdb\file\ActiveQuery;

/**
 * CustomerFileQuery
 */
class CustomerFileQuery extends ActiveQuery
{
    public function activeOnly()
    {
        $this->andWhere(['status' => 2]);

        return $this;
    }
}
