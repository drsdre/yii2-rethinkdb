<?php

namespace yiiunit\extensions\rethinkdb;

use yii\helpers\ArrayHelper;
use yii\rethinkdb\Connection;
use Yii;
use yii\rethinkdb\Exception;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    public static $params;
    /**
     * @var array Rethink connection configuration.
     */
    protected $rethinkDbConfig = [
        'dsn' => 'rethinkdb://localhost:27017',
        'defaultDatabaseName' => 'yii2test',
        'options' => [],
    ];
    /**
     * @var Connection Rethink connection instance.
     */
    protected $rethinkdb;

    protected function setUp()
    {
        parent::setUp();
        if (!extension_loaded('rethink')) {
            $this->markTestSkipped('rethink extension required.');
        }
        $config = self::getParam('rethinkdb');
        if (!empty($config)) {
            $this->rethinkDbConfig = $config;
        }
        //$this->mockApplication();
    }

    protected function tearDown()
    {
        if ($this->rethinkdb) {
            $this->rethinkdb->close();
        }
        $this->destroyApplication();
    }

    /**
     * Returns a test configuration param from /data/config.php
     * @param  string $name params name
     * @param  mixed $default default value to use when param is not set.
     * @return mixed  the value of the configuration param
     */
    public static function getParam($name, $default = null)
    {
        if (static::$params === null) {
            static::$params = require(__DIR__ . '/data/config.php');
        }

        return isset(static::$params[$name]) ? static::$params[$name] : $default;
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
        ], $config));
    }

    protected function getVendorPath()
    {
        $vendor = dirname(dirname(__DIR__)) . '/vendor';
        if (!is_dir($vendor)) {
            $vendor = dirname(dirname(dirname(dirname(__DIR__))));
        }
        return $vendor;
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        \Yii::$app = null;
    }

    /**
     * @param  boolean                 $reset whether to clean up the test database
     * @param  boolean                 $open  whether to open test database
     * @return \yii\rethinkdb\Connection
     */
    public function getConnection($reset = false, $open = true)
    {
        if (!$reset && $this->rethinkdb) {
            return $this->rethinkdb;
        }
        $db = new Connection();
        $db->dsn = $this->rethinkDbConfig['dsn'];
        $db->defaultDatabaseName = $this->rethinkDbConfig['defaultDatabaseName'];
        if (isset($this->rethinkDbConfig['options'])) {
            $db->options = $this->rethinkDbConfig['options'];
        }
        if ($open) {
            $db->open();
        }
        $this->rethinkdb = $db;

        return $db;
    }

    /**
     * Drops the specified collection.
     * @param string $name collection name.
     */
    protected function dropCollection($name)
    {
        if ($this->rethinkdb) {
            try {
                $this->rethinkdb->getCollection($name)->drop();
            } catch (Exception $e) {
                // shut down exception
            }
        }
    }

    /**
     * Drops the specified file collection.
     * @param string $name file collection name.
     */
    protected function dropFileCollection($name = 'fs')
    {
        if ($this->rethinkdb) {
            try {
                $this->rethinkdb->getFileCollection($name)->drop();
            } catch (Exception $e) {
                // shut down exception
            }
        }
    }

    /**
     * Finds all records in collection.
     * @param  \yii\rethinkdb\Collection $collection
     * @param  array                   $condition
     * @param  array                   $fields
     * @return array                   rows
     */
    protected function findAll($collection, $condition = [], $fields = [])
    {
        $cursor = $collection->find($condition, $fields);
        $result = [];
        foreach ($cursor as $data) {
            $result[] = $data;
        }

        return $result;
    }

    /**
     * Returns the Rethink server version.
     * @return string Rethink server version.
     */
    protected function getServerVersion()
    {
        $connection = $this->getConnection();
        $buildInfo = $connection->getDatabase()->executeCommand(['buildinfo' => true]);

        return $buildInfo['version'];
    }
}
