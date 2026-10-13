<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\Maps as MapsModel;

class MapsModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new MapsModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new MapsModel();
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

        (new MapsModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new MapsModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new MapsModel();
        $model->setName('de_dust2');

        self::assertSame('de_dust2', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new MapsModel();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new MapsModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setName('Test'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new MapsModel())
            ->setId(3)
            ->setName('de_inferno');

        self::assertSame(3, $model->getId());
        self::assertSame('de_inferno', $model->getName());
    }

    /**
     * Tests that default values are 0 for id and empty string for name.
     */
    public function testDefaultValues()
    {
        $model = new MapsModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getName());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new MapsModel();
        $model->setId(1)->setName('old_map');

        $model->setId(2)->setName('new_map');

        self::assertSame(2, $model->getId());
        self::assertSame('new_map', $model->getName());
    }

    /**
     * Tests setByArray() with all keys.
     */
    public function testSetByArrayFull()
    {
        $model = new MapsModel();
        $model->setByArray([
            'id' => 4,
            'name' => 'de_mirage',
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame('de_mirage', $model->getName());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new MapsModel())->setByArray(['name' => 'de_nuke']);

        self::assertSame(0, $model->getId());
        self::assertSame('de_nuke', $model->getName());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new MapsModel())->setByArray(['id' => 9, 'foo' => 'bar']);

        self::assertSame(9, $model->getId());
        self::assertSame('', $model->getName());
    }

    /**
     * Tests that getArray() returns id and name.
     */
    public function testGetArrayWithId()
    {
        $model = new MapsModel();
        $model->setId(3)->setName('de_inferno');

        self::assertSame(
            [
                'id' => 3,
                'name' => 'de_inferno',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new MapsModel();
        $model->setName('de_inferno');

        self::assertSame(
            [
                'name' => 'de_inferno',
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
            'id' => 4,
            'name' => 'de_mirage',
        ];

        $model = (new MapsModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
