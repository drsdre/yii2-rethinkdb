<?php

namespace yiiunit\extensions\rethinkdb\validators;

use yii\base\Model;
use yii\rethinkdb\validators\RethinkDateValidator;
use yiiunit\extensions\rethinkdb\TestCase;

class RethinkDateValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testValidateValue()
    {
        $validator = new RethinkDateValidator();
        $this->assertFalse($validator->validate('string'));
        $this->assertTrue($validator->validate(new \RethinkDate(time())));
    }

    public function testValidateAttribute()
    {
        $model = new RethinkDateTestModel();

        $model->date = 'string';
        $this->assertFalse($model->validate());
        $model->date = new \RethinkDate(time());
        $this->assertTrue($model->validate());
    }

    public function testRethinkDateAttribute()
    {
        $model = new RethinkDateTestModel();

        $model->date = '05/08/2015';
        $this->assertTrue($model->validate());
        $this->assertTrue($model->rethinkDate instanceof \RethinkDate);
        $this->assertEquals(strtotime('2015-05-08'), $model->rethinkDate->sec);
    }
}

class RethinkDateTestModel extends Model
{
    public $date;
    public $rethinkDate;

    public function rules()
    {
        return [
            ['date', RethinkDateValidator::className(), 'format' => 'MM/dd/yyyy', 'rethinkDateAttribute' => 'rethinkDate']
        ];
    }
}