<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\rethinkdb\validators;

use yii\validators\DateValidator;

/**
 * RethinkDateValidator is an enhanced version of [[DateValidator]], which supports [[\RethinkDate]] values.
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
 *             ['date', 'yii\rethinkdb\validators\RethinkDateValidator', 'format' => 'MM/dd/yyyy']
 *         ];
 *     }
 * }
 * ~~~
 *
 * @see DateValidator
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0.4
 */
class RethinkDateValidator extends DateValidator
{
    /**
     * @var string the name of the attribute to receive the parsing result as [[\RethinkDate]] instance.
     * When this property is not null and the validation is successful, the named attribute will
     * receive the parsing result as [[\RethinkDate]] instance.
     *
     * This can be the same attribute as the one being validated. If this is the case,
     * the original value will be overwritten with the value after successful validation.
     */
    public $rethinkDateAttribute;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $rethinkDateAttribute = $this->rethinkDateAttribute;
        if ($this->timestampAttribute === null) {
            $this->timestampAttribute = $rethinkDateAttribute;
        }

        $originalErrorCount = count($model->getErrors($attribute));
        parent::validateAttribute($model, $attribute);
        $afterValidateErrorCount = count($model->getErrors($attribute));

        if ($originalErrorCount === $afterValidateErrorCount) {
            if ($this->rethinkDateAttribute !== null) {
                $timestamp = $model->{$this->timestampAttribute};
                $rethinkDateAttributeValue = $model->{$this->rethinkDateAttribute};
                // ensure "dirty attributes" support :
                if (!($rethinkDateAttributeValue instanceof \RethinkDate) || $rethinkDateAttributeValue->sec !== $timestamp) {
                    $model->{$this->rethinkDateAttribute} = new \RethinkDate($timestamp);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function parseDateValue($value)
    {
        if ($value instanceof \RethinkDate) {
            return $value->sec;
        }
        return parent::parseDateValue($value);
    }
}