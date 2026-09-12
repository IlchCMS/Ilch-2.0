<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Linkus\Models;

use PHPUnit\Framework\TestCase;
use Modules\Linkus\Models\Linkus as LinkusModel;

class LinkusModelTest extends TestCase
{
    /**
     * Tests that default property values are id=0, title='', banner=''.
     */
    public function testDefaults()
    {
        $model = new LinkusModel();

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getTitle());
        self::assertEquals('', $model->getBanner());
    }

    /**
     * Tests that setId() stores the value and returns $this.
     */
    public function testSetId()
    {
        $model = new LinkusModel();
        $result = $model->setId(5);

        self::assertSame($model, $result);
        self::assertEquals(5, $model->getId());
    }

    /**
     * Tests that setTitle() stores the value and returns $this.
     */
    public function testSetTitle()
    {
        $model = new LinkusModel();
        $result = $model->setTitle('My Site');

        self::assertSame($model, $result);
        self::assertEquals('My Site', $model->getTitle());
    }

    /**
     * Tests that setBanner() stores the value and returns $this.
     */
    public function testSetBanner()
    {
        $model = new LinkusModel();
        $result = $model->setBanner('banner.html');

        self::assertSame($model, $result);
        self::assertEquals('banner.html', $model->getBanner());
    }

    /**
     * Tests that setBanner() accepts an empty string.
     */
    public function testSetBannerEmptyString()
    {
        $model = new LinkusModel();
        $model->setBanner('');

        self::assertEquals('', $model->getBanner());
    }

    /**
     * Tests that setters are chainable in any order.
     */
    public function testSettersAreChainable()
    {
        $model = new LinkusModel();
        $result = $model->setId(3)->setTitle('Chained')->setBanner('c.html');

        self::assertSame($model, $result);
        self::assertEquals(3, $model->getId());
        self::assertEquals('Chained', $model->getTitle());
        self::assertEquals('c.html', $model->getBanner());
    }

    /**
     * Tests that setByArray() populates all fields from a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new LinkusModel();
        $model->setByArray([
            'id'     => 7,
            'title'  => 'Complete Entry',
            'banner' => 'banner7.html',
        ]);

        self::assertEquals(7, $model->getId());
        self::assertEquals('Complete Entry', $model->getTitle());
        self::assertEquals('banner7.html', $model->getBanner());
    }

    /**
     * Tests that setByArray() only updates keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new LinkusModel();
        $model->setId(99);
        $model->setTitle('Original');
        $model->setBanner('original.html');

        $model->setByArray([
            'title' => 'Changed Title',
        ]);

        self::assertEquals(99, $model->getId());
        self::assertEquals('Changed Title', $model->getTitle());
        self::assertEquals('original.html', $model->getBanner());
    }

    /**
     * Tests that setByArray() with an empty array leaves defaults intact.
     */
    public function testSetByArrayEmpty()
    {
        $model = new LinkusModel();
        $model->setByArray([]);

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getTitle());
        self::assertEquals('', $model->getBanner());
    }

    /**
     * Tests that setByArray() accepts an empty banner string value.
     */
    public function testSetByArrayEmptyBanner()
    {
        $model = new LinkusModel();
        $model->setByArray([
            'id'     => 2,
            'title'  => 'No Banner',
            'banner' => '',
        ]);

        self::assertEquals(2, $model->getId());
        self::assertEquals('No Banner', $model->getTitle());
        self::assertEquals('', $model->getBanner());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new LinkusModel();
        $result = $model->setByArray(['id' => 1]);

        self::assertSame($model, $result);
    }

    /**
     * Tests that getArray() includes id when $withId is true (default).
     */
    public function testGetArrayWithId()
    {
        $model = new LinkusModel();
        $model->setId(4);
        $model->setTitle('Test Title');
        $model->setBanner('test.html');

        $array = $model->getArray();

        self::assertEquals([
            'id'     => 4,
            'title'  => 'Test Title',
            'banner' => 'test.html',
        ], $array);
    }

    /**
     * Tests that getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = new LinkusModel();
        $model->setId(4);
        $model->setTitle('Test Title');
        $model->setBanner('test.html');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals([
            'title'  => 'Test Title',
            'banner' => 'test.html',
        ], $array);
    }

    /**
     * Tests that getArray() with defaults returns id=0 and empty strings.
     */
    public function testGetArrayDefaults()
    {
        $model = new LinkusModel();

        self::assertEquals([
            'id'     => 0,
            'title'  => '',
            'banner' => '',
        ], $model->getArray());
    }

    /**
     * Tests that getArray(false) with defaults returns only empty strings.
     */
    public function testGetArrayWithoutIdDefaults()
    {
        $model = new LinkusModel();

        self::assertEquals([
            'title'  => '',
            'banner' => '',
        ], $model->getArray(false));
    }

    /**
     * Tests a full round-trip: set → getArray → setByArray → getArray.
     */
    public function testFullRoundTrip()
    {
        $model = new LinkusModel();
        $model->setId(10);
        $model->setTitle('Round Trip');
        $model->setBanner('rt.html');

        $array = $model->getArray();

        $model2 = new LinkusModel();
        $model2->setByArray($array);

        self::assertEquals(10, $model2->getId());
        self::assertEquals('Round Trip', $model2->getTitle());
        self::assertEquals('rt.html', $model2->getBanner());

        self::assertEquals($model->getArray(), $model2->getArray());
    }
}
