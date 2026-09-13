<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Partner\Models;

use PHPUnit\Framework\TestCase;
use Modules\Partner\Models\Partner as PartnerModel;

class PartnerModelTest extends TestCase
{
    /**
     * Tests default values of a new Partner model.
     */
    public function testDefaultValues()
    {
        $model = new PartnerModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getLink());
        self::assertSame('', $model->getBanner());
        self::assertSame(0, $model->getTarget());
        self::assertSame(1, $model->getFree());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new PartnerModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero()
    {
        $model = new PartnerModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new PartnerModel();
        $model->setName('ilch');

        self::assertSame('ilch', $model->getName());
    }

    /**
     * Tests that setLink() sets and returns the link.
     */
    public function testSetLink()
    {
        $model = new PartnerModel();
        $model->setLink('https://ilch.de');

        self::assertSame('https://ilch.de', $model->getLink());
    }

    /**
     * Tests that setBanner() sets and returns the banner.
     */
    public function testSetBanner()
    {
        $model = new PartnerModel();
        $model->setBanner('https://www.ilch.de/include/images/linkus/88x31.png');

        self::assertSame('https://www.ilch.de/include/images/linkus/88x31.png', $model->getBanner());
    }

    /**
     * Tests that setTarget() sets and returns the target.
     */
    public function testSetTarget()
    {
        $model = new PartnerModel();
        $model->setTarget(1);

        self::assertSame(1, $model->getTarget());
    }

    /**
     * Tests that setTarget(0) stores zero.
     */
    public function testSetTargetZero()
    {
        $model = new PartnerModel();
        $model->setTarget(0);

        self::assertSame(0, $model->getTarget());
    }

    /**
     * Tests that setFree() sets and returns the free flag.
     */
    public function testSetFree()
    {
        $model = new PartnerModel();
        $model->setFree(0);

        self::assertSame(0, $model->getFree());
    }

