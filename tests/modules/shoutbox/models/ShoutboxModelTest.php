<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shoutbox\Models;

use PHPUnit\Framework\TestCase;
use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;

class ShoutboxModelTest extends TestCase
{
    /**
     * Tests that default values are 0 for id/uid and empty string for name/textarea/time.
     */
    public function testDefaultValues()
    {
        $model = new ShoutboxModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getUid());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getTextarea());
        self::assertSame('', $model->getTime());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ShoutboxModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ShoutboxModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUid() sets and returns the uid.
     */
    public function testSetUid()
    {
        $model = new ShoutboxModel();
        $model->setUid(3);

        self::assertSame(3, $model->getUid());
    }

    /**
     * Tests that setUid(0) represents a guest.
     */
    public function testSetUidZero()
    {
        $model = new ShoutboxModel();
        $model->setUid(0);

        self::assertSame(0, $model->getUid());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new ShoutboxModel();
        $model->setName('Alice');

        self::assertSame('Alice', $model->getName());
    }

    /**
     * Tests that setTextarea() sets and returns the textarea.
     */
    public function testSetTextarea()
    {
        $model = new ShoutboxModel();
        $model->setTextarea('Hello world!');

        self::assertSame('Hello world!', $model->getTextarea());
    }

    /**
     * Tests that setTextarea() handles HTML content.
     */
    public function testSetTextareaHtml()
    {
        $model = new ShoutboxModel();
        $model->setTextarea('<p>Some <b>bold</b> text.</p>');

        self::assertSame('<p>Some <b>bold</b> text.</p>', $model->getTextarea());
    }

    /**
     * Tests that setTime() sets and returns the time.
     */
    public function testSetTime()
    {
        $model = new ShoutboxModel();
        $model->setTime('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getTime());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ShoutboxModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUid(2));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setTextarea('Text'));
        self::assertSame($model, $model->setTime('2024-01-01 00:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ShoutboxModel())
            ->setId(2)
            ->setUid(2)
            ->setName('Bob')
            ->setTextarea('Nice shoutbox!')
            ->setTime('2024-02-20 14:45:00');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getUid());
        self::assertSame('Bob', $model->getName());
        self::assertSame('Nice shoutbox!', $model->getTextarea());
        self::assertSame('2024-02-20 14:45:00', $model->getTime());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ShoutboxModel();
        $model->setId(1)
            ->setUid(1)
            ->setName('Old')
            ->setTextarea('Old text')
            ->setTime('2024-01-01 00:00:00');

        $model->setId(2)
            ->setUid(2)
            ->setName('New')
            ->setTextarea('New text')
            ->setTime('2024-02-01 00:00:00');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getUid());
        self::assertSame('New', $model->getName());
        self::assertSame('New text', $model->getTextarea());
        self::assertSame('2024-02-01 00:00:00', $model->getTime());
    }

    /**
     * Tests that setByArray() populates all fields.
     */
    public function testSetByArrayFull()
    {
        $model = new ShoutboxModel();
        $model->setByArray([
            'id'       => 1,
            'user_id'  => 1,
            'name'     => 'Alice',
            'textarea' => 'Hello world!',
            'time'     => '2024-01-15 10:30:00',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame(1, $model->getUid());
        self::assertSame('Alice', $model->getName());
        self::assertSame('Hello world!', $model->getTextarea());
        self::assertSame('2024-01-15 10:30:00', $model->getTime());
    }

    /**
     * Tests that setByArray() only sets fields that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new ShoutboxModel();
        $model->setByArray([
            'id'     => 5,
            'name'   => 'Test',
        ]);

        self::assertSame(5, $model->getId());
        self::assertSame('Test', $model->getName());
        self::assertSame(0, $model->getUid());
        self::assertSame('', $model->getTextarea());
        self::assertSame('', $model->getTime());
    }

    /**
     * Tests that setByArray() maps user_id to uid.
     */
    public function testSetByArrayMapsUserIdToUid()
    {
        $model = new ShoutboxModel();
        $model->setByArray([
            'user_id' => 42,
        ]);

        self::assertSame(42, $model->getUid());
    }

    /**
     * Tests that setByArray() returns $this.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new ShoutboxModel();
        $result = $model->setByArray(['id' => 1]);

        self::assertSame($model, $result);
    }

    /**
     * Tests that getArray() includes all fields with id.
     */
    public function testGetArrayWithId()
    {
        $model = (new ShoutboxModel())
            ->setId(3)
            ->setUid(2)
            ->setName('Bob')
            ->setTextarea('Nice shoutbox!')
            ->setTime('2024-02-20 14:45:00');

        $array = $model->getArray();

        self::assertSame(3, $array['id']);
        self::assertSame(2, $array['user_id']);
        self::assertSame('Bob', $array['name']);
        self::assertSame('Nice shoutbox!', $array['textarea']);
        self::assertSame('2024-02-20 14:45:00', $array['time']);
    }

    /**
     * Tests that getArray() without id excludes the id field.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new ShoutboxModel())
            ->setId(3)
            ->setUid(2)
            ->setName('Bob')
            ->setTextarea('Nice shoutbox!')
            ->setTime('2024-02-20 14:45:00');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(2, $array['user_id']);
        self::assertSame('Bob', $array['name']);
        self::assertSame('Nice shoutbox!', $array['textarea']);
        self::assertSame('2024-02-20 14:45:00', $array['time']);
    }

    /**
     * Tests that getArray() maps uid to user_id key.
     */
    public function testGetArrayMapsUidToUserId()
    {
        $model = new ShoutboxModel();
        $model->setUid(7);

        $array = $model->getArray(false);

        self::assertSame(7, $array['user_id']);
        self::assertArrayNotHasKey('uid', $array);
    }
}
