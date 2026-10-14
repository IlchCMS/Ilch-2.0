<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Vote\Models;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the Ip model of the Vote module
 *
 * @package ilch_phpunit
 */
class IpModelTest extends TestCase
{
    /**
     * Test the default values of a new model
     *
     * @return void
     */
    public function testDefaultValues()
    {
        $model = new Ip();

        self::assertSame(0, $model->getPollId());
        self::assertSame('', $model->getIP());
        self::assertSame(0, $model->getUserId());
    }

    /**
     * Test that setPollId() stores the given value
     *
     * @return void
     */
    public function testSetPollId()
    {
        $model = new Ip();
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
        $model = new Ip();
        $model->setPollId('42');

        self::assertSame(42, $model->getPollId());
        self::assertIsInt($model->getPollId());
    }

    /**
     * Test that setIP() stores the given value
     *
     * @return void
     */
    public function testSetIP()
    {
        $model = new Ip();
        $model->setIP('192.168.10.1');

        self::assertSame('192.168.10.1', $model->getIP());
    }

    /**
     * Test that setIP() casts the given value to string
     *
     * @return void
     */
    public function testSetIpcastsToString()
    {
        $model = new Ip();
        $model->setIP(456);

        self::assertSame('456', $model->getIP());
        self::assertIsString($model->getIP());
    }

    /**
     * Test that setUserId() stores the given value
     *
     * @return void
     */
    public function testSetUserId()
    {
        $model = new Ip();
        $model->setUserId(2);

        self::assertSame(2, $model->getUserId());
    }

    /**
     * Test that setUserId() casts the given value to int
     *
     * @return void
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new Ip();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Test that all setters return the model itself for chaining
     *
     * @return void
     */
    public function testSettersReturnSelf()
    {
        $model = new Ip();

        self::assertSame($model, $model->setPollId(1));
        self::assertSame($model, $model->setIP('127.0.0.1'));
        self::assertSame($model, $model->setUserId(3));
    }

    /**
     * Test that the setters can be chained
     *
     * @return void
     */
    public function testChainedSetters()
    {
        $model = (new Ip())
            ->setPollId(1)
            ->setIP('127.0.0.1')
            ->setUserId(2);

        self::assertSame(1, $model->getPollId());
        self::assertSame('127.0.0.1', $model->getIP());
        self::assertSame(2, $model->getUserId());
    }

    /**
     * Test that setByArray() sets all supported fields
     *
     * @return void
     */
    public function testSetByArray()
    {
        $model = new Ip();
        $result = $model->setByArray([
            'poll_id' => 1,
            'ip'      => '192.168.10.10',
            'user_id' => 2,
        ]);

        self::assertSame($model, $result);
        self::assertSame(1, $model->getPollId());
        self::assertSame('192.168.10.10', $model->getIP());
        self::assertSame(2, $model->getUserId());
    }

    /**
     * Test that setByArray() only sets the given fields and keeps the defaults
     *
     * @return void
     */
    public function testSetByArrayPartial()
    {
        $model = new Ip();
        $model->setByArray(['ip' => '10.0.0.1']);

        self::assertSame(0, $model->getPollId());
        self::assertSame('10.0.0.1', $model->getIP());
        self::assertSame(0, $model->getUserId());
    }

    /**
     * Test that setByArray() ignores unknown keys
     *
     * @return void
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = new Ip();
        $model->setByArray(['unknown' => 'x', 'ip' => '10.0.0.2']);

        self::assertSame('10.0.0.2', $model->getIP());
    }

    /**
     * Test that getArray() returns all fields as an array
     *
     * @return void
     */
    public function testGetArray()
    {
        $model = new Ip();
        $model->setPollId(1)->setIP('127.0.0.1')->setUserId(2);

        self::assertSame([
            'poll_id' => 1,
            'ip'      => '127.0.0.1',
            'user_id' => 2,
        ], $model->getArray());
    }

    /**
     * Test that values can be overwritten by calling the setters again
     *
     * @return void
     */
    public function testOverwriteValues()
    {
        $model = new Ip();
        $model->setPollId(1)->setIP('1.1.1.1')->setUserId(1);

        $model->setPollId(2)->setIP('2.2.2.2')->setUserId(2);

        self::assertSame(2, $model->getPollId());
        self::assertSame('2.2.2.2', $model->getIP());
        self::assertSame(2, $model->getUserId());
    }
}
