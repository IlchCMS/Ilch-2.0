<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;

class MenuItemModelTest extends TestCase
{
    /**
     * Tests the type constant values.
     */
    public function testTypeConstants()
    {
        self::assertSame(0, MenuItem::TYPE_MENU);
        self::assertSame(1, MenuItem::TYPE_LINK);
        self::assertSame(2, MenuItem::TYPE_PAGE_LINK);
        self::assertSame(3, MenuItem::TYPE_MODULE_LINK);
        self::assertSame(4, MenuItem::TYPE_BOX);
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new MenuItem();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that all integer setters cast to int.
     */
    public function testIntegerSettersCastToInt()
    {
        $model = new MenuItem();
        $model->setId('1');
        $model->setSort('2');
        $model->setType('3');
        $model->setSiteId('4');
        $model->setBoxId('5');
        $model->setMenuId('6');
        $model->setParentId('7');

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getSort());
        self::assertSame(3, $model->getType());
        self::assertSame(4, $model->getSiteId());
        self::assertSame(5, $model->getBoxId());
        self::assertSame(6, $model->getMenuId());
        self::assertSame(7, $model->getParentId());

        self::assertIsInt($model->getId());
        self::assertIsInt($model->getSort());
        self::assertIsInt($model->getType());
        self::assertIsInt($model->getSiteId());
        self::assertIsInt($model->getBoxId());
        self::assertIsInt($model->getMenuId());
        self::assertIsInt($model->getParentId());
    }

    /**
     * Tests that all string setters cast to string.
     */
    public function testStringSettersCastToString()
    {
        $model = new MenuItem();
        $model->setModuleKey(10);
        $model->setBoxKey(11);
        $model->setTitle(12);
        $model->setHref(13);

        self::assertSame('10', $model->getModuleKey());
        self::assertSame('11', $model->getBoxKey());
        self::assertSame('12', $model->getTitle());
        self::assertSame('13', $model->getHref());

        self::assertIsString($model->getModuleKey());
        self::assertIsString($model->getBoxKey());
        self::assertIsString($model->getTitle());
        self::assertIsString($model->getHref());
    }

    /**
     * Tests that setTarget() stores the value without casting.
     */
    public function testSetTargetNoCast()
    {
        $model = new MenuItem();
        $model->setTarget(1);

        self::assertSame(1, $model->getTarget());
        self::assertIsInt($model->getTarget());
    }

    /**
     * Tests that setAccess() stores the value without casting.
     */
    public function testSetAccessNoCast()
    {
        $model = new MenuItem();
        $model->setAccess(2);

        self::assertSame(2, $model->getAccess());
        self::assertIsInt($model->getAccess());
    }

    /**
     * Tests isLink() for every type.
     */
    public function testIsLink()
    {
        $expected = [
            MenuItem::TYPE_MENU => false,
            MenuItem::TYPE_LINK => true,
            MenuItem::TYPE_PAGE_LINK => true,
            MenuItem::TYPE_MODULE_LINK => true,
            MenuItem::TYPE_BOX => false,
        ];

        foreach ($expected as $type => $expectedResult) {
            $model = new MenuItem();
            $model->setType($type);

            self::assertSame($expectedResult, $model->isLink(), 'Failed for type ' . $type);
        }
    }

    /**
     * Tests isModuleLink() for every type.
     */
    public function testIsModuleLink()
    {
        $expected = [
            MenuItem::TYPE_MENU => false,
            MenuItem::TYPE_LINK => false,
            MenuItem::TYPE_PAGE_LINK => false,
            MenuItem::TYPE_MODULE_LINK => true,
            MenuItem::TYPE_BOX => false,
        ];

        foreach ($expected as $type => $expectedResult) {
            $model = new MenuItem();
            $model->setType($type);

            self::assertSame($expectedResult, $model->isModuleLink(), 'Failed for type ' . $type);
        }
    }

    /**
     * Tests isPageLink() for every type.
     */
    public function testIsPageLink()
    {
        $expected = [
            MenuItem::TYPE_MENU => false,
            MenuItem::TYPE_LINK => false,
            MenuItem::TYPE_PAGE_LINK => true,
            MenuItem::TYPE_MODULE_LINK => false,
            MenuItem::TYPE_BOX => false,
        ];

        foreach ($expected as $type => $expectedResult) {
            $model = new MenuItem();
            $model->setType($type);

            self::assertSame($expectedResult, $model->isPageLink(), 'Failed for type ' . $type);
        }
    }

    /**
     * Tests isBox() for every type.
     */
    public function testIsBox()
    {
        $expected = [
            MenuItem::TYPE_MENU => false,
            MenuItem::TYPE_LINK => false,
            MenuItem::TYPE_PAGE_LINK => false,
            MenuItem::TYPE_MODULE_LINK => false,
            MenuItem::TYPE_BOX => true,
        ];

        foreach ($expected as $type => $expectedResult) {
            $model = new MenuItem();
            $model->setType($type);

            self::assertSame($expectedResult, $model->isBox(), 'Failed for type ' . $type);
        }
    }

    /**
     * Tests isMenu() for every type.
     */
    public function testIsMenu()
    {
        $expected = [
            MenuItem::TYPE_MENU => true,
            MenuItem::TYPE_LINK => false,
            MenuItem::TYPE_PAGE_LINK => false,
            MenuItem::TYPE_MODULE_LINK => false,
            MenuItem::TYPE_BOX => false,
        ];

        foreach ($expected as $type => $expectedResult) {
            $model = new MenuItem();
            $model->setType($type);

            self::assertSame($expectedResult, $model->isMenu(), 'Failed for type ' . $type);
        }
    }

    /**
     * Tests that the type checks are false when no type is set (null).
     */
    public function testTypeChecksWithoutType()
    {
        $model = new MenuItem();

        self::assertFalse($model->isMenu());
        self::assertFalse($model->isLink());
        self::assertFalse($model->isModuleLink());
        self::assertFalse($model->isPageLink());
        self::assertFalse($model->isBox());
    }

    /**
     * Tests that overwriting previously set values works.
     */
    public function testOverwriteValues()
    {
        $model = new MenuItem();
        $model->setId(1);
        $model->setSort(1);
        $model->setType(MenuItem::TYPE_LINK);
        $model->setTitle('Old');

        $model->setId(2);
        $model->setSort(2);
        $model->setType(MenuItem::TYPE_MENU);
        $model->setTitle('New');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getSort());
        self::assertSame(MenuItem::TYPE_MENU, $model->getType());
        self::assertSame('New', $model->getTitle());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new MenuItem();

        self::assertNull($model->getId());
        self::assertNull($model->getSort());
        self::assertNull($model->getType());
        self::assertNull($model->getModuleKey());
        self::assertNull($model->getSiteId());
        self::assertNull($model->getBoxId());
        self::assertNull($model->getBoxKey());
        self::assertNull($model->getMenuId());
        self::assertNull($model->getParentId());
        self::assertNull($model->getTitle());
        self::assertNull($model->getHref());
        self::assertNull($model->getTarget());
        self::assertNull($model->getAccess());
    }
}
