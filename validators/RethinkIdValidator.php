<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\rethinkdb\validators;

use yii\base\InvalidConfigException;
use yii\validators\Validator;
use Yii;

/**
 * RethinkIdValidator verifies if the attribute is a valid Rethink ID.
 * Attribute will be considered as valid, if it is an instance of [[\RethinkId]] or a its string value.
 *
 * Usage example:
 *
 * ~~~
 * class Customer extends yii\rethinkdb\ActiveRecord
 * {
 *     ...
 *     public function rules()
 *     {
 *         return [
 *             ['_id', 'yii\rethinkdb\validators\RethinkIdValidator']
 *         ];
 *     }
 * }
 * ~~~
 *
 * This validator may also serve as a filter, allowing conversion of Rethink ID value either to the plain string
 * or to [[\RethinkId]] instance. You can enable this feature via [[forceFormat]].
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0.4
 */
class RethinkIdValidator extends Validator
{
    /**
     * @var string|null specifies the format, which validated attribute value should be converted to
     * in case validation was successful.
     * valid values are:
     * - 'string' - enforce value converted to plain string.
     * - 'object' - enforce value converted to [[\RethinkId]] instance.
     * If not set - no conversion will be performed, leaving attribute value intact.
     */
    public $forceFormat;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid.');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $rethinkId = $this->parseRethinkId($value);
        if (is_object($rethinkId)) {
            if ($this->forceFormat !== null) {
                switch ($this->forceFormat) {
                    case 'string' : {
                        $model->$attribute = $rethinkId->__toString();
                        break;
                    }
                    case 'object' : {
                        $model->$attribute = $rethinkId;
                        break;
                    }
                    default: {
                        throw new InvalidConfigException("Unrecognized format '{$this->forceFormat}'");
                    }
                }
            }
        } else {
            $this->addError($model, $attribute, $this->message, []);
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        return is_object($this->parseRethinkId($value)) ? null : [$this->message, []];
    }

    /**
     * @param mixed $value
     * @return \RethinkId|null
     */
    private function parseRethinkId($value)
    {
        if ($value instanceof \RethinkId) {
            return $value;
        }
        try {
            return new \RethinkId($value);
        } catch (\Exception $e) {
            return null;
        }
    }
} 