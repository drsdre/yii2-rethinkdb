<?php

namespace yiiunit\extensions\rethinkdb\file;

use yiiunit\extensions\rethinkdb\TestCase;

/**
 * @group rethinkdb
 */
class CollectionTest extends TestCase
{
    protected function tearDown()
    {
        $this->dropFileCollection('fs');
        parent::tearDown();
    }

    // Tests :

    public function testGetChunkCollection()
    {
        $collection = $this->getConnection()->getFileCollection();
        $chunkCollection = $collection->getChunkCollection();
        $this->assertTrue($chunkCollection instanceof \yii\rethinkdb\Collection);
        $this->assertTrue($chunkCollection->rethinkCollection instanceof \RethinkCollection);
    }

    public function testFind()
    {
        $collection = $this->getConnection()->getFileCollection();
        $cursor = $collection->find();
        $this->assertTrue($cursor instanceof \RethinkGridFSCursor);
    }

    public function testInsertFile()
    {
        $collection = $this->getConnection()->getFileCollection();

        $filename = __FILE__;
        $id = $collection->insertFile($filename);
        $this->assertTrue($id instanceof \RethinkId);

        $files = $this->findAll($collection);
        $this->assertEquals(1, count($files));

        /* @var $file \RethinkGridFSFile */
        $file = $files[0];
        $this->assertEquals($filename, $file->getFilename());
        $this->assertEquals(file_get_contents($filename), $file->getBytes());
    }

    public function testInsertFileContent()
    {
        $collection = $this->getConnection()->getFileCollection();

        $bytes = 'Test file content';
        $id = $collection->insertFileContent($bytes);
        $this->assertTrue($id instanceof \RethinkId);

        $files = $this->findAll($collection);
        $this->assertEquals(1, count($files));

        /* @var $file \RethinkGridFSFile */
        $file = $files[0];
        $this->assertEquals($bytes, $file->getBytes());
    }

    /**
     * @depends testInsertFileContent
     */
    public function testGet()
    {
        $collection = $this->getConnection()->getFileCollection();

        $bytes = 'Test file content';
        $id = $collection->insertFileContent($bytes);

        $file = $collection->get($id);
        $this->assertTrue($file instanceof \RethinkGridFSFile);
        $this->assertEquals($bytes, $file->getBytes());
    }

    /**
     * @depends testGet
     */
    public function testDelete()
    {
        $collection = $this->getConnection()->getFileCollection();

        $bytes = 'Test file content';
        $id = $collection->insertFileContent($bytes);

        $this->assertTrue($collection->delete($id));

        $file = $collection->get($id);
        $this->assertNull($file);
    }
}
