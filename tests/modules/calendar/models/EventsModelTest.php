<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Calendar\Models;

use PHPUnit\Framework\TestCase;

class EventsModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Events();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Events();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new Events();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUrl() sets and returns the url.
     */
    public function testSetUrl()
    {
        $model = new Events();
        $model->setUrl('calendar/events/index/');

        self::assertSame('calendar/events/index/', $model->getUrl());
    }

    /**
     * Tests that setUrl() casts to string.
     */
    public function testSetUrlCastsToString()
    {
        $model = new Events();
        $model->setUrl(456);

        self::assertSame('456', $model->getUrl());
        self::assertIsString($model->getUrl());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Events();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUrl('calendar/events/index/'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Events())
            ->setId(2)
            ->setUrl('war/wars/index/');

        self::assertSame(2, $model->getId());
        self::assertSame('war/wars/index/', $model->getUrl());
    }

    /**
     * Tests that default values are 0 for id and empty string for url.
     */
    public function testDefaultValues()
    {
        $model = new Events();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getUrl());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = (new Events())->setId(1)->setUrl('calendar/events/index/');

        $model->setId(2)->setUrl('training/trainings/index/');

        self::assertSame(2, $model->getId());
        self::assertSame('training/trainings/index/', $model->getUrl());
    }

    /**
     * Tests that getArray() returns all fields including the id.
     */
    public function testGetArray()
    {
        $model = (new Events())->setId(1)->setUrl('calendar/events/index/');

        self::assertSame(
            [
                'id' => 1,
                'url' => 'calendar/events/index/',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray() returns default fields for a fresh model.
     */
    public function testGetArrayDefaults()
    {
        $model = new Events();

        self::assertSame(
            [
                'id' => 0,
                'url' => '',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new Events())->setId(1)->setUrl('away/aways/index/');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame('away/aways/index/', $array['url']);
    }

    /**
     * Tests that setByArray() sets all fields from a complete array.
     */
    public function testSetByArray()
    {
        $model = (new Events())->setByArray(
            [
                'id' => 3,
                'url' => 'training/trainings/index/',
            ]
        );

        self::assertSame(3, $model->getId());
        self::assertSame('training/trainings/index/', $model->getUrl());
    }

    /**
     * Tests that setByArray() only changes provided keys and keeps defaults.
     */
    public function testSetByArrayPartialKeepsDefaults()
    {
        $model = (new Events())->setByArray(['url' => 'birthday/birthdays/index/']);

        self::assertSame(0, $model->getId());
        self::assertSame('birthday/birthdays/index/', $model->getUrl());
    }

    /**
     * Tests that setByArray() overwrites previously set values.
     */
    public function testSetByArrayOverwrite()
    {
        $model = (new Events())->setId(1)->setUrl('calendar/events/index/');

        $model->setByArray(['url' => 'events/events/index/']);

        self::assertSame(1, $model->getId());
        self::assertSame('events/events/index/', $model->getUrl());
    }
}
