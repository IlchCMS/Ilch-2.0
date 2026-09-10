<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Comment\Models;

use PHPUnit\Framework\TestCase;
use Modules\Comment\Models\Comment as CommentModel;

class CommentModelTest extends TestCase
{
    /**
     * Tests that all properties default to null.
     */
    public function testDefaults()
    {
        $model = new CommentModel();

        self::assertNull($model->getId());
        self::assertNull($model->getFKId());
        self::assertNull($model->getKey());
        self::assertNull($model->getText());
        self::assertNull($model->getUserId());
        self::assertNull($model->getDateCreated());
        self::assertNull($model->getUp());
        self::assertNull($model->getDown());
        self::assertNull($model->getVoted());
    }

    /**
     * Tests that setters are chainable and return $this.
     */
    public function testSettersAreChainable()
    {
        $model = new CommentModel();
        $result = $model->setId(1)
            ->setFKId(0)
            ->setKey('news/1/')
            ->setText('Hello')
            ->setUserId(10)
            ->setDateCreated('2025-01-01 00:00:00')
            ->setUp(5)
            ->setDown(2)
            ->setVoted('10,11');

        self::assertSame($model, $result);
        self::assertEquals(1, $model->getId());
        self::assertEquals(0, $model->getFKId());
        self::assertEquals('news/1/', $model->getKey());
        self::assertEquals('Hello', $model->getText());
        self::assertEquals(10, $model->getUserId());
        self::assertEquals('2025-01-01 00:00:00', $model->getDateCreated());
        self::assertEquals(5, $model->getUp());
        self::assertEquals(2, $model->getDown());
        self::assertEquals('10,11', $model->getVoted());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new CommentModel();
        $model->setId('42');

        self::assertEquals(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setFKId() casts to int.
     */
    public function testSetFKIdCastsToInt()
    {
        $model = new CommentModel();
        $model->setFKId('7');

        self::assertEquals(7, $model->getFKId());
        self::assertIsInt($model->getFKId());
    }

    /**
     * Tests that setKey() casts to string.
     */
    public function testSetKeyCastsToString()
    {
        $model = new CommentModel();
        $model->setKey(123);

        self::assertEquals('123', $model->getKey());
        self::assertIsString($model->getKey());
    }

    /**
     * Tests that setText() casts to string.
     */
    public function testSetTextCastsToString()
    {
        $model = new CommentModel();
        $model->setText(456);

        self::assertEquals('456', $model->getText());
        self::assertIsString($model->getText());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new CommentModel();
        $model->setUserId('99');

        self::assertEquals(99, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setUp() casts to int.
     */
    public function testSetUpCastsToInt()
    {
        $model = new CommentModel();
        $model->setUp('10');

        self::assertEquals(10, $model->getUp());
        self::assertIsInt($model->getUp());
    }

    /**
     * Tests that setDown() casts to int.
     */
    public function testSetDownCastsToInt()
    {
        $model = new CommentModel();
        $model->setDown('3');

        self::assertEquals(3, $model->getDown());
        self::assertIsInt($model->getDown());
    }

    /**
     * Tests that setVoted() casts to string (even if an int is passed).
     */
    public function testSetVotedCastsToString()
    {
        $model = new CommentModel();
        $model->setVoted(12345);

        self::assertEquals('12345', $model->getVoted());
        self::assertIsString($model->getVoted());
    }

    /**
     * Tests that setVoted() preserves string content with commas.
     */
    public function testSetVotedStringContent()
    {
        $model = new CommentModel();
        $model->setVoted('10,11,12,13');

        self::assertEquals('10,11,12,13', $model->getVoted());
    }
}
