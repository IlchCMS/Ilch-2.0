<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Downloads\Tests;

use PHPUnit\Framework\TestCase;
use Modules\Downloads\Models\DownloadsItem;

class DownloadsItemModelTest extends TestCase
{
    /**
     * Tests that default property values are correct.
     */
    public function testDefaults()
    {
        $model = new DownloadsItem();

        self::assertNull($model->getId());
        self::assertEquals(0, $model->getSort());
        self::assertNull($model->getType());
        self::assertEquals(0, $model->getParentId());
        self::assertNull($model->getTitle());
        self::assertNull($model->getDesc());
        self::assertNull($model->getAccess());
    }

    /**
     * Tests that setId() stores the value.
     */
    public function testSetId()
    {
        $model = new DownloadsItem();
        $model->setId(5);

        self::assertEquals(5, $model->getId());
    }

    /**
     * Tests that setSort() stores the value.
     */
    public function testSetSort()
    {
        $model = new DownloadsItem();
        $model->setSort(3);

        self::assertEquals(3, $model->getSort());
    }

    /**
     * Tests that setType() stores the value.
     */
    public function testSetType()
    {
        $model = new DownloadsItem();
        $model->setType(1);

        self::assertEquals(1, $model->getType());
    }

    /**
     * Tests that setParentId() stores the value.
     */
    public function testSetParentId()
    {
        $model = new DownloadsItem();
        $model->setParentId(7);

        self::assertEquals(7, $model->getParentId());
    }

    /**
     * Tests that setTitle() stores the value.
     */
    public function testSetTitle()
    {
        $model = new DownloadsItem();
        $model->setTitle('My Category');

        self::assertEquals('My Category', $model->getTitle());
    }

    /**
     * Tests that setDesc() stores the value.
     */
    public function testSetDesc()
    {
        $model = new DownloadsItem();
        $model->setDesc('Description text');

        self::assertEquals('Description text', $model->getDesc());
    }

    /**
     * Tests that setAccess() stores the value and returns $this.
     */
    public function testSetAccess()
    {
        $model = new DownloadsItem();
        $result = $model->setAccess('1,2,3');

        self::assertSame($model, $result);
        self::assertEquals('1,2,3', $model->getAccess());
    }

    /**
     * Tests that setAccess() accepts an empty string.
     */
    public function testSetAccessEmptyString()
    {
        $model = new DownloadsItem();
        $result = $model->setAccess('');

        self::assertSame($model, $result);
        self::assertEquals('', $model->getAccess());
    }

    /**
     * Tests that all setters can be called sequentially.
     */
    public function testAllSettersSequential()
    {
        $model = new DownloadsItem();
        $model->setId(10);
        $model->setSort(2);
        $model->setType(2);
        $model->setParentId(5);
        $model->setTitle('Test Item');
        $model->setDesc('Test description');
        $model->setAccess('1,4');

        self::assertEquals(10, $model->getId());
        self::assertEquals(2, $model->getSort());
        self::assertEquals(2, $model->getType());
        self::assertEquals(5, $model->getParentId());
        self::assertEquals('Test Item', $model->getTitle());
        self::assertEquals('Test description', $model->getDesc());
        self::assertEquals('1,4', $model->getAccess());
    }

    /**
     * Tests that setTitle() accepts an empty string.
     */
    public function testSetTitleEmptyString()
    {
        $model = new DownloadsItem();
        $model->setTitle('');

        self::assertEquals('', $model->getTitle());
    }

    /**
     * Tests that setDesc() accepts an empty string.
     */
    public function testSetDescEmptyString()
    {
        $model = new DownloadsItem();
        $model->setDesc('');

        self::assertEquals('', $model->getDesc());
    }
}
