<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\Notification as NotificationModel;

/**
 * Tests the Notification model class.
 *
 * @package ilch_phpunit
 */
class NotificationModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new NotificationModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new NotificationModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setTimestamp() sets and returns the timestamp.
     */
    public function testSetTimestamp()
    {
        $model = new NotificationModel();
        $model->setTimestamp('2014-01-01 12:12:12');

        self::assertSame('2014-01-01 12:12:12', $model->getTimestamp());
    }

    /**
     * Tests that setModule() sets and returns the module.
     */
    public function testSetModule()
    {
        $model = new NotificationModel();
        $model->setModule('article');

        self::assertSame('article', $model->getModule());
    }

    /**
     * Tests that setMessage() sets and returns the message.
     */
    public function testSetMessage()
    {
        $model = new NotificationModel();
        $model->setMessage('Testmessage1');

        self::assertSame('Testmessage1', $model->getMessage());
    }

    /**
     * Tests that setURL() sets and returns the url.
     */
    public function testSetURL()
    {
        $model = new NotificationModel();
        $model->setURL('https://www.google.de');

        self::assertSame('https://www.google.de', $model->getURL());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new NotificationModel();
        $model->setType('articleNewArticle');

        self::assertSame('articleNewArticle', $model->getType());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new NotificationModel();

        self::assertNull($model->getId());
        self::assertNull($model->getTimestamp());
        self::assertNull($model->getModule());
        self::assertNull($model->getMessage());
        self::assertNull($model->getURL());
        self::assertNull($model->getType());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new NotificationModel();
        $model->setId(1);
        $model->setModule('article');
        $model->setMessage('OldMessage');
        $model->setURL('https://www.old.de');
        $model->setType('oldType');

        $model->setId(2);
        $model->setModule('awards');
        $model->setMessage('NewMessage');
        $model->setURL('https://www.new.de');
        $model->setType('newType');

        self::assertSame(2, $model->getId());
        self::assertSame('awards', $model->getModule());
        self::assertSame('NewMessage', $model->getMessage());
        self::assertSame('https://www.new.de', $model->getURL());
        self::assertSame('newType', $model->getType());
    }
}
