<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\Gallery as GalleryMapper;
use Modules\User\Models\GalleryItem as GalleryItemModel;

class GalleryTest extends DatabaseTestCase
{
    /**
     * @var GalleryMapper
     */
    protected Gallery $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database_users_gallery.yml');
        $this->out = new GalleryMapper();
    }

    /**
     * Tests that getGalleryItemsByParent() returns the root items of the first user.
     */
    public function testGetGalleryItemsByParent()
    {
        $items = $this->out->getGalleryItemsByParent(1, 0);

        self::assertNotNull($items);
        self::assertCount(2, $items);
        self::assertInstanceOf(GalleryItemModel::class, $items[0]);
    }

    /**
     * Tests that getGalleryItemsByParent() returns correct fields for the root items.
     */
    public function testGetGalleryItemsByParentFields()
    {
        $items = $this->out->getGalleryItemsByParent(1, 0);

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(1, $items[0]->getUserId());
        self::assertEquals(1, $items[0]->getType());
        self::assertEquals('Album 1', $items[0]->getTitle());
        self::assertEquals('First album', $items[0]->getDesc());
        self::assertEquals(1, $items[0]->getSort());
        self::assertEquals(0, $items[0]->getParentId());

        self::assertEquals(2, $items[1]->getId());
        self::assertEquals('Album 2', $items[1]->getTitle());
        self::assertEquals(2, $items[1]->getSort());
    }

    /**
     * Tests that getGalleryItemsByParent() returns the child items of a parent.
     */
    public function testGetGalleryItemsByParentChild()
    {
        $items = $this->out->getGalleryItemsByParent(1, 1);

        self::assertNotNull($items);
        self::assertCount(1, $items);
        self::assertEquals(3, $items[0]->getId());
        self::assertEquals('Photo 1', $items[0]->getTitle());
        self::assertEquals(1, $items[0]->getParentId());
        self::assertEquals(3, $items[0]->getSort());
    }

    /**
     * Tests that getGalleryItemsByParent() returns items ordered by sort ASC.
     */
    public function testGetGalleryItemsByParentOrderBySort()
    {
        $items = $this->out->getGalleryItemsByParent(1, 0);

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
    }

    /**
     * Tests that getGalleryItemsByParent() filters by user id as well.
     */
    public function testGetGalleryItemsByParentOtherUser()
    {
        $items = $this->out->getGalleryItemsByParent(2, 4);

        self::assertNotNull($items);
        self::assertCount(1, $items);
        self::assertEquals(5, $items[0]->getId());
        self::assertEquals('Photo 2', $items[0]->getTitle());
    }

    /**
     * Tests that getGalleryItemsByParent() returns null when no items match.
     */
    public function testGetGalleryItemsByParentNotFound()
    {
        self::assertNull($this->out->getGalleryItemsByParent(1, 4));
        self::assertNull($this->out->getGalleryItemsByParent(2, 1));
        self::assertNull($this->out->getGalleryItemsByParent(99, 0));
    }

    /**
     * Tests that getGalleryCatItem() returns all items of a given type.
     */
    public function testGetGalleryCatItemAlbums()
    {
        $items = $this->out->getGalleryCatItem(1);

        self::assertNotNull($items);
        self::assertCount(3, $items);
        self::assertInstanceOf(GalleryItemModel::class, $items[0]);

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(1, $items[0]->getUserId());
        self::assertEquals('Album 1', $items[0]->getTitle());
        self::assertEquals(0, $items[0]->getParentId());
    }

    /**
     * Tests that getGalleryCatItem() spans users and is ordered by sort ASC.
     */
    public function testGetGalleryCatItemPhotosOrdered()
    {
        $items = $this->out->getGalleryCatItem(2);

        self::assertNotNull($items);
        self::assertCount(2, $items);
        self::assertEquals(3, $items[0]->getId());
        self::assertEquals(5, $items[1]->getId());
    }

    /**
     * Tests that getGalleryCatItem() returns null for a type without items.
     */
    public function testGetGalleryCatItemNotFound()
    {
        self::assertNull($this->out->getGalleryCatItem(3));
    }

    /**
     * Tests that getGalleryById() returns the correct item.
     */
    public function testGetGalleryById()
    {
        $item = $this->out->getGalleryById(4);

        self::assertNotNull($item);
        self::assertEquals(4, $item->getId());
        self::assertEquals(2, $item->getUserId());
        self::assertEquals(1, $item->getType());
        self::assertEquals('Album 3', $item->getTitle());
        self::assertEquals('Third album', $item->getDesc());
        self::assertEquals(4, $item->getSort());
        self::assertEquals(0, $item->getParentId());
    }

    /**
     * Tests that getGalleryById() returns null for a non-existent id.
     */
    public function testGetGalleryByIdNotFound()
    {
        self::assertNull($this->out->getGalleryById(9999));
    }

    /**
     * Tests that getGalleryItems() returns all items of the user ordered by sort ASC.
     */
    public function testGetGalleryItems()
    {
        $items = $this->out->getGalleryItems(1);

        self::assertNotNull($items);
        self::assertCount(3, $items);
        self::assertInstanceOf(GalleryItemModel::class, $items[0]);

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
        self::assertEquals(3, $items[2]->getId());
    }

    /**
     * Tests that getGalleryItems() returns the items of the second user.
     */
    public function testGetGalleryItemsSecondUser()
    {
        $items = $this->out->getGalleryItems(2);

        self::assertNotNull($items);
        self::assertCount(2, $items);
        self::assertEquals(4, $items[0]->getId());
        self::assertEquals(5, $items[1]->getId());
    }

    /**
     * Tests that getGalleryItems() returns null when the user has no items.
     */
    public function testGetGalleryItemsEmpty()
    {
        self::assertNull($this->out->getGalleryItems(3));
    }

    /**
     * Tests that getCountGalleryByUser() only counts items with parent_id 0.
     */
    public function testGetCountGalleryByUser()
    {
        self::assertEquals(2, $this->out->getCountGalleryByUser(1));
        self::assertEquals(1, $this->out->getCountGalleryByUser(2));
        self::assertEquals(0, $this->out->getCountGalleryByUser(3));
    }

    /**
     * Tests inserting a new gallery item via saveItem().
     */
    public function testSaveItemInsert()
    {
        $before = $this->out->getGalleryItems(1);
        self::assertCount(3, $before);

        $model = new GalleryItemModel();
        $model->setId(0)
            ->setUserId(1)
            ->setType(1)
            ->setTitle('New Album')
            ->setDesc('New album description')
            ->setParentId(0)
            ->setSort(10);

        $itemId = $this->out->saveItem($model);
        self::assertGreaterThan(5, $itemId);

        $after = $this->out->getGalleryItems(1);
        self::assertCount(4, $after);

        // The new item has the highest sort value and must be the last one.
        self::assertEquals($itemId, $after[3]->getId());
        self::assertEquals(1, $after[3]->getUserId());
        self::assertEquals('New Album', $after[3]->getTitle());
        self::assertEquals('New album description', $after[3]->getDesc());
        self::assertEquals(0, $after[3]->getParentId());
        self::assertEquals(10, $after[3]->getSort());

        // Items of the other user must be untouched.
        self::assertCount(2, $this->out->getGalleryItems(2));
    }

    /**
     * Tests that saveItem() updates an existing item and keeps untouched fields.
     */
    public function testSaveItemUpdate()
    {
        $model = new GalleryItemModel();
        $model->setId(1)
            ->setTitle('Updated Album 1');

        $itemId = $this->out->saveItem($model);
        self::assertEquals(1, $itemId);

        $item = $this->out->getGalleryById(1);
        self::assertNotNull($item);
        self::assertEquals('Updated Album 1', $item->getTitle());

        // Fields that were null on the model must keep their old database values.
        self::assertEquals('First album', $item->getDesc());
        self::assertEquals(1, $item->getType());
        self::assertEquals(0, $item->getParentId());
        self::assertEquals(1, $item->getUserId());
        self::assertEquals(1, $item->getSort());
    }

    /**
     * Tests that saveItem() update does not affect other items.
     */
    public function testSaveItemUpdateDoesNotAffectOthers()
    {
        $model = new GalleryItemModel();
        $model->setId(1)
            ->setTitle('Changed Title')
            ->setDesc('Changed description');

        $this->out->saveItem($model);

        $other = $this->out->getGalleryById(2);
        self::assertNotNull($other);
        self::assertEquals('Album 2', $other->getTitle());
        self::assertEquals('Second album', $other->getDesc());
    }

    /**
     * Tests that updating sort/type changes ordering and category lists.
     */
    public function testSaveItemUpdateChangesSortOrder()
    {
        $model = new GalleryItemModel();
        $model->setId(4)
            ->setUserId(2)
            ->setTitle('Renamed Album 3')
            ->setDesc('Renamed description')
            ->setParentId(0)
            ->setType(2)
            ->setSort(6);

        $this->out->saveItem($model);

        $item = $this->out->getGalleryById(4);
        self::assertNotNull($item);
        self::assertEquals('Renamed Album 3', $item->getTitle());
        self::assertEquals('Renamed description', $item->getDesc());
        self::assertEquals(2, $item->getType());
        self::assertEquals(6, $item->getSort());

        // With sort 6 the item now comes after item 5 (sort 5).
        $items = $this->out->getGalleryItems(2);
        self::assertCount(2, $items);
        self::assertEquals(5, $items[0]->getId());
        self::assertEquals(4, $items[1]->getId());

        // The type change must be reflected in the category lists.
        self::assertCount(2, $this->out->getGalleryCatItem(1));
        self::assertCount(3, $this->out->getGalleryCatItem(2));
    }

    /**
     * Tests that saveItem() with id 0 performs an insert, not an update.
     */
    public function testSaveItemZeroIdInserts()
    {
        self::assertCount(3, $this->out->getGalleryItems(1));

        $model = new GalleryItemModel();
        $model->setId(0)
            ->setUserId(1)
            ->setType(2)
            ->setTitle('New Entry')
            ->setDesc('New entry description')
            ->setParentId(0)
            ->setSort(20);

        $this->out->saveItem($model);

        $after = $this->out->getGalleryItems(1);
        self::assertCount(4, $after);

        // Original items untouched.
        self::assertEquals('Album 1', $after[0]->getTitle());
        self::assertEquals('Album 2', $after[1]->getTitle());
        self::assertEquals('Photo 1', $after[2]->getTitle());
    }

    /**
     * Tests that saveItem() without a set id (null) performs an insert.
     */
    public function testSaveItemNullIdInserts()
    {
        self::assertCount(2, $this->out->getGalleryItems(2));

        $model = new GalleryItemModel();
        $model->setUserId(2)
            ->setType(2)
            ->setTitle('No Id Set')
            ->setDesc('Insert without id')
            ->setParentId(4)
            ->setSort(7);

        $itemId = $this->out->saveItem($model);
        self::assertGreaterThan(5, $itemId);

        $item = $this->out->getGalleryById($itemId);
        self::assertNotNull($item);
        self::assertEquals('No Id Set', $item->getTitle());
        self::assertEquals(2, $item->getUserId());
        self::assertEquals(4, $item->getParentId());
    }

    /**
     * Tests that saveItem() inserts an item without a description.
     * The mapper falls back to an empty string, so the insert succeeds even on
     * strict-mode MySQL where `description` is NOT NULL without a default.
     */
    public function testSaveItemInsertWithoutDescription()
    {
        $model = new GalleryItemModel();
        $model->setId(0)
            ->setUserId(2)
            ->setType(2)
            ->setTitle('No Description')
            ->setParentId(0)
            ->setSort(30);

        $itemId = $this->out->saveItem($model);

        $item = $this->out->getGalleryById($itemId);
        self::assertNotNull($item);
        self::assertEquals('No Description', $item->getTitle());
        self::assertEquals('', $item->getDesc());
    }

    /**
     * Tests that saveItem() rejects a new item without a title.
     */
    public function testSaveItemInsertRequiresTitle()
    {
        $model = new GalleryItemModel();
        $model->setUserId(1)
            ->setType(1)
            ->setSort(40);

        $this->expectException(\InvalidArgumentException::class);
        $this->out->saveItem($model);
    }

    /**
     * Tests that saveItem() rejects a new item without a type.
     */
    public function testSaveItemInsertRequiresType()
    {
        $model = new GalleryItemModel();
        $model->setUserId(1)
            ->setTitle('Missing Type')
            ->setSort(41);

        $this->expectException(\InvalidArgumentException::class);
        $this->out->saveItem($model);
    }

    /**
     * Tests that deleteItem() removes an item.
     */
    public function testDeleteItem()
    {
        $model = new GalleryItemModel();
        $model->setId(1);

        $this->out->deleteItem($model);

        self::assertNull($this->out->getGalleryById(1));

        $items = $this->out->getGalleryItems(1);
        self::assertCount(2, $items);
        self::assertEquals(2, $items[0]->getId());
        self::assertEquals(3, $items[1]->getId());
    }

    /**
     * Tests that deleteItem() on a non-existent id does not throw.
     */
    public function testDeleteItemNotFound()
    {
        $model = new GalleryItemModel();
        $model->setId(9999);

        $this->out->deleteItem($model);

        self::assertCount(3, $this->out->getGalleryItems(1));
        self::assertCount(2, $this->out->getGalleryItems(2));
    }

    /**
     * Returns database schema sql statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}
