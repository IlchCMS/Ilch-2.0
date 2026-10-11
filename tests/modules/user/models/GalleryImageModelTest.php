<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\GalleryImage as GalleryImageModel;

class GalleryImageModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new GalleryImageModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new GalleryImageModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GalleryImageModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new GalleryImageModel();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new GalleryImageModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setImageId() sets and returns the image id.
     */
    public function testSetImageId()
    {
        $model = new GalleryImageModel();
        $model->setImageId('abc123');

        self::assertSame('abc123', $model->getImageId());
    }

    /**
     * Tests that setImageId() casts to string.
     */
    public function testSetImageIdCastsToString()
    {
        $model = new GalleryImageModel();
        $model->setImageId(456);

        self::assertSame('456', $model->getImageId());
        self::assertIsString($model->getImageId());
    }

    /**
     * Tests that setImageThumb() sets and returns the thumb.
     */
    public function testSetImageThumb()
    {
        $model = new GalleryImageModel();
        $model->setImageThumb('thumb_abc123.jpg');

        self::assertSame('thumb_abc123.jpg', $model->getImageThumb());
    }

    /**
     * Tests that setImageTitle() sets and returns the title.
     */
    public function testSetImageTitle()
    {
        $model = new GalleryImageModel();
        $model->setImageTitle('My Image');

        self::assertSame('My Image', $model->getImageTitle());
    }

    /**
     * Tests that setImageTitle() casts to string.
     */
    public function testSetImageTitleCastsToString()
    {
        $model = new GalleryImageModel();
        $model->setImageTitle(123);

        self::assertSame('123', $model->getImageTitle());
        self::assertIsString($model->getImageTitle());
    }

    /**
     * Tests that setImageDesc() sets and returns the description.
     */
    public function testSetImageDesc()
    {
        $model = new GalleryImageModel();
        $model->setImageDesc('My description');

        self::assertSame('My description', $model->getImageDesc());
    }

    /**
     * Tests that setImageDesc() casts to string.
     */
    public function testSetImageDescCastsToString()
    {
        $model = new GalleryImageModel();
        $model->setImageDesc(456);

        self::assertSame('456', $model->getImageDesc());
        self::assertIsString($model->getImageDesc());
    }

    /**
     * Tests that setCat() sets and returns the category.
     */
    public function testSetCat()
    {
        $model = new GalleryImageModel();
        $model->setCat('5');

        self::assertSame('5', $model->getCat());
    }

    /**
     * Tests that setCat() casts to string.
     */
    public function testSetCatCastsToString()
    {
        $model = new GalleryImageModel();
        $model->setCat(5);

        self::assertSame('5', $model->getCat());
        self::assertIsString($model->getCat());
    }

    /**
     * Tests that setVisits() sets and returns the visits counter.
     */
    public function testSetVisits()
    {
        $model = new GalleryImageModel();
        $model->setVisits('0');

        self::assertSame('0', $model->getVisits());
    }

    /**
     * Tests that setVisits() casts to string.
     */
    public function testSetVisitsCastsToString()
    {
        $model = new GalleryImageModel();
        $model->setVisits(10);

        self::assertSame('10', $model->getVisits());
        self::assertIsString($model->getVisits());
    }

    /**
     * Tests that setImageUrl() sets and returns the url.
     */
    public function testSetImageUrl()
    {
        $model = new GalleryImageModel();
        $model->setImageUrl('images/abc123.jpg');

        self::assertSame('images/abc123.jpg', $model->getImageUrl());
    }

    /**
     * Tests that setImageUrl() casts to string.
     */
    public function testSetImageUrlCastsToString()
    {
        $model = new GalleryImageModel();
        $model->setImageUrl(789);

        self::assertSame('789', $model->getImageUrl());
        self::assertIsString($model->getImageUrl());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new GalleryImageModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setImageId('abc123'));
        self::assertSame($model, $model->setImageThumb('thumb_abc123.jpg'));
        self::assertSame($model, $model->setImageTitle('Test'));
        self::assertSame($model, $model->setImageDesc('Desc'));
        self::assertSame($model, $model->setCat('5'));
        self::assertSame($model, $model->setVisits('0'));
        self::assertSame($model, $model->setImageUrl('image.jpg'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new GalleryImageModel())
            ->setId(1)
            ->setUserId(2)
            ->setImageId('abc123')
            ->setImageThumb('thumb_abc123.jpg')
            ->setImageTitle('My Image')
            ->setImageDesc('My description')
            ->setCat('5')
            ->setVisits('0')
            ->setImageUrl('images/abc123.jpg');

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('abc123', $model->getImageId());
        self::assertSame('thumb_abc123.jpg', $model->getImageThumb());
        self::assertSame('My Image', $model->getImageTitle());
        self::assertSame('My description', $model->getImageDesc());
        self::assertSame('5', $model->getCat());
        self::assertSame('0', $model->getVisits());
        self::assertSame('images/abc123.jpg', $model->getImageUrl());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new GalleryImageModel();

        self::assertNull($model->getId());
        self::assertNull($model->getUserId());
        self::assertNull($model->getImageId());
        self::assertNull($model->getImageThumb());
        self::assertNull($model->getImageTitle());
        self::assertNull($model->getImageDesc());
        self::assertNull($model->getCat());
        self::assertNull($model->getVisits());
        self::assertNull($model->getImageUrl());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GalleryImageModel();
        $model->setId(1);
        $model->setUserId(1);
        $model->setImageId('old');
        $model->setImageTitle('Old');
        $model->setImageUrl('old.jpg');

        $model->setId(2);
        $model->setUserId(2);
        $model->setImageId('new');
        $model->setImageTitle('New');
        $model->setImageUrl('new.jpg');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('new', $model->getImageId());
        self::assertSame('New', $model->getImageTitle());
        self::assertSame('new.jpg', $model->getImageUrl());
    }
}
