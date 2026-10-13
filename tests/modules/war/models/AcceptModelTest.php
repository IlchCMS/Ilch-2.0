<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\Accept as AcceptModel;

class AcceptModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new AcceptModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new AcceptModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId() rejects non-numeric strings.
     */
    public function testSetIdRejectsNonNumericString()
    {
        $this->expectException(\TypeError::class);

        (new AcceptModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new AcceptModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setWarId() sets and returns the warId.
     */
    public function testSetWarId()
    {
        $model = new AcceptModel();
        $model->setWarId(7);

        self::assertSame(7, $model->getWarId());
    }

    /**
     * Tests that setUserId() sets and returns the userId.
     */
    public function testSetUserId()
    {
        $model = new AcceptModel();
        $model->setUserId(42);

        self::assertSame(42, $model->getUserId());
    }

    /**
     * Tests that setAccept() sets and returns the accept flag.
     */
    public function testSetAccept()
    {
        $model = new AcceptModel();
        $model->setAccept(1);

        self::assertSame(1, $model->getAccept());
    }

    /**
     * Tests that setAccept(0) stores zero (falsy but valid).
     */
    public function testSetAcceptZero()
    {
        $model = new AcceptModel();
        $model->setAccept(0);

        self::assertSame(0, $model->getAccept());
    }

    /**
     * Tests that setComment() sets and returns the comment.
     */
    public function testSetComment()
    {
        $model = new AcceptModel();
        $model->setComment('Count me in!');

        self::assertSame('Count me in!', $model->getComment());
    }

    /**
     * Tests that setComment() casts to string.
     */
    public function testSetCommentCastsToString()
    {
        $model = new AcceptModel();
        $model->setComment(123);

        self::assertSame('123', $model->getComment());
        self::assertIsString($model->getComment());
    }

    /**
     * Tests that setDateCreated() sets and returns the date created.
     */
    public function testSetDateCreated()
    {
        $model = new AcceptModel();
        $model->setDateCreated('2024-01-15 12:30:00');

        self::assertSame('2024-01-15 12:30:00', $model->getDateCreated());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new AcceptModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setWarId(2));
        self::assertSame($model, $model->setUserId(3));
        self::assertSame($model, $model->setAccept(1));
        self::assertSame($model, $model->setComment('Test'));
        self::assertSame($model, $model->setDateCreated('2024-01-01 00:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new AcceptModel())
            ->setId(3)
            ->setWarId(7)
            ->setUserId(42)
            ->setAccept(1)
            ->setComment('Count me in')
            ->setDateCreated('2024-01-15 12:30:00');

        self::assertSame(3, $model->getId());
        self::assertSame(7, $model->getWarId());
        self::assertSame(42, $model->getUserId());
        self::assertSame(1, $model->getAccept());
        self::assertSame('Count me in', $model->getComment());
        self::assertSame('2024-01-15 12:30:00', $model->getDateCreated());
    }

    /**
     * Tests that default values are 0 for the int properties and empty string for comment/dateCreated.
     */
    public function testDefaultValues()
    {
        $model = new AcceptModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getWarId());
        self::assertSame(0, $model->getUserId());
        self::assertSame(0, $model->getAccept());
        self::assertSame('', $model->getComment());
        self::assertSame('', $model->getDateCreated());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new AcceptModel();
        $model->setId(1)->setWarId(1)->setUserId(1)->setAccept(1)->setComment('Old')->setDateCreated('2024-01-01 00:00:00');

        $model->setId(2)->setWarId(2)->setUserId(2)->setAccept(0)->setComment('New')->setDateCreated('2024-06-01 08:00:00');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(2, $model->getUserId());
        self::assertSame(0, $model->getAccept());
        self::assertSame('New', $model->getComment());
        self::assertSame('2024-06-01 08:00:00', $model->getDateCreated());
    }

    /**
     * Tests setByArray() with all keys.
     */
    public function testSetByArrayFull()
    {
        $model = new AcceptModel();
        $model->setByArray([
            'id' => 5,
            'war_id' => 2,
            'user_id' => 89,
            'accept' => 1,
            'comment' => 'I am in',
            'date_created' => '2024-06-01 08:00:00',
        ]);

        self::assertSame(5, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(89, $model->getUserId());
        self::assertSame(1, $model->getAccept());
        self::assertSame('I am in', $model->getComment());
        self::assertSame('2024-06-01 08:00:00', $model->getDateCreated());
    }

    /**
     * Tests that setByArray() casts numeric string values (as returned by the DB).
     */
    public function testSetByArrayCastsValues()
    {
        $model = (new AcceptModel())->setByArray([
            'id' => '5',
            'war_id' => '2',
            'user_id' => '89',
            'accept' => '1',
        ]);

        self::assertSame(5, $model->getId());
        self::assertSame(2, $model->getWarId());
        self::assertSame(89, $model->getUserId());
        self::assertSame(1, $model->getAccept());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new AcceptModel())->setByArray(['comment' => 'Late join']);

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getWarId());
        self::assertSame(0, $model->getUserId());
        self::assertSame(0, $model->getAccept());
        self::assertSame('Late join', $model->getComment());
        self::assertSame('', $model->getDateCreated());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new AcceptModel())->setByArray(['id' => 9, 'foo' => 'bar']);

        self::assertSame(9, $model->getId());
        self::assertSame(0, $model->getWarId());
        self::assertSame('', $model->getComment());
    }

    /**
     * Tests that getArray() returns id and all fields.
     */
    public function testGetArrayWithId()
    {
        $model = new AcceptModel();
        $model->setId(3)
            ->setWarId(7)
            ->setUserId(42)
            ->setAccept(1)
            ->setComment('Count me in')
            ->setDateCreated('2024-01-15 12:30:00');

        self::assertSame(
            [
                'id' => 3,
                'war_id' => 7,
                'user_id' => 42,
                'accept' => 1,
                'comment' => 'Count me in',
                'date_created' => '2024-01-15 12:30:00',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new AcceptModel();
        $model->setId(3)
            ->setWarId(7)
            ->setUserId(42)
            ->setAccept(0)
            ->setComment('Declined')
            ->setDateCreated('2024-01-15 12:30:00');

        self::assertSame(
            [
                'war_id' => 7,
                'user_id' => 42,
                'accept' => 0,
                'comment' => 'Declined',
                'date_created' => '2024-01-15 12:30:00',
            ],
            $model->getArray(false)
        );
    }

    /**
     * Tests that setByArray() and getArray() are inverse operations.
     */
    public function testSetByArrayGetArrayRoundTrip()
    {
        $source = [
            'id' => 5,
            'war_id' => 2,
            'user_id' => 89,
            'accept' => 0,
            'comment' => 'Declined',
            'date_created' => '2024-06-01 08:00:00',
        ];

        $model = (new AcceptModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