    /**
     * Tests that setFree(1) stores one.
     */
    public function testSetFreeOne()
    {
        $model = new PartnerModel();
        $model->setFree(1);

        self::assertSame(1, $model->getFree());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new PartnerModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setLink('https://test.com'));
        self::assertSame($model, $model->setBanner('https://test.com/banner.png'));
        self::assertSame($model, $model->setTarget(1));
        self::assertSame($model, $model->setFree(0));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new PartnerModel())
            ->setId(2)
            ->setName('Example Corp')
            ->setLink('https://example.com')
            ->setBanner('https://example.com/banners/example.png')
            ->setTarget(1)
            ->setFree(0);

        self::assertSame(2, $model->getId());
        self::assertSame('Example Corp', $model->getName());
        self::assertSame('https://example.com', $model->getLink());
        self::assertSame('https://example.com/banners/example.png', $model->getBanner());
        self::assertSame(1, $model->getTarget());
        self::assertSame(0, $model->getFree());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new PartnerModel();
        $model->setId(1)
            ->setName('Old Name')
            ->setLink('https://old.com')
            ->setBanner('https://old.com/banner.png')
            ->setTarget(0)
            ->setFree(1);

        $model->setId(2)
            ->setName('New Name')
            ->setLink('https://new.com')
            ->setBanner('https://new.com/banner.png')
            ->setTarget(1)
            ->setFree(0);

        self::assertSame(2, $model->getId());
        self::assertSame('New Name', $model->getName());
        self::assertSame('https://new.com', $model->getLink());
        self::assertSame('https://new.com/banner.png', $model->getBanner());
        self::assertSame(1, $model->getTarget());
        self::assertSame(0, $model->getFree());
    }

    /**
     * Tests that setByArray() populates all fields from a DB-like row.
     */
    public function testSetByArray()
    {
        $model = new PartnerModel();
        $model->setByArray([
            'id'      => 1,
            'name'    => 'ilch',
            'link'    => 'https://ilch.de',
            'banner'  => 'https://www.ilch.de/include/images/linkus/88x31.png',
            'target'  => 1,
            'setfree' => 1,
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('ilch', $model->getName());
        self::assertSame('https://ilch.de', $model->getLink());
        self::assertSame('https://www.ilch.de/include/images/linkus/88x31.png', $model->getBanner());
        self::assertSame(1, $model->getTarget());
        self::assertSame(1, $model->getFree());
    }

    /**
     * Tests that setByArray() correctly sets zero values via isset() checks.
     */
    public function testSetByArraySetsZeroValues()
    {
        $model = new PartnerModel();
        $model->setByArray([
            'id'      => 0,
            'name'    => 'Test',
            'link'    => 'https://test.com',
            'banner'  => 'https://test.com/banner.png',
            'target'  => 0,
            'setfree' => 0,
        ]);

        // id=0 is correctly set via isset()
        self::assertSame(0, $model->getId());
        self::assertSame('Test', $model->getName());
        self::assertSame('https://test.com', $model->getLink());
        self::assertSame('https://test.com/banner.png', $model->getBanner());
        // target=0 is correctly set via isset()
        self::assertSame(0, $model->getTarget());
        // setfree=0 is correctly set via isset() (was buggy with !empty())
        self::assertSame(0, $model->getFree());
    }

    /**
     * Tests that setByArray() only sets keys that are present and non-empty.
     */
    public function testSetByArrayPartial()
    {
        $model = new PartnerModel();
        $model->setByArray([
            'name' => 'Partial Partner',
        ]);

        self::assertSame(0, $model->getId());
        self::assertSame('Partial Partner', $model->getName());
        self::assertSame('', $model->getLink());
        self::assertSame('', $model->getBanner());
        self::assertSame(0, $model->getTarget());
        self::assertSame(1, $model->getFree());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new PartnerModel();

        self::assertSame($model, $model->setByArray(['id' => 1, 'name' => 'Test']));
    }

    /**
     * Tests that getArray() includes id by default.
     */
    public function testGetArrayWithId()
    {
        $model = (new PartnerModel())
            ->setId(2)
            ->setName('Example Corp')
            ->setLink('https://example.com')
            ->setBanner('https://example.com/banners/example.png')
            ->setTarget(1)
            ->setFree(0);

        $array = $model->getArray();

        self::assertIsArray($array);
        self::assertArrayHasKey('id', $array);
        self::assertEquals(2, $array['id']);
        self::assertEquals('Example Corp', $array['name']);
        self::assertEquals('https://example.com', $array['link']);
        self::assertEquals('https://example.com/banners/example.png', $array['banner']);
        self::assertEquals(1, $array['target']);
        self::assertEquals(0, $array['setfree']);
        self::assertCount(6, $array);
    }

    /**
     * Tests that getArray(false) excludes the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new PartnerModel())
            ->setId(2)
            ->setName('Example Corp')
            ->setLink('https://example.com')
            ->setBanner('https://example.com/banners/example.png')
            ->setTarget(1)
            ->setFree(0);

        $array = $model->getArray(false);

        self::assertIsArray($array);
        self::assertArrayNotHasKey('id', $array);
        self::assertCount(5, $array);
        self::assertEquals('Example Corp', $array['name']);
        self::assertEquals('https://example.com', $array['link']);
        self::assertEquals('https://example.com/banners/example.png', $array['banner']);
        self::assertEquals(1, $array['target']);
        self::assertEquals(0, $array['setfree']);
    }

    /**
     * Tests that getArray() uses 'setfree' key (matching DB column name).
     */
    public function testGetArrayUsesSetfreeKey()
    {
        $model = new PartnerModel();
        $model->setFree(1);

        $array = $model->getArray(false);

        self::assertArrayHasKey('setfree', $array);
        self::assertArrayNotHasKey('free', $array);
        self::assertEquals(1, $array['setfree']);
    }
}
