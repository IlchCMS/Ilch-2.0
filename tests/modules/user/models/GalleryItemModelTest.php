<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Framework\TestCase;
use Modules\User\Models\GalleryItem as GalleryItemModel;

class GalleryItemModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new GalleryItemModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new GalleryItemModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GalleryItemModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setSort() sets and returns the sort order.
     */
    public function testSetSort()
    {
        $model = new GalleryItemModel();
        $model->setSort(3);

        self::assertSame(3, $model->getSort());
    }

    /**
     * Tests that setSort() casts to int.
     */
    public function testSetSortCastsToInt()
    {
        $model = new GalleryItemModel();
        $model->setSort('42');

        self::assertSame(42, $model->getSort());
        self::assertIsInt($model->getSort());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new GalleryItemModel();
        $model->setType(1);

        self::assertSame(1, $model->getType());
    }

    /**
     * Tests that setType() casts to int.
     */
    public function testSetTypeCastsToInt()
    {
        $model = new GalleryItemModel();
        $model->setType('42');

        self::assertSame(42, $model->getType());
        self::assertIsInt($model->getType());
    }

    /**
     * Tests that setUserId() sets and returns the user id.
     */
    public function testSetUserId()
    {
        $model = new GalleryItemModel();
        $model->setUserId(5);

        self::assertSame(5, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new GalleryItemModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setParentId() sets and returns the parent id.
     */
    public function testSetParentId()
    {
        $model = new GalleryItemModel();
        $model->setParentId(7);

        self::assertSame(7, $model->getParentId());
    }

    /**
     * Tests that setParentId() casts to int.
     */
    public function testSetParentIdCastsToInt()
    {
        $model = new GalleryItemModel();
        $model->setParentId('42');

        self::assertSame(42, $model->getParentId());
        self::assertIsInt($model->getParentId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new GalleryItemModel();
        $model->setTitle('Album');

        self::assertSame('Album', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new GalleryItemModel();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setDesc() sets and returns the description.
     */
    public function testSetDesc()
    {
        $model = new GalleryItemModel();
        $model->setDesc('My description');

        self::assertSame('My description', $model->getDesc());
    }

    /**
     * Tests that setDesc() casts to string.
     */
    public function testSetDescCastsToString()
    {
        $model = new GalleryItemModel();
        $model->setDesc(456);

        self::assertSame('456', $model->getDesc());
        self::assertIsString($model->getDesc());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new GalleryItemModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setSort(2));
        self::assertSame($model, $model->setType(3));
        self::assertSame($model, $model->setUserId(4));
        self::assertSame($model, $model->setParentId(5));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setDesc('Desc'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new GalleryItemModel())
            ->setId(1)
            ->setSort(2)
            ->setType(1)
            ->setUserId(3)
            ->setParentId(4)
            ->setTitle('Album')
            ->setDesc('Description');

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getSort());
        self::assertSame(1, $model->getType());
        self::assertSame(3, $model->getUserId());
        self::assertSame(4, $model->getParentId());
        self::assertSame('Album', $model->getTitle());
        self::assertSame('Description', $model->getDesc());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new GalleryItemModel();

        self::assertNull($model->getId());
        self::assertNull($model->getSort());
        self::assertNull($model->getType());
        self::assertNull($model->getUserId());
        self::assertNull($model->getParentId());
        self::assertNull($model->getTitle());
        self::assertNull($model->getDesc());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GalleryItemModel();
        $model->setId(1)->setSort(1)->setType(1)->setUserId(1)->setParentId(1)->setTitle('Old')->setDesc('Old');

        $model->setId(2)->setSort(2)->setType(2)->setUserId(2)->setParentId(2)->setTitle('New')->setDesc('New');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getSort());
        self::assertSame(2, $model->getType());
        self::assertSame(2, $model->getUserId());
        self::assertSame(2, $model->getParentId());
        self::assertSame('New', $model->getTitle());
        self::assertSame('New', $model->getDesc());
    }
}
