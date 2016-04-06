<?php

namespace yiiunit\extensions\rethinkdb\validators;

use yii\base\Model;
use yii\rethinkdb\validators\RethinkIdValidator;
use yiiunit\extensions\rethinkdb\TestCase;

class RethinkIdValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testValidateValue()
    {
        $validator = new RethinkIdValidator();
        $this->assertFalse($validator->validate('id'));
        $this->assertTrue($validator->validate(new \RethinkId('4d3ed089fb60ab534684b7e9')));
        $this->assertTrue($validator->validate('4d3ed089fb60ab534684b7e9'));
    }

    public function testValidateAttribute()
    {
        $model = new RethinkIdTestModel();
        $validator = new RethinkIdValidator();
        $validator->attributes = ['id'];
        $model->getValidators()->append($validator);

        $model->id = 'id';
        $this->assertFalse($model->validate());
        $model->id = new \RethinkId('4d3ed089fb60ab534684b7e9');
        $this->assertTrue($model->validate());
        $model->id = '4d3ed089fb60ab534684b7e9';
        $this->assertTrue($model->validate());
    }

    /**
     * @depends testValidateAttribute
     */
    public function testConvertValue()
    {
        $model = new RethinkIdTestModel();
        $validator = new RethinkIdValidator();
        $validator->attributes = ['id'];
        $model->getValidators()->append($validator);

        $validator->forceFormat = null;
        $model->id = '4d3ed089fb60ab534684b7e9';
        $model->validate();
        $this->assertTrue(is_string($model->id));
        $model->id = new \RethinkId('4d3ed089fb60ab534684b7e9');
        $model->validate();
        $this->assertTrue($model->id instanceof \RethinkId);

        $validator->forceFormat = 'object';
        $model->id = '4d3ed089fb60ab534684b7e9';
        $model->validate();
        $this->assertTrue($model->id instanceof \RethinkId);

        $validator->forceFormat = 'string';
        $model->id = new \RethinkId('4d3ed089fb60ab534684b7e9');
        $model->validate();
        $this->assertTrue(is_string($model->id));
    }
}

class RethinkIdTestModel extends Model
{
    public $id;
}