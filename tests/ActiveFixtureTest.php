<?php

namespace yiiunit\extensions\rethinkdb;

use yii\rethinkdb\ActiveFixture;
use yiiunit\extensions\rethinkdb\data\ar\Customer;

class ActiveFixtureTest extends TestCase
{
    protected function tearDown()
    {
        $this->dropCollection(Customer::collectionName());
        parent::tearDown();
    }

    public function testLoadCollection()
    {
        /* @var $fixture ActiveFixture|\PHPUnit_Framework_MockObject_MockObject */
        $fixture = $this->getMock(
            ActiveFixture::className(),
            ['getData'],
            [
                [
                    'db' => $this->getConnection(),
                    'collectionName' => Customer::collectionName()
                ]
            ]
        );
        $fixture->expects($this->any())->method('getData')->will($this->returnValue([
            ['name' => 'name1'],
            ['name' => 'name2'],
        ]));

        $fixture->load();

        $rows = $this->findAll($this->getConnection()->getCollection(Customer::collectionName()));
        $this->assertCount(2, $rows);
    }

    public function testLoadClass()
    {
        /* @var $fixture ActiveFixture|\PHPUnit_Framework_MockObject_MockObject */
        $fixture = $this->getMock(
            ActiveFixture::className(),
            ['getData'],
            [
                [
                    'db' => $this->getConnection(),
                    'modelClass' => Customer::className()
                ]
            ]
        );
        $fixture->expects($this->any())->method('getData')->will($this->returnValue([
            ['name' => 'name1'],
            ['name' => 'name2'],
        ]));

        $fixture->load();

        $rows = $this->findAll($this->getConnection()->getCollection(Customer::collectionName()));
        $this->assertCount(2, $rows);
    }

    /**
     * @depends testLoadCollection
     *
     * @see https://github.com/yiisoft/yii2-rethinkdb/pull/40
     */
    public function testLoadEmptyData()
    {
        /* @var $fixture ActiveFixture|\PHPUnit_Framework_MockObject_MockObject */
        $fixture = $this->getMock(
            ActiveFixture::className(),
            ['getData'],
            [
                [
                    'db' => $this->getConnection(),
                    'collectionName' => Customer::collectionName()
                ]
            ]
        );
        $fixture->expects($this->any())->method('getData')->will($this->returnValue([
            // empty
        ]));

        $fixture->load(); // should be no error

        $rows = $this->findAll($this->getConnection()->getCollection(Customer::collectionName()));
        $this->assertEmpty($rows);
    }
}