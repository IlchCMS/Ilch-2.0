<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Imprint\Models;

use PHPUnit\Framework\TestCase;
use Modules\Imprint\Models\Imprint as ImprintModel;

class ImprintModelTest extends TestCase
{
    /**
     * Tests that default property values are id=0 and imprint=''.
     */
    public function testDefaults()
    {
        $model = new ImprintModel();

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getImprint());
    }

    /**
     * Tests that setId() stores the value and returns $this.
     */
    public function testSetId()
    {
        $model = new ImprintModel();
        $result = $model->setId(5);

        self::assertSame($model, $result);
        self::assertEquals(5, $model->getId());
    }

    /**
     * Tests that setImprint() stores the value and returns $this.
     */
    public function testSetImprint()
    {
        $model = new ImprintModel();
        $result = $model->setImprint('<h1>Content</h1>');

        self::assertSame($model, $result);
        self::assertEquals('<h1>Content</h1>', $model->getImprint());
    }

    /**
     * Tests that setImprint() accepts an empty string.
     */
    public function testSetImprintEmptyString()
    {
        $model = new ImprintModel();
        $model->setImprint('');

        self::assertEquals('', $model->getImprint());
    }

    /**
     * Tests that setters are chainable.
     */
    public function testSettersAreChainable()
    {
        $model = new ImprintModel();
        $result = $model->setId(3)->setImprint('Chained content');

        self::assertSame($model, $result);
        self::assertEquals(3, $model->getId());
        self::assertEquals('Chained content', $model->getImprint());
    }

    /**
     * Tests that setByArray() populates both fields from a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new ImprintModel();
        $model->setByArray([
            'id'      => 7,
            'imprint' => '<p>Full imprint</p>',
        ]);

        self::assertEquals(7, $model->getId());
        self::assertEquals('<p>Full imprint</p>', $model->getImprint());
    }

    /**
     * Tests that setByArray() only updates keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new ImprintModel();
        $model->setId(99);
        $model->setImprint('Original');

        $model->setByArray([
            'imprint' => 'Changed',
        ]);

        self::assertEquals(99, $model->getId());
        self::assertEquals('Changed', $model->getImprint());
    }

    /**
     * Tests that setByArray() with an empty array leaves defaults intact.
     */
    public function testSetByArrayEmpty()
    {
        $model = new ImprintModel();
        $model->setByArray([]);

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getImprint());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new ImprintModel();
        $result = $model->setByArray(['id' => 1]);

        self::assertSame($model, $result);
    }

    /**
     * Tests that getArray() includes id when $withId is true (default).
     */
    public function testGetArrayWithId()
    {
        $model = new ImprintModel();
        $model->setId(4);
        $model->setImprint('Test content');

        $array = $model->getArray();

        self::assertEquals([
            'id'      => 4,
            'imprint' => 'Test content',
        ], $array);
    }

    /**
     * Tests that getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = new ImprintModel();
        $model->setId(4);
        $model->setImprint('Test content');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals([
            'imprint' => 'Test content',
        ], $array);
    }

    /**
     * Tests that getArray() with defaults returns id=0 and imprint=''.
     */
    public function testGetArrayDefaults()
    {
        $model = new ImprintModel();

        self::assertEquals([
            'id'      => 0,
            'imprint' => '',
        ], $model->getArray());
    }

    /**
     * Tests that getArray(false) with defaults returns only imprint=''.
     */
    public function testGetArrayWithoutIdDefaults()
    {
        $model = new ImprintModel();

        self::assertEquals([
            'imprint' => '',
        ], $model->getArray(false));
    }

    /**
     * Tests a full round-trip: set → get → setByArray → get.
     */
    public function testFullRoundTrip()
    {
        $model = new ImprintModel();
        $model->setId(10);
        $model->setImprint('<h2>Angaben gemäß § 5 TMG</h2><p>Max Mustermann</p>');

        self::assertEquals(10, $model->getId());
        self::assertEquals('<h2>Angaben gemäß § 5 TMG</h2><p>Max Mustermann</p>', $model->getImprint());

        // Simulate what the mapper does: fetch row as array, then setByArray
        $rowArray = [
            'id'      => '10',
            'imprint' => '<h2>Angaben gemäß § 5 TMG</h2><p>Max Mustermann</p>',
        ];

        $model2 = new ImprintModel();
        $model2->setByArray($rowArray);

        self::assertEquals(10, $model2->getId());
        self::assertEquals('<h2>Angaben gemäß § 5 TMG</h2><p>Max Mustermann</p>', $model2->getImprint());

        // getArray round-trip
        self::assertEquals($model->getArray(), $model2->getArray());
    }
}
