<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Gallery\Models;

use PHPUnit\Framework\TestCase;
use Modules\Gallery\Models\GalleryItem as GalleryItemModel;

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
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new GalleryItemModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setSort() sets and returns the sort.
     */
    public function testSetSort()
    {
        $model = new GalleryItemModel();
        $model->setSort(3);

        self::assertSame(3, $model->getSort());
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
     * Tests that setParentId() sets and returns the parent id.
     */
    public function testSetParentId()
    {
        $model = new GalleryItemModel();
        $model->setParentId(2);

        self::assertSame(2, $model->getParentId());
    }

    /**
     * Tests that setParentId(0) stores zero for root items.
     */
    public function testSetParentIdZero()
    {
        $model = new GalleryItemModel();
        $model->setParentId(0);

        self::assertSame(0, $model->getParentId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new GalleryItemModel();
        $model->setTitle('My Gallery');

        self::assertSame('My Gallery', $model->getTitle());
    }

    /**
     * Tests that setDesc() sets and returns the description.
     */
    public function testSetDesc()
    {
        $model = new GalleryItemModel();
        $model->setDesc('A description');

        self::assertSame('A description', $model->getDesc());
    }

    /**
     * Tests that default values are 0 for int fields and empty string for string fields.
     */
    public function testDefaultValues()
    {
        $model = new GalleryItemModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getSort());
        self::assertSame(0, $model->getType());
        self::assertSame(0, $model->getParentId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getDesc());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new GalleryItemModel();
        $model->setId(1);
        $model->setSort(1);
        $model->setParentId(0);
        $model->setType(1);
        $model->setTitle('Old');
        $model->setDesc('Old desc');

        $model->setId(2);
        $model->setSort(5);
        $model->setParentId(1);
        $model->setType(0);
        $model->setTitle('New');
        $model->setDesc('New desc');

        self::assertSame(2, $model->getId());
        self::assertSame(5, $model->getSort());
        self::assertSame(1, $model->getParentId());
        self::assertSame(0, $model->getType());
        self::assertSame('New', $model->getTitle());
        self::assertSame('New desc', $model->getDesc());
    }

    /**
     * Tests that setByArray() populates fields from a database row.
     */
    public function testSetByArray()
    {
        $model = new GalleryItemModel();
        $entries = [
            'id' => 4,
            'sort' => 2,
            'parent_id' => 1,
            'type' => 0,
            'title' => 'Test Item',
            'description' => 'Test description',
        ];

        $result = $model->setByArray($entries);

        self::assertSame($model, $result);
        self::assertSame(4, $model->getId());
        self::assertSame(1, $model->getParentId());
        self::assertSame(0, $model->getType());
        self::assertSame('Test Item', $model->getTitle());
        self::assertSame('Test description', $model->getDesc());
    }

    /**
     * Tests that setByArray() populates the sort field from the 'sort' key.
     */
    public function testSetByArrayPopulatesSort()
    {
        $model = new GalleryItemModel();
        $entries = [
            'id' => 4,
            'sort' => 7,
            'parent_id' => 1,
            'type' => 0,
            'title' => 'Test',
            'description' => 'Desc',
        ];

        $model->setByArray($entries);

        self::assertSame(7, $model->getSort());
    }

    /**
     * Tests that setByArray() returns $this (chainable).
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new GalleryItemModel();

        self::assertSame($model, $model->setByArray(['id' => 1, 'title' => 'A']));
    }

    /**
     * Tests that getArray() includes all fields with id by default.
     */
    public function testGetArrayWithId()
    {
        $model = new GalleryItemModel();
        $model->setId(1);
        $model->setSort(2);
        $model->setParentId(3);
        $model->setType(0);
        $model->setTitle('Title');
        $model->setDesc('Desc');

        $array = $model->getArray();

        self::assertSame(1, $array['id']);
        self::assertSame(2, $array['sort']);
        self::assertSame(3, $array['parent_id']);
        self::assertSame(0, $array['type']);
        self::assertSame('Title', $array['title']);
        self::assertSame('Desc', $array['description']);
    }

    /**
     * Tests that getArray(false) excludes the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new GalleryItemModel();
        $model->setId(5);
        $model->setSort(1);
        $model->setParentId(0);
        $model->setType(1);
        $model->setTitle('NoId');
        $model->setDesc('NoId Desc');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(1, $array['sort']);
        self::assertSame(0, $array['parent_id']);
        self::assertSame(1, $array['type']);
        self::assertSame('NoId', $array['title']);
        self::assertSame('NoId Desc', $array['description']);
    }
}
