<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\Media as MediaModel;

class MediaModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new MediaModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new MediaModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new MediaModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new MediaModel();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new MediaModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setUrl() sets and returns the url.
     */
    public function testSetUrl()
    {
        $model = new MediaModel();
        $model->setUrl('media/abc123.jpg');

        self::assertSame('media/abc123.jpg', $model->getUrl());
    }

    /**
     * Tests that setUrl() casts to string.
     */
    public function testSetUrlCastsToString()
    {
        $model = new MediaModel();
        $model->setUrl(456);

        self::assertSame('456', $model->getUrl());
        self::assertIsString($model->getUrl());
    }

    /**
     * Tests that setUrlThumb() sets and returns the url thumb.
     */
    public function testSetUrlThumb()
    {
        $model = new MediaModel();
        $model->setUrlThumb('media/thumb_abc123.jpg');

        self::assertSame('media/thumb_abc123.jpg', $model->getUrlThumb());
    }

    /**
     * Tests that setUrlThumb() casts to string.
     */
    public function testSetUrlThumbCastsToString()
    {
        $model = new MediaModel();
        $model->setUrlThumb(456);

        self::assertSame('456', $model->getUrlThumb());
        self::assertIsString($model->getUrlThumb());
    }

    /**
     * Tests that setEnding() sets and returns the file ending.
     */
    public function testSetEnding()
    {
        $model = new MediaModel();
        $model->setEnding('jpg');

        self::assertSame('jpg', $model->getEnding());
    }

    /**
     * Tests that setEnding() casts to string.
     */
    public function testSetEndingCastsToString()
    {
        $model = new MediaModel();
        $model->setEnding(123);

        self::assertSame('123', $model->getEnding());
        self::assertIsString($model->getEnding());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new MediaModel();
        $model->setName('My Media');

        self::assertSame('My Media', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new MediaModel();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setDatetime() sets and returns the datetime.
     */
    public function testSetDatetime()
    {
        $model = new MediaModel();
        $model->setDatetime('2024-01-01 12:00:00');

        self::assertSame('2024-01-01 12:00:00', $model->getDatetime());
    }

    /**
     * Tests that setDatetime() casts to string.
     */
    public function testSetDatetimeCastsToString()
    {
        $model = new MediaModel();
        $model->setDatetime(20240101);

        self::assertSame('20240101', $model->getDatetime());
        self::assertIsString($model->getDatetime());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new MediaModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setUrl('image.jpg'));
        self::assertSame($model, $model->setUrlThumb('thumb.jpg'));
        self::assertSame($model, $model->setEnding('jpg'));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setDatetime('2024-01-01 12:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new MediaModel())
            ->setId(1)
            ->setUserId(2)
            ->setUrl('media/abc123.jpg')
            ->setUrlThumb('media/thumb_abc123.jpg')
            ->setEnding('jpg')
            ->setName('My Media')
            ->setDatetime('2024-01-01 12:00:00');

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('media/abc123.jpg', $model->getUrl());
        self::assertSame('media/thumb_abc123.jpg', $model->getUrlThumb());
        self::assertSame('jpg', $model->getEnding());
        self::assertSame('My Media', $model->getName());
        self::assertSame('2024-01-01 12:00:00', $model->getDatetime());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new MediaModel();

        self::assertNull($model->getId());
        self::assertNull($model->getUserId());
        self::assertNull($model->getUrl());
        self::assertNull($model->getUrlThumb());
        self::assertNull($model->getEnding());
        self::assertNull($model->getName());
        self::assertNull($model->getDatetime());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new MediaModel();
        $model->setId(1);
        $model->setUserId(1);
        $model->setUrl('old.jpg');
        $model->setUrlThumb('old_thumb.jpg');
        $model->setEnding('jpg');
        $model->setName('Old');
        $model->setDatetime('2024-01-01 00:00:00');

        $model->setId(2);
        $model->setUserId(2);
        $model->setUrl('new.jpg');
        $model->setUrlThumb('new_thumb.jpg');
        $model->setEnding('png');
        $model->setName('New');
        $model->setDatetime('2024-02-02 00:00:00');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('new.jpg', $model->getUrl());
        self::assertSame('new_thumb.jpg', $model->getUrlThumb());
        self::assertSame('png', $model->getEnding());
        self::assertSame('New', $model->getName());
        self::assertSame('2024-02-02 00:00:00', $model->getDatetime());
    }
}
