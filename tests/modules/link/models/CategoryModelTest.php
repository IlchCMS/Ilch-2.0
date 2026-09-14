<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Link\Models;

use PHPUnit\Framework\TestCase;

class CategoryModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Category();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Category();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setPosition() sets and returns the position.
     */
    public function testSetPosition()
    {
        $model = new Category();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    /**
     * Tests that setPosition() casts to int.
     */
    public function testSetPositionCastsToInt()
    {
        $model = new Category();
        $model->setPosition('7');

        self::assertSame(7, $model->getPosition());
        self::assertIsInt($model->getPosition());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new Category();
        $model->setName('Technology');

        self::assertSame('Technology', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new Category();
        $model->setName(123);

        self::assertSame('123', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setParentId() sets and returns the parent id.
     */
    public function testSetParentId()
    {
        $model = new Category();
        $model->setParentId(4);

        self::assertSame(4, $model->getParentId());
    }

    /**
     * Tests that setParentId() casts to int.
     */
    public function testSetParentIdCastsToInt()
    {
        $model = new Category();
        $model->setParentId('6');

        self::assertSame(6, $model->getParentId());
        self::assertIsInt($model->getParentId());
    }

    /**
     * Tests that setDesc() sets and returns the description.
     */
    public function testSetDesc()
    {
        $model = new Category();
        $model->setDesc('Technology links');

        self::assertSame('Technology links', $model->getDesc());
    }

    /**
     * Tests that setDesc() casts to string.
     */
    public function testSetDescCastsToString()
    {
        $model = new Category();
        $model->setDesc(456);

        self::assertSame('456', $model->getDesc());
        self::assertIsString($model->getDesc());
    }

    /**
     * Tests that setLinksCount() sets and returns the links count.
     */
    public function testSetLinksCount()
    {
        $model = new Category();
        $model->setLinksCount(5);

        self::assertSame(5, $model->getLinksCount());
    }

    /**
     * Tests that setLinksCount() casts to int.
     */
    public function testSetLinksCountCastsToInt()
    {
        $model = new Category();
        $model->setLinksCount('8');

        self::assertSame(8, $model->getLinksCount());
        self::assertIsInt($model->getLinksCount());
    }

    /**
     * Tests that setAccess() sets and returns the access string.
     */
    public function testSetAccess()
    {
        $model = new Category();
        $model->setAccess('1,2,3');

        self::assertSame('1,2,3', $model->getAccess());
    }

    /**
     * Tests that setAccess() casts to string.
     */
    public function testSetAccessCastsToString()
    {
        $model = new Category();
        $model->setAccess(123);

        self::assertSame('123', $model->getAccess());
        self::assertIsString($model->getAccess());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Category();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setPosition(2));
        self::assertSame($model, $model->setName('Test'));
        self::assertSame($model, $model->setParentId(3));
        self::assertSame($model, $model->setDesc('Desc'));
        self::assertSame($model, $model->setLinksCount(4));
        self::assertSame($model, $model->setAccess('1,2'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Category())
            ->setId(3)
            ->setPosition(1)
            ->setName('Programming')
            ->setParentId(1)
            ->setDesc('Programming resources')
            ->setLinksCount(2)
            ->setAccess('1,2');

        self::assertSame(3, $model->getId());
        self::assertSame(1, $model->getPosition());
        self::assertSame('Programming', $model->getName());
        self::assertSame(1, $model->getParentId());
        self::assertSame('Programming resources', $model->getDesc());
        self::assertSame(2, $model->getLinksCount());
        self::assertSame('1,2', $model->getAccess());
    }

    /**
     * Tests that default values are 0 for id/position/parentId/linksCount and empty string for name/desc/access.
     */
    public function testDefaultValues()
    {
        $model = new Category();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getPosition());
        self::assertSame('', $model->getName());
        self::assertSame(0, $model->getParentId());
        self::assertSame('', $model->getDesc());
        self::assertSame(0, $model->getLinksCount());
        self::assertSame('', $model->getAccess());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Category();
        $model->setId(1)->setName('Old')->setParentId(5);

        $model->setId(2)->setName('New')->setParentId(10);

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getName());
        self::assertSame(10, $model->getParentId());
    }

    /**
     * Tests setByArray() populates all fields from a database row.
     */
    public function testSetByArray()
    {
        $model = new Category();
        $model->setByArray([
            'id'        => 7,
            'parent_id' => 2,
            'pos'       => 3,
            'name'      => 'Testing',
            'desc'      => 'Test category',
            'count'     => 4,
            'access'    => '1,2,3',
        ]);

        self::assertSame(7, $model->getId());
        self::assertSame(2, $model->getParentId());
        self::assertSame(3, $model->getPosition());
        self::assertSame('Testing', $model->getName());
        self::assertSame('Test category', $model->getDesc());
        self::assertSame(4, $model->getLinksCount());
        self::assertSame('1,2,3', $model->getAccess());
    }

    /**
     * Tests setByArray() ignores missing keys (defaults remain).
     */
    public function testSetByArrayMissingKeys()
    {
        $model = new Category();
        $model->setByArray([
            'id'   => 1,
            'name' => 'Only Name',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Only Name', $model->getName());
        self::assertSame(0, $model->getParentId());
        self::assertSame(0, $model->getPosition());
        self::assertSame('', $model->getDesc());
        self::assertSame(0, $model->getLinksCount());
        self::assertSame('', $model->getAccess());
    }

    /**
     * Tests getArray() includes id by default.
     */
    public function testGetArrayWithId()
    {
        $model = (new Category())
            ->setId(5)
            ->setPosition(2)
            ->setName('Tech')
            ->setParentId(1)
            ->setDesc('Tech desc');

        $array = $model->getArray();

        self::assertSame(5, $array['id']);
        self::assertSame(2, $array['pos']);
        self::assertSame('Tech', $array['name']);
        self::assertSame(1, $array['parent_id']);
        self::assertSame('Tech desc', $array['desc']);
    }

    /**
     * Tests getArray() excludes id when withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new Category())
            ->setId(5)
            ->setPosition(2)
            ->setName('Tech')
            ->setParentId(1)
            ->setDesc('Tech desc');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(2, $array['pos']);
        self::assertSame('Tech', $array['name']);
        self::assertSame(1, $array['parent_id']);
        self::assertSame('Tech desc', $array['desc']);
    }

    /**
     * Tests that getArray() does not include access or linksCount.
     */
    public function testGetArrayExcludesAccessAndLinksCount()
    {
        $model = (new Category())
            ->setId(1)
            ->setAccess('1,2')
            ->setLinksCount(5);

        $array = $model->getArray();

        self::assertArrayNotHasKey('access', $array);
        self::assertArrayNotHasKey('linksCount', $array);
    }
}
