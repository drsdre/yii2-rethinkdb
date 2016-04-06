<?php

namespace yiiunit\extensions\rethinkdb;

use yii\rethinkdb\Collection;
use yii\rethinkdb\file\Collection as FileCollection;
use yii\rethinkdb\Connection;
use yii\rethinkdb\Database;

/**
 * @group rethinkdb
 */
class ConnectionTest extends TestCase
{
    public function testConstruct()
    {
        $connection = $this->getConnection(false);
        $params = $this->rethinkDbConfig;

        $connection->open();

        $this->assertEquals($params['dsn'], $connection->dsn);
        $this->assertEquals($params['defaultDatabaseName'], $connection->defaultDatabaseName);
        $this->assertEquals($params['options'], $connection->options);
    }

    public function testOpenClose()
    {
        $connection = $this->getConnection(false, false);

        $this->assertFalse($connection->isActive);
        $this->assertEquals(null, $connection->rethinkClient);

        $connection->open();
        $this->assertTrue($connection->isActive);
        $this->assertTrue(is_object($connection->rethinkClient));

        $connection->close();
        $this->assertFalse($connection->isActive);
        $this->assertEquals(null, $connection->rethinkClient);

        $connection = new Connection;
        $connection->dsn = 'unknown::memory:';
        $this->setExpectedException('yii\rethinkdb\Exception');
        $connection->open();
    }

    public function testGetDatabase()
    {
        $connection = $this->getConnection();

        $database = $connection->getDatabase($connection->defaultDatabaseName);
        $this->assertTrue($database instanceof Database);
        $this->assertTrue($database->rethinkDb instanceof \RethinkDB);

        $database2 = $connection->getDatabase($connection->defaultDatabaseName);
        $this->assertTrue($database === $database2);

        $databaseRefreshed = $connection->getDatabase($connection->defaultDatabaseName, true);
        $this->assertFalse($database === $databaseRefreshed);
    }

    /**
     * Data provider for [[testFetchDefaultDatabaseName()]]
     * @return array test data
     */
    public function dataProviderFetchDefaultDatabaseName()
    {
        return [
            [
                'rethinkdb://travis:test@localhost:27017/dbname',
                'dbname',
            ],
            [
                'rethinkdb://travis:test@localhost:27017/dbname?replicaSet=test&connectTimeoutMS=300000',
                'dbname',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderFetchDefaultDatabaseName
     *
     * @param string $dsn
     * @param string $databaseName
     */
    public function testFetchDefaultDatabaseName($dsn, $databaseName)
    {
        $connection = new Connection();
        $connection->dsn = $dsn;

        $reflection = new \ReflectionObject($connection);
        $method = $reflection->getMethod('fetchDefaultDatabaseName');
        $method->setAccessible(true);
        $method->invoke($connection);

        $this->assertEquals($databaseName, $connection->defaultDatabaseName);
    }

    /**
     * @depends testGetDatabase
     * @depends testFetchDefaultDatabaseName
     */
    public function testGetDefaultDatabase()
    {
        $connection = new Connection();
        $connection->dsn = $this->rethinkDbConfig['dsn'];
        $connection->defaultDatabaseName = $this->rethinkDbConfig['defaultDatabaseName'];
        $database = $connection->getDatabase();
        $this->assertTrue($database instanceof Database, 'Unable to get default database!');

        $connection = new Connection();
        $connection->dsn = $this->rethinkDbConfig['dsn'];
        $connection->options = ['db' => $this->rethinkDbConfig['defaultDatabaseName']];
        $database = $connection->getDatabase();
        $this->assertTrue($database instanceof Database, 'Unable to determine default database from options!');

        $connection = new Connection();
        $connection->dsn = $this->rethinkDbConfig['dsn'] . '/' . $this->rethinkDbConfig['defaultDatabaseName'];
        $database = $connection->getDatabase();
        $this->assertTrue($database instanceof Database, 'Unable to determine default database from dsn!');
    }

    /**
     * @depends testGetDefaultDatabase
     */
    public function testGetCollection()
    {
        $connection = $this->getConnection();

        $collection = $connection->getCollection('customer');
        $this->assertTrue($collection instanceof Collection);

        $collection2 = $connection->getCollection('customer');
        $this->assertTrue($collection === $collection2);

        $collection2 = $connection->getCollection('customer', true);
        $this->assertFalse($collection === $collection2);
    }

    /**
     * @depends testGetDefaultDatabase
     */
    public function testGetFileCollection()
    {
        $connection = $this->getConnection();

        $collection = $connection->getFileCollection('testfs');
        $this->assertTrue($collection instanceof FileCollection);

        $collection2 = $connection->getFileCollection('testfs');
        $this->assertTrue($collection === $collection2);

        $collection2 = $connection->getFileCollection('testfs', true);
        $this->assertFalse($collection === $collection2);
    }
}
