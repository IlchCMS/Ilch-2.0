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
        self::assertSame($model, $model->setIsEnabled(true));
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
        self::assertTrue($model->isEnabled());
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
     * Tests that getArray() returns all fields (id, url, enabled).
     */
    public function testGetArray()
    {
        $model = (new Events())->setId(1)->setUrl('calendar/events/index/');

        self::assertSame(
            [
                'id' => 1,
                'url' => 'calendar/events/index/',
                'enabled' => 1,
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
                'enabled' => 1,
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
        self::assertSame(1, $array['enabled']);
    }

    /**
     * Tests that getArray() reflects a disabled enabled flag.
     */
    public function testGetArrayWithDisabledFlag()
    {
        $model = (new Events())->setId(1)->setUrl('calendar/events/index/')->setIsEnabled(false);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame('calendar/events/index/', $array['url']);
        self::assertSame(0, $array['enabled']);
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

    /**
     * Tests that the default enabled state is true.
     */
    public function testIsEnabledDefault()
    {
        $model = new Events();

        self::assertTrue($model->isEnabled());
    }

    /**
     * Tests that setIsEnabled() sets the value and returns $this.
     */
    public function testSetIsEnabled()
    {
        $model = new Events();

        self::assertSame($model, $model->setIsEnabled(false));
        self::assertFalse($model->isEnabled());

        self::assertSame($model, $model->setIsEnabled(true));
        self::assertTrue($model->isEnabled());
    }

    /**
     * Tests that setIsEnabled() casts to bool.
     */
    public function testSetIsEnabledCastsToBool()
    {
        $model = new Events();

        $model->setIsEnabled(1);
        self::assertTrue($model->isEnabled());
        self::assertIsBool($model->isEnabled());

        $model->setIsEnabled(0);
        self::assertFalse($model->isEnabled());
    }

    /**
     * Tests that chaining setters including setIsEnabled builds a complete model.
     */
    public function testChainedSettersWithIsEnabled()
    {
        $model = (new Events())
            ->setId(2)
            ->setUrl('war/wars/index/')
            ->setIsEnabled(false);

        self::assertSame(2, $model->getId());
        self::assertSame('war/wars/index/', $model->getUrl());
        self::assertFalse($model->isEnabled());
    }

    /**
     * Tests that setByArray() sets the enabled flag from the array.
     */
    public function testSetByArrayWithEnabled()
    {
        $model = (new Events())->setByArray(
            [
                'id' => 3,
                'url' => 'training/trainings/index/',
                'enabled' => 0,
            ]
        );

        self::assertSame(3, $model->getId());
        self::assertSame('training/trainings/index/', $model->getUrl());
        self::assertFalse($model->isEnabled());
    }
}
