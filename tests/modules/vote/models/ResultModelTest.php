<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Vote\Models;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the Result model of the Vote module
 *
 * @package ilch_phpunit
 */
class ResultModelTest extends TestCase
{
    /**
     * Test the default values of a new model
     *
     * @return void
     */
    public function testDefaultValues()
    {
        $model = new Result();

        self::assertSame(0, $model->getPollId());
        self::assertSame('', $model->getReply());
        self::assertSame(0, $model->getResult());
    }

    /**
     * Test that setPollId() stores the given value
     *
     * @return void
     */
    public function testSetPollId()
    {
        $model = new Result();
        $model->setPollId(5);

        self::assertSame(5, $model->getPollId());
    }

    /**
     * Test that setPollId() casts the given value to int
     *
     * @return void
     */
    public function testSetPollIdCastsToInt()
    {
        $model = new Result();
        $model->setPollId('42');

        self::assertSame(42, $model->getPollId());
        self::assertIsInt($model->getPollId());
    }

    /**
     * Test that setReply() stores the given value
     *
     * @return void
     */
    public function testSetReply()
    {
        $model = new Result();
        $model->setReply('Antwort 1');

        self::assertSame('Antwort 1', $model->getReply());
    }

    /**
     * Test that setReply() casts the given value to string
     *
     * @return void
     */
    public function testSetReplyCastsToString()
    {
        $model = new Result();
        $model->setReply(123);

        self::assertSame('123', $model->getReply());
        self::assertIsString($model->getReply());
    }

    /**
     * Test that setResult() stores the given value
     *
     * @return void
     */
    public function testSetResult()
    {
        $model = new Result();
        $model->setResult(10);

        self::assertSame(10, $model->getResult());
    }

    /**
     * Test that setResult() casts the given value to int
     *
     * @return void
     */
    public function testSetResultCastsToInt()
    {
        $model = new Result();
        $model->setResult('42');

        self::assertSame(42, $model->getResult());
        self::assertIsInt($model->getResult());
    }

    /**
     * Test that all setters return the model itself for chaining
     *
     * @return void
     */
    public function testSettersReturnSelf()
    {
        $model = new Result();

        self::assertSame($model, $model->setPollId(1));
        self::assertSame($model, $model->setReply('Antwort'));
        self::assertSame($model, $model->setResult(2));
    }

    /**
     * Test that the setters can be chained
     *
     * @return void
     */
    public function testChainedSetters()
    {
        $model = (new Result())
            ->setPollId(1)
            ->setReply('Antwort 1.1')
            ->setResult(5);

        self::assertSame(1, $model->getPollId());
        self::assertSame('Antwort 1.1', $model->getReply());
        self::assertSame(5, $model->getResult());
    }

    /**
     * Test that setByArray() sets all supported fields
     *
     * @return void
     */
    public function testSetByArray()
    {
        $model = new Result();
        $result = $model->setByArray([
            'poll_id' => 1,
            'reply'   => 'Antwort 1.1',
            'result'  => 4,
        ]);

        self::assertSame($model, $result);
        self::assertSame(1, $model->getPollId());
        self::assertSame('Antwort 1.1', $model->getReply());
        self::assertSame(4, $model->getResult());
    }

    /**
     * Test that setByArray() only sets the given fields and keeps the defaults
     *
     * @return void
     */
    public function testSetByArrayPartial()
    {
        $model = new Result();
        $model->setByArray(['reply' => 'Antwort']);

        self::assertSame(0, $model->getPollId());
        self::assertSame('Antwort', $model->getReply());
        self::assertSame(0, $model->getResult());
    }

    /**
     * Test that getArray() returns all fields as an array
     *
     * @return void
     */
    public function testGetArray()
    {
        $model = new Result();
        $model->setPollId(1)->setReply('Antwort')->setResult(3);

        self::assertSame([
            'poll_id' => 1,
            'reply'   => 'Antwort',
            'result'  => 3,
        ], $model->getArray());
    }

    /**
     * Test that values can be overwritten by calling the setters again
     *
     * @return void
     */
    public function testOverwriteValues()
    {
        $model = new Result();
        $model->setPollId(1)->setReply('Old')->setResult(1);

        $model->setPollId(2)->setReply('New')->setResult(2);

        self::assertSame(2, $model->getPollId());
        self::assertSame('New', $model->getReply());
        self::assertSame(2, $model->getResult());
    }

    /**
     * Test that getPercent() calculates the percentage of a given total count
     *
     * @return void
     */
    public function testGetPercent()
    {
        $model = new Result();
        $model->setResult(25);

        self::assertEquals(25.0, $model->getPercent(100));
    }

    /**
     * Test that getPercent() rounds to one decimal place
     *
     * @return void
     */
    public function testGetPercentRounding()
    {
        $model = new Result();
        $model->setResult(1);

        self::assertEquals(33.0, $model->getPercent(3));
        self::assertEquals(50.0, $model->getPercent(2));
        self::assertEquals(25.0, $model->getPercent(4));

        $model->setResult(3);

        self::assertEquals(75.0, $model->getPercent(4));
    }

    /**
     * Test that getPercent() rounds halves up
     *
     * @return void
     */
    public function testGetPercentRoundHalfUp()
    {
        $model = new Result();
        $model->setResult(1);

        // 12.5 rounds up to 13
        self::assertEquals(13.0, $model->getPercent(8));
    }

    /**
     * Test that getPercent() returns 0 for a zero result
     *
     * @return void
     */
    public function testGetPercentWithZeroResult()
    {
        $model = new Result();
        $model->setResult(0);

        self::assertEquals(0, $model->getPercent(10));
    }

    /**
     * Test that getPercent() returns 0 for a zero total count instead of failing
     *
     * @return void
     */
    public function testGetPercentWithZeroTotalCount()
    {
        $model = new Result();
        $model->setResult(5);

        self::assertEquals(0, $model->getPercent(0));
    }
}
