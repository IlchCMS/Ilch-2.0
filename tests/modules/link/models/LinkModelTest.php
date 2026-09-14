<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Link\Models;

use PHPUnit\Framework\TestCase;

class LinkModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Link();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Link();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new Link();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that setPosition() casts to int.
     */
    public function testSetPositionCastsToInt()
    {
        $model = new Link();
        $model->setPosition('7');

        self::assertSame(7, $model->getPosition());
        self::assertIsInt($model->getPosition());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new Link();
        $model->setName('ilch');

        self::assertSame('ilch', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new Link();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setLink() sets and returns the link URL.
     */
    public function testSetLink()
    {
        $model = new Link();
        $model->setLink('https://ilch.de');

        self::assertSame('https://ilch.de', $model->getLink());
    }

    /**
     * Tests that setLink() casts to string.
     */
    public function testSetLinkCastsToString()
    {
        $model = new Link();
        $model->setLink(456);

        self::assertSame('456', $model->getLink());
        self::assertIsString($model->getLink());
    }

    /**
     * Tests that setBanner() sets and returns the banner.
     */
    public function testSetBanner()
    {
        $model = new Link();
        $model->setBanner('https://example.com/banner.png');

        self::assertSame('https://example.com/banner.png', $model->getBanner());
    }

    /**
     * Tests that setBanner() casts to string.
     */
    public function testSetBannerCastsToString()
    {
        $model = new Link();
        $model->setBanner(789);

        self::assertSame('789', $model->getBanner());
        self::assertIsString($model->getBanner());
    }

    /**
     * Tests that setCatId() sets and returns the category id.
     */
    public function testSetCatId()
    {
        $model = new Link();
        $model->setCatId(3);

        self::assertSame(3, $model->getCatId());
    }

    /**
     * Tests that setCatId() casts to int.
     */
    public function testSetCatIdCastsToInt()
    {
        $model = new Link();
        $model->setCatId('6');

        self::assertSame(6, $model->getCatId());
        self::assertIsInt($model->getCatId());
    }

    /**
     * Tests that setDesc() sets and returns the description.
     */
    public function testSetDesc()
    {
        $model = new Link();
        $model->setDesc('A great resource');

        self::assertSame('A great resource', $model->getDesc());
    }

    /**
     * Tests that setDesc() casts to string.
     */
    public function testSetDescCastsToString()
    {
        $model = new Link();
        $model->setDesc(456);

        self::assertSame('456', $model->getDesc());
        self::assertIsString($model->getDesc());
    }

    /**
     * Tests that setHits() sets and returns the hits.
     */
    public function testSetHits()
    {
        $model = new Link();
        $model->setHits(42);

        self::assertSame(42, $model->getHits());
    }

    /**
     * Tests that setHits() casts to int.
     */
    public function testSetHitsCastsToInt()
    {
        $model = new Link();
        $model->setHits('100');

        self::assertSame(100, $model->getHits());
        self::assertIsInt($model->getHits());
    }

    /**
     * Tests that addHits() increments the hits by 1 by default.
     */
    public function testAddHitsDefault()
    {
        $model = new Link();
        $model->setHits(10);
        $model->addHits();

        self::assertSame(11, $model->getHits());
    }

    /**
     * Tests that addHits() increments by a custom amount.
     */
    public function testAddHitsCustom()
    {
        $model = new Link();
        $model->setHits(5);
        $model->addHits(15);

        self::assertSame(20, $model->getHits());
    }

    /**
     * Tests that addHits() works from zero default.
     */
    public function testAddHitsFromZero()
    {
        $model = new Link();
        $model->addHits();

        self::assertSame(1, $model->getHits());
    }

    /**
     * Tests that setAccess() sets and returns the access string.
     */
    public function testSetAccess()
    {
        $model = new Link();
        $model->setAccess('1,2,3');

        self::assertSame('1,2,3', $model->getAccess());
    }

    /**
     * Tests that setAccess() casts to string.
     */
    public function testSetAccessCastsToString()
    {
        $model = new Link();
        $model->setAccess(123);

        self::assertSame('123', $model->getAccess());
        self::assertIsString($model->getAccess());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Link();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setPosition(2));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setLink('https://test.com'));
        self::assertSame($model, $model->setBanner('https://test.com/banner.png'));
        self::assertSame($model, $model->setCatId(3));
        self::assertSame($model, $model->setDesc('Desc'));
        self::assertSame($model, $model->setHits(5));
        self::assertSame($model, $model->setAccess('1,2'));
        self::assertSame($model, $model->addHits());
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Link())
            ->setId(3)
            ->setPosition(1)
            ->setName('PHP')
            ->setLink('https://php.net')
            ->setBanner('https://php.net/banner.png')
            ->setCatId(2)
            ->setDesc('PHP resource')
            ->setHits(5)
            ->setAccess('1,2');

        self::assertSame(3, $model->getId());
        self::assertSame(1, $model->getPosition());
        self::assertSame('PHP', $model->getName());
        self::assertSame('https://php.net', $model->getLink());
        self::assertSame('https://php.net/banner.png', $model->getBanner());
        self::assertSame(2, $model->getCatId());
        self::assertSame('PHP resource', $model->getDesc());
        self::assertSame(5, $model->getHits());
        self::assertSame('1,2', $model->getAccess());
    }

    /**
     * Tests that default values are 0 for id/position/catId/hits and empty string for name/link/banner/desc/access.
     */
    public function testDefaultValues()
    {
        $model = new Link();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getPosition());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getLink());
        self::assertSame('', $model->getBanner());
        self::assertSame(0, $model->getCatId());
        self::assertSame('', $model->getDesc());
        self::assertSame(0, $model->getHits());
        self::assertSame('', $model->getAccess());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Link();
        $model->setId(1)->setName('Old')->setLink('https://old.com');

        $model->setId(2)->setName('New')->setLink('https://new.com');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getName());
        self::assertSame('https://new.com', $model->getLink());
    }

    /**
     * Tests setByArray() populates all fields from a database row.
     */
    public function testSetByArray()
    {
        $model = new Link();
        $model->setByArray([
            'id'     => 7,
            'cat_id' => 2,
            'pos'    => 3,
            'name'   => 'Dribbble',
            'desc'   => 'Design inspiration',
            'banner' => 'https://dribbble.com/logo.png',
            'link'   => 'https://dribbble.com',
            'hits'   => 15,
            'access' => '1,2',
        ]);

        self::assertSame(7, $model->getId());
        self::assertSame(2, $model->getCatId());
        self::assertSame(3, $model->getPosition());
        self::assertSame('Dribbble', $model->getName());
        self::assertSame('Design inspiration', $model->getDesc());
        self::assertSame('https://dribbble.com/logo.png', $model->getBanner());
        self::assertSame('https://dribbble.com', $model->getLink());
        self::assertSame(15, $model->getHits());
        self::assertSame('1,2', $model->getAccess());
    }

    /**
     * Tests setByArray() ignores missing keys (defaults remain).
     */
    public function testSetByArrayMissingKeys()
    {
        $model = new Link();
        $model->setByArray([
            'id'   => 1,
            'name' => 'Only Name',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Only Name', $model->getName());
        self::assertSame(0, $model->getCatId());
        self::assertSame(0, $model->getPosition());
        self::assertSame('', $model->getLink());
        self::assertSame('', $model->getBanner());
        self::assertSame('', $model->getDesc());
        self::assertSame(0, $model->getHits());
        self::assertSame('', $model->getAccess());
    }

    /**
     * Tests getArray() includes id by default.
     */
    public function testGetArrayWithId()
    {
        $model = (new Link())
            ->setId(5)
            ->setPosition(2)
            ->setName('ilch')
            ->setLink('https://ilch.de')
            ->setBanner('https://ilch.de/banner.png')
            ->setCatId(1)
            ->setDesc('CMS')
            ->setHits(10);

        $array = $model->getArray();

        self::assertSame(5, $array['id']);
        self::assertSame(2, $array['pos']);
        self::assertSame('ilch', $array['name']);
        self::assertSame('https://ilch.de', $array['link']);
        self::assertSame('https://ilch.de/banner.png', $array['banner']);
        self::assertSame(1, $array['cat_id']);
        self::assertSame('CMS', $array['desc']);
        self::assertSame(10, $array['hits']);
    }

    /**
     * Tests getArray() excludes id when withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new Link())
            ->setId(5)
            ->setPosition(2)
            ->setName('ilch')
            ->setLink('https://ilch.de')
            ->setBanner('https://ilch.de/banner.png')
            ->setCatId(1)
            ->setDesc('CMS')
            ->setHits(10);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(2, $array['pos']);
        self::assertSame('ilch', $array['name']);
    }

    /**
     * Tests that getArray() does not include access.
     */
    public function testGetArrayExcludesAccess()
    {
        $model = (new Link())
            ->setId(1)
            ->setAccess('1,2');

        $array = $model->getArray();

        self::assertArrayNotHasKey('access', $array);
    }
}
