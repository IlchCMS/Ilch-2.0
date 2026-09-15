<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Gallery\Models;

use PHPUnit\Framework\TestCase;
use Modules\Gallery\Models\Image as ImageModel;

class ImageModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ImageModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero()
    {
        $model = new ImageModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setImageId() sets and returns the image id.
     */
    public function testSetImageId()
    {
        $model = new ImageModel();
        $model->setImageId(100);

        self::assertSame(100, $model->getImageId());
    }

    /**
     * Tests that setImageTitle() sets and returns the title.
     */
    public function testSetImageTitle()
    {
        $model = new ImageModel();
        $model->setImageTitle('Sunset');

        self::assertSame('Sunset', $model->getImageTitle());
    }

    /**
     * Tests that setImageDesc() sets and returns the description.
     */
    public function testSetImageDesc()
    {
        $model = new ImageModel();
        $model->setImageDesc('Beautiful view');

        self::assertSame('Beautiful view', $model->getImageDesc());
    }

    /**
     * Tests that setGalleryId() sets and returns the gallery id.
     */
    public function testSetGalleryId()
    {
        $model = new ImageModel();
        $model->setGalleryId(2);

        self::assertSame(2, $model->getGalleryId());
    }

    /**
     * Tests that setVisits() sets and returns the visits.
     */
    public function testSetVisits()
    {
        $model = new ImageModel();
        $model->setVisits(42);

        self::assertSame(42, $model->getVisits());
    }

    /**
     * Tests that setVisits(0) stores zero.
     */
    public function testSetVisitsZero()
    {
        $model = new ImageModel();
        $model->setVisits(0);

        self::assertSame(0, $model->getVisits());
    }

    /**
     * Tests that setImageUrl() sets and returns the URL.
     */
    public function testSetImageUrl()
    {
        $model = new ImageModel();
        $model->setImageUrl('/media/test.jpg');

        self::assertSame('/media/test.jpg', $model->getImageUrl());
    }

    /**
     * Tests that setImageThumb() sets and returns the thumb.
     */
    public function testSetImageThumb()
    {
        $model = new ImageModel();
        $model->setImageThumb('/media/test_thumb.jpg');

        self::assertSame('/media/test_thumb.jpg', $model->getImageThumb());
    }

    /**
     * Tests that default values are 0 for int fields and empty string for string fields.
     */
    public function testDefaultValues()
    {
        $model = new ImageModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getImageId());
        self::assertSame('', $model->getImageThumb());
        self::assertSame('', $model->getImageTitle());
        self::assertSame('', $model->getImageDesc());
        self::assertSame(0, $model->getGalleryId());
        self::assertSame(0, $model->getVisits());
        self::assertSame('', $model->getImageUrl());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ImageModel();
        $model->setId(1);
        $model->setImageId(100);
        $model->setImageTitle('Old');
        $model->setImageDesc('Old desc');
        $model->setGalleryId(1);
        $model->setVisits(5);

        $model->setId(2);
        $model->setImageId(200);
        $model->setImageTitle('New');
        $model->setImageDesc('New desc');
        $model->setGalleryId(2);
        $model->setVisits(10);

        self::assertSame(2, $model->getId());
        self::assertSame(200, $model->getImageId());
        self::assertSame('New', $model->getImageTitle());
        self::assertSame('New desc', $model->getImageDesc());
        self::assertSame(2, $model->getGalleryId());
        self::assertSame(10, $model->getVisits());
    }

    /**
     * Tests that setByArray() populates fields from a database row.
     */
    public function testSetByArray()
    {
        $model = new ImageModel();
        $entries = [
            'imgid' => 1,
            'image_id' => 100,
            'url' => '/media/sunset.jpg',
            'url_thumb' => '/media/sunset_thumb.jpg',
            'image_title' => 'Sunset',
            'image_description' => 'Beautiful sunset',
            'gallery_id' => 1,
            'visits' => 5,
        ];

        $result = $model->setByArray($entries);

        self::assertSame($model, $result);
        self::assertSame(1, $model->getId());
        self::assertSame(100, $model->getImageId());
        self::assertSame('/media/sunset.jpg', $model->getImageUrl());
        self::assertSame('/media/sunset_thumb.jpg', $model->getImageThumb());
        self::assertSame('Sunset', $model->getImageTitle());
        self::assertSame('Beautiful sunset', $model->getImageDesc());
        self::assertSame(1, $model->getGalleryId());
        self::assertSame(5, $model->getVisits());
    }

    /**
     * Tests that setByArray() works with 'id' key as fallback for imgid.
     */
    public function testSetByArrayWithIdKey()
    {
        $model = new ImageModel();
        $entries = [
            'id' => 7,
            'image_id' => 50,
            'gallery_id' => 2,
        ];

        $model->setByArray($entries);

        self::assertSame(7, $model->getId());
        self::assertSame(50, $model->getImageId());
        self::assertSame(2, $model->getGalleryId());
    }

    /**
     * Tests that setByArray() ignores empty/null values.
     */
    public function testSetByArrayIgnoresEmpty()
    {
        $model = new ImageModel();
        $entries = [
            'imgid' => 1,
            'image_id' => 0,
            'url' => '',
            'url_thumb' => '',
            'image_title' => '',
            'image_description' => '',
            'gallery_id' => 0,
            'visits' => 0,
        ];

        $model->setByArray($entries);

        self::assertSame(1, $model->getId());
        self::assertSame(0, $model->getImageId());
        self::assertSame('', $model->getImageUrl());
        self::assertSame(0, $model->getGalleryId());
        self::assertSame(0, $model->getVisits());
    }

    /**
     * Tests that getArray() includes all fields with id by default.
     */
    public function testGetArrayWithId()
    {
        $model = new ImageModel();
        $model->setId(1);
        $model->setImageId(100);
        $model->setImageTitle('Title');
        $model->setImageDesc('Desc');
        $model->setGalleryId(2);
        $model->setVisits(7);

        $array = $model->getArray();

        self::assertSame(1, $array['id']);
        self::assertSame(100, $array['image_id']);
        self::assertSame('Title', $array['image_title']);
        self::assertSame('Desc', $array['image_description']);
        self::assertSame(2, $array['gallery_id']);
        self::assertSame(7, $array['visits']);
    }

    /**
     * Tests that getArray(false) excludes the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new ImageModel();
        $model->setId(9);
        $model->setImageId(100);
        $model->setImageTitle('T');
        $model->setImageDesc('D');
        $model->setGalleryId(1);
        $model->setVisits(3);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(100, $array['image_id']);
        self::assertSame('T', $array['image_title']);
        self::assertSame('D', $array['image_description']);
        self::assertSame(1, $array['gallery_id']);
        self::assertSame(3, $array['visits']);
    }
}
