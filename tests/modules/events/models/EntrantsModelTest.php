<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Events\Tests;

use PHPUnit\Framework\TestCase;
use Modules\Events\Models\Entrants;

class EntrantsModelTest extends TestCase
{
    /**
     * Tests that default property values are correct.
     */
    public function testDefaults()
    {
        $model = new Entrants();

        self::assertEquals(0, $model->getEventId());
        self::assertEquals(0, $model->getUserId());
        self::assertEquals(0, $model->getStatus());
    }

    /**
     * Tests that setEventId() stores the value and returns $this.
     */
    public function testSetEventId()
    {
        $model = new Entrants();
        $result = $model->setEventId(5);

        self::assertSame($model, $result);
        self::assertEquals(5, $model->getEventId());
    }

    /**
     * Tests that setUserId() stores the value and returns $this.
     */
    public function testSetUserId()
    {
        $model = new Entrants();
        $result = $model->setUserId(7);

        self::assertSame($model, $result);
        self::assertEquals(7, $model->getUserId());
    }

    /**
     * Tests that setStatus() stores the value and returns $this.
     */
    public function testSetStatus()
    {
        $model = new Entrants();
        $result = $model->setStatus(1);

        self::assertSame($model, $result);
        self::assertEquals(1, $model->getStatus());
    }

    /**
     * Tests that setStatus() accepts status value 2 (maybe).
     */
    public function testSetStatusMaybe()
    {
        $model = new Entrants();
        $result = $model->setStatus(2);

        self::assertSame($model, $result);
        self::assertEquals(2, $model->getStatus());
    }

    /**
     * Tests that setters are chainable.
     */
    public function testSettersAreChainable()
    {
        $model = new Entrants();
        $result = $model->setEventId(3)->setUserId(4)->setStatus(1);

        self::assertSame($model, $result);
        self::assertEquals(3, $model->getEventId());
        self::assertEquals(4, $model->getUserId());
        self::assertEquals(1, $model->getStatus());
    }

    /**
     * Tests that setByArray() populates all fields from a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new Entrants();
        $result = $model->setByArray([
            'event_id' => 1,
            'user_id'  => 2,
            'status'   => 1,
        ]);

        self::assertSame($model, $result);
        self::assertEquals(1, $model->getEventId());
        self::assertEquals(2, $model->getUserId());
        self::assertEquals(1, $model->getStatus());
    }

    /**
     * Tests that setByArray() only updates keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new Entrants();
        $model->setEventId(99);
        $model->setUserId(88);
        $model->setStatus(2);

        $model->setByArray([
            'status' => 1,
        ]);

        self::assertEquals(99, $model->getEventId());
        self::assertEquals(88, $model->getUserId());
        self::assertEquals(1, $model->getStatus());
    }

    /**
     * Tests that setByArray() with an empty array leaves defaults intact.
     */
    public function testSetByArrayEmpty()
    {
        $model = new Entrants();
        $model->setByArray([]);

        self::assertEquals(0, $model->getEventId());
        self::assertEquals(0, $model->getUserId());
        self::assertEquals(0, $model->getStatus());
    }

    /**
     * Tests that getArray() returns all fields.
     */
    public function testGetArray()
    {
        $model = new Entrants();
        $model->setEventId(1);
        $model->setUserId(2);
        $model->setStatus(1);

        $array = $model->getArray();

        self::assertEquals([
            'event_id' => 1,
            'user_id'  => 2,
            'status'   => 1,
        ], $array);
    }

    /**
     * Tests that getArray() with defaults returns zeros.
     */
    public function testGetArrayDefaults()
    {
        $model = new Entrants();

        self::assertEquals([
            'event_id' => 0,
            'user_id'  => 0,
            'status'   => 0,
        ], $model->getArray());
    }

    /**
     * Tests a full round-trip: set → getArray → setByArray → getArray.
     */
    public function testFullRoundTrip()
    {
        $model = new Entrants();
        $model->setEventId(10);
        $model->setUserId(20);
        $model->setStatus(2);

        $array = $model->getArray();

        $model2 = new Entrants();
        $model2->setByArray($array);

        self::assertEquals(10, $model2->getEventId());
        self::assertEquals(20, $model2->getUserId());
        self::assertEquals(2, $model2->getStatus());
        self::assertEquals($model->getArray(), $model2->getArray());
    }
}
