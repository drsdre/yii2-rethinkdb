<?php

namespace yiiunit\extensions\rethinkdb\data\ar;

use yii\rethinkdb\ActiveQuery;

/**
 * CustomerQuery
 */
class CustomerQuery extends ActiveQuery
{
    public function activeOnly()
    {
        $this->andWhere(['status' => 2]);

        return $this;
    }
}
