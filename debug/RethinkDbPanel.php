<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\rethinkdb\debug;

use yii\debug\panels\DbPanel;
use yii\log\Logger;

/**
 * RethinkDbPanel panel that collects and displays RethinkDB queries performed.
 *
 * @author Klimov Paul <klimov@zfort.com>
 * @since 2.0.1
 */
class RethinkDbPanel extends DbPanel
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'RethinkDB';
    }

    /**
     * @inheritdoc
     */
    public function getSummaryName()
    {
        return 'RethinkDB';
    }

    /**
     * Returns all profile logs of the current request for this panel.
     * @return array
     */
    public function getProfileLogs()
    {
        $target = $this->module->logTarget;

        return $target->filterMessages($target->messages, Logger::LEVEL_PROFILE, [
            'yii\rethinkdb\Collection::*',
            'yii\rethinkdb\Query::*',
            'yii\rethinkdb\Database::*',
        ]);
    }
} 