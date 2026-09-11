<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Media\Models;

use PHPUnit\Framework\TestCase;
use Modules\Media\Models\Media as MediaModel;

class MediaModelTest extends TestCase
{
    /**
     * Tests that all default property values are null.
     */
    public function testDefaults()
    {
        $model = new MediaModel();

        self::assertNull($model->getId());
        self::assertNull($model->getUrl());
        self::assertNull($model->getUrlThumb());
        self::assertNull($model->getEnding());
        self::assertNull($model->getName());
        self::assertNull($model->getDatetime());
        self::assertNull($model->getCatName());
        self::assertNull($model->getCatId());
    }

    /**
     * Tests that setId() casts the value to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new MediaModel();
        $model->setId('42');

        self::assertEquals(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setUrl() casts the value to string.
     */
    public function testSetUrlCastsToString()
    {
        $model = new MediaModel();
        $model->setUrl(12345);

        self::assertEquals('12345', $model->getUrl());
        self::assertIsString($model->getUrl());
    }

    /**
     * Tests that setUrlThumb() casts null to empty string.
     */
    public function testSetUrlThumbCastsNullToEmptyString()
    {
        $model = new MediaModel();
        $model->setUrlThumb(null);

        self::assertEquals('', $model->getUrlThumb());
    }

    /**
     * Tests that setEnding() stores the value as a string.
     */
    public function testSetEnding()
    {
        $model = new MediaModel();
        $model->setEnding('jpeg');

        self::assertEquals('jpeg', $model->getEnding());
        self::assertIsString($model->getEnding());
    }

    /**
     * Tests that setName() casts the value to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new MediaModel();
        $model->setName(99);

        self::assertEquals('99', $model->getName());
    }

    /**
     * Tests that setDatetime() casts the value to string.
     */
    public function testSetDatetimeCastsToString()
    {
        $model = new MediaModel();
        $model->setDatetime('2024-06-15 14:30:00');

        self::assertEquals('2024-06-15 14:30:00', $model->getDatetime());
        self::assertIsString($model->getDatetime());
    }

    /**
     * Tests that setCatName() stores the value as-is (no cast).
     */
    public function testSetCatName()
    {
        $model = new MediaModel();
        $model->setCatName('Images');

        self::assertEquals('Images', $model->getCatName());
    }

    /**
     * Tests that setCatName() accepts null (for LEFT JOIN with no match).
     */
    public function testSetCatNameNull()
    {
        $model = new MediaModel();
        $model->setCatName(null);

        self::assertNull($model->getCatName());
    }

    /**
     * Tests that setCatId() stores the value as-is (no cast).
     */
    public function testSetCatId()
    {
        $model = new MediaModel();
        $model->setCatId(5);

        self::assertEquals(5, $model->getCatId());
    }

    /**
     * Tests that setCatId() accepts 0 for uncategorised media.
     */
    public function testSetCatIdZero()
    {
        $model = new MediaModel();
        $model->setCatId(0);

        self::assertEquals(0, $model->getCatId());
    }

    /**
     * Tests that setters and getters round-trip correctly for a fully populated model.
     */
    public function testFullRoundTrip()
    {
        $model = new MediaModel();
        $model->setId(10);
        $model->setUrl('upload/photo.jpg');
        $model->setUrlThumb('upload/photo_thumb.jpg');
        $model->setEnding('jpg');
        $model->setName('photo.jpg');
        $model->setDatetime('2024-10-01 09:15:30');
        $model->setCatName('Images');
        $model->setCatId(1);

        self::assertEquals(10, $model->getId());
        self::assertEquals('upload/photo.jpg', $model->getUrl());
        self::assertEquals('upload/photo_thumb.jpg', $model->getUrlThumb());
        self::assertEquals('jpg', $model->getEnding());
        self::assertEquals('photo.jpg', $model->getName());
        self::assertEquals('2024-10-01 09:15:30', $model->getDatetime());
        self::assertEquals('Images', $model->getCatName());
        self::assertEquals(1, $model->getCatId());
    }

    /**
     * Tests that the model can be used as a category-only object (for cat operations).
     */
    public function testCategoryOnlyUsage()
    {
        $model = new MediaModel();
        $model->setId(7);
        $model->setCatName('Audio');

        self::assertEquals(7, $model->getId());
        self::assertEquals('Audio', $model->getCatName());
        self::assertNull($model->getUrl());
        self::assertNull($model->getName());
    }
}
