<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Downloads\Tests;

use PHPUnit\Framework\TestCase;
use Modules\Downloads\Models\File;

class DownloadsFileModelTest extends TestCase
{
    /**
     * Tests that default property values are correct.
     */
    public function testDefaults()
    {
        $model = new File();

        self::assertNull($model->getId());
        self::assertNull($model->getAccess());
    }

    /**
     * Tests that setId() stores the value.
     */
    public function testSetId()
    {
        $model = new File();
        $model->setId(5);

        self::assertEquals(5, $model->getId());
    }

    /**
     * Tests that setFileId() stores the value and getFileId() returns it as string.
     */
    public function testSetFileId()
    {
        $model = new File();
        $model->setFileId('10');

        self::assertEquals('10', $model->getFileId());
    }

    /**
     * Tests that setFileTitle() stores the value.
     */
    public function testSetFileTitle()
    {
        $model = new File();
        $model->setFileTitle('Ubuntu.iso');

        self::assertEquals('Ubuntu.iso', $model->getFileTitle());
    }

    /**
     * Tests that setFileDesc() stores the value.
     */
    public function testSetFileDesc()
    {
        $model = new File();
        $model->setFileDesc('Ubuntu 22.04 LTS');

        self::assertEquals('Ubuntu 22.04 LTS', $model->getFileDesc());
    }

    /**
     * Tests that setFileImage() stores the value.
     */
    public function testSetFileImage()
    {
        $model = new File();
        $model->setFileImage('/media/ubuntu.iso');

        self::assertEquals('/media/ubuntu.iso', $model->getFileImage());
    }

    /**
     * Tests that setFileImage() accepts an empty string.
     */
    public function testSetFileImageEmptyString()
    {
        $model = new File();
        $model->setFileImage('');

        self::assertEquals('', $model->getFileImage());
    }

    /**
     * Tests that setItemId() stores the value.
     */
    public function testSetItemId()
    {
        $model = new File();
        $model->setItemId(2);

        self::assertEquals('2', $model->getItemId());
    }

    /**
     * Tests that setVisits() stores the value.
     */
    public function testSetVisits()
    {
        $model = new File();
        $model->setVisits(42);

        self::assertEquals(42, $model->getVisits());
    }

    /**
     * Tests that setFileUrl() stores the value.
     */
    public function testSetFileUrl()
    {
        $model = new File();
        $model->setFileUrl('/media/ubuntu.iso');

        self::assertEquals('/media/ubuntu.iso', $model->getFileUrl());
    }

    /**
     * Tests that setFileUrl() accepts an empty string.
     */
    public function testSetFileUrlEmptyString()
    {
        $model = new File();
        $model->setFileUrl('');

        self::assertEquals('', $model->getFileUrl());
    }

    /**
     * Tests that setAccess() stores the value and returns $this.
     */
    public function testSetAccess()
    {
        $model = new File();
        $result = $model->setAccess('1,2');

        self::assertSame($model, $result);
        self::assertEquals('1,2', $model->getAccess());
    }

    /**
     * Tests that setAccess() accepts an empty string.
     */
    public function testSetAccessEmptyString()
    {
        $model = new File();
        $result = $model->setAccess('');

        self::assertSame($model, $result);
        self::assertEquals('', $model->getAccess());
    }

    /**
     * Tests that setFileThumb() stores the value.
     */
    public function testSetFileThumb()
    {
        $model = new File();
        $model->setFileThumb('/media/ubuntu_thumb.png');

        self::assertEquals('/media/ubuntu_thumb.png', $model->getFileThumb());
    }

    /**
     * Tests that setFileThumb() accepts an empty string.
     */
    public function testSetFileThumbEmptyString()
    {
        $model = new File();
        $model->setFileThumb('');

        self::assertEquals('', $model->getFileThumb());
    }

    /**
     * Tests that all setters can be called sequentially.
     */
    public function testAllSettersSequential()
    {
        $model = new File();
        $model->setId(1);
        $model->setFileId('10');
        $model->setFileTitle('Ubuntu.iso');
        $model->setFileDesc('Ubuntu 22.04');
        $model->setFileImage('/media/ubuntu.iso');
        $model->setItemId(2);
        $model->setVisits(5);
        $model->setFileUrl('/media/ubuntu.iso');
        $model->setFileThumb('/media/ubuntu_thumb.png');
        $model->setAccess('1,2');

        self::assertEquals(1, $model->getId());
        self::assertEquals('10', $model->getFileId());
        self::assertEquals('Ubuntu.iso', $model->getFileTitle());
        self::assertEquals('Ubuntu 22.04', $model->getFileDesc());
        self::assertEquals('/media/ubuntu.iso', $model->getFileImage());
        self::assertEquals('2', $model->getItemId());
        self::assertEquals(5, $model->getVisits());
        self::assertEquals('/media/ubuntu.iso', $model->getFileUrl());
        self::assertEquals('/media/ubuntu_thumb.png', $model->getFileThumb());
        self::assertEquals('1,2', $model->getAccess());
    }
}
