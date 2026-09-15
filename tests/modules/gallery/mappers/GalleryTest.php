<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Gallery\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Gallery\Config\Config as ModuleConfig;
use Modules\Media\Config\Config as MediaConfig;
use Modules\Gallery\Mappers\Gallery as GalleryMapper;
use Modules\Gallery\Models\GalleryItem as GalleryItemModel;
use Modules\Gallery\Mappers\Image as ImageMapper;

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
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new GalleryMapper();
    }

    /**
     * Tests that getGalleryItems() returns all items.
     */
    public function testGetGalleryItems()
    {
        $items = $this->out->getGalleryItems();

        self::assertNotNull($items);
        self::assertCount(3, $items);
        self::assertInstanceOf(GalleryItemModel::class, $items[0]);
    }

    /**
     * Tests that getGalleryItems() returns correct fields for the first item.
     */
    public function testGetGalleryItemsFields()
    {
        $items = $this->out->getGalleryItems();

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(0, $items[0]->getParentId());
        self::assertEquals(1, $items[0]->getType());
        self::assertEquals('Main Gallery', $items[0]->getTitle());
        self::assertEquals('Root gallery', $items[0]->getDesc());
    }

    /**
     * Tests that getGalleryItems() returns correct fields for the second item.
     */
    public function testGetGalleryItemsSecond()
    {
        $items = $this->out->getGalleryItems();

        self::assertEquals(2, $items[1]->getId());
        self::assertEquals(1, $items[1]->getParentId());
        self::assertEquals(0, $items[1]->getType());
        self::assertEquals('Photos', $items[1]->getTitle());
        self::assertEquals('Photo collection', $items[1]->getDesc());
    }

    /**
     * Tests that getGalleryItems() returns correct fields for the third item.
     */
    public function testGetGalleryItemsThird()
    {
        $items = $this->out->getGalleryItems();

        self::assertEquals(3, $items[2]->getId());
        self::assertEquals(1, $items[2]->getParentId());
        self::assertEquals(0, $items[2]->getType());
        self::assertEquals('Videos', $items[2]->getTitle());
        self::assertEquals('Video collection', $items[2]->getDesc());
    }

    /**
     * Tests that getGalleryItems() returns null when no items exist.
     */
    public function testGetGalleryItemsEmpty()
    {
        $this->out->deleteItem(1);
        $this->out->deleteItem(2);
        $this->out->deleteItem(3);

        $items = $this->out->getGalleryItems();

        self::assertNull($items);
    }

    /**
     * Tests that getGalleryItems() defaults to sort ASC ordering.
     */
    public function testGetGalleryItemsOrderSortAsc()
    {
        $items = $this->out->getGalleryItems();

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
        self::assertEquals(3, $items[2]->getId());
    }

    /**
     * Tests that getEntriesBy() applies custom ordering.
     */
    public function testGetEntriesByOrderDesc()
    {
        $items = $this->out->getEntriesBy([], ['id' => 'DESC']);

        self::assertNotNull($items);
        self::assertCount(3, $items);
        self::assertEquals(3, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
        self::assertEquals(1, $items[2]->getId());
    }

    /**
     * Tests that getEntriesBy() filters by where clause.
     */
    public function testGetEntriesByWithWhere()
    {
        $items = $this->out->getEntriesBy(['type' => 1]);

        self::assertNotNull($items);
        self::assertCount(1, $items);
        self::assertEquals(1, $items[0]->getId());
        self::assertEquals('Main Gallery', $items[0]->getTitle());
    }

    /**
     * Tests that getEntriesBy() returns null when no rows match.
     */
    public function testGetEntriesByWhereNoMatch()
    {
        $items = $this->out->getEntriesBy(['id' => 9999]);

        self::assertNull($items);
    }

    /**
     * Tests that getGalleryItemsByParent() returns children of item 1.
     */
    public function testGetGalleryItemsByParent()
    {
        $items = $this->out->getGalleryItemsByParent(1);

        self::assertNotNull($items);
        self::assertCount(2, $items);
        self::assertEquals(2, $items[0]->getId());
        self::assertEquals(3, $items[1]->getId());
    }

    /**
     * Tests that getGalleryItemsByParent(0) returns root items.
     */
    public function testGetGalleryItemsByParentRoot()
    {
        $items = $this->out->getGalleryItemsByParent(0);

        self::assertNotNull($items);
        self::assertCount(1, $items);
        self::assertEquals(1, $items[0]->getId());
        self::assertEquals('Main Gallery', $items[0]->getTitle());
    }

    /**
     * Tests that getGalleryItemsByParent() returns null for no children.
     */
    public function testGetGalleryItemsByParentNotFound()
    {
        $items = $this->out->getGalleryItemsByParent(9999);

        self::assertNull($items);
    }

    /**
     * Tests that getGalleryCatItem() returns items of given type.
     */
    public function testGetGalleryCatItem()
    {
        $items = $this->out->getGalleryCatItem(1);

        self::assertIsArray($items);
        self::assertCount(1, $items);
        self::assertEquals(1, $items[0]->getId());
        self::assertEquals('Main Gallery', $items[0]->getTitle());
    }

    /**
     * Tests that getGalleryCatItem() returns items of type 0.
     */
    public function testGetGalleryCatItemTypeZero()
    {
        $items = $this->out->getGalleryCatItem(0);

        self::assertIsArray($items);
        self::assertCount(2, $items);
        self::assertEquals(2, $items[0]->getId());
        self::assertEquals(3, $items[1]->getId());
    }

    /**
     * Tests that getGalleryCatItem() returns empty array for no matches.
     */
    public function testGetGalleryCatItemEmpty()
    {
        $items = $this->out->getGalleryCatItem(9999);

        self::assertIsArray($items);
        self::assertCount(0, $items);
    }

    /**
     * Tests that getGalleryById() returns the correct item.
     */
    public function testGetGalleryById()
    {
        $item = $this->out->getGalleryById(1);

        self::assertNotNull($item);
        self::assertEquals(1, $item->getId());
        self::assertEquals(0, $item->getParentId());
        self::assertEquals(1, $item->getType());
        self::assertEquals('Main Gallery', $item->getTitle());
        self::assertEquals('Root gallery', $item->getDesc());
    }

    /**
     * Tests that getGalleryById() returns a different item.
     */
    public function testGetGalleryByIdSecond()
    {
        $item = $this->out->getGalleryById(3);

        self::assertNotNull($item);
        self::assertEquals(3, $item->getId());
        self::assertEquals('Videos', $item->getTitle());
    }

    /**
     * Tests that getGalleryById() returns null for a non-existent id.
     */
    public function testGetGalleryByIdNotFound()
    {
        $item = $this->out->getGalleryById(9999);

        self::assertNull($item);
    }

    /**
     * Tests that save() inserts a new item when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new GalleryItemModel();
        $model->setId(0);
        $model->setSort(3);
        $model->setParentId(1);
        $model->setType(0);
        $model->setTitle('Music');
        $model->setDesc('Music collection');

        $newId = $this->out->save($model);

        self::assertGreaterThan(3, $newId);

        $item = $this->out->getGalleryById($newId);
        self::assertNotNull($item);
        self::assertEquals('Music', $item->getTitle());
        self::assertEquals('Music collection', $item->getDesc());
        self::assertEquals(1, $item->getParentId());
        self::assertEquals(0, $item->getType());
    }

    /**
     * Tests that save() updates an existing item when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new GalleryItemModel();
        $model->setId(1);
        $model->setSort(0);
        $model->setParentId(0);
        $model->setType(1);
        $model->setTitle('Updated Gallery');
        $model->setDesc('Updated description');

        $returnedId = $this->out->save($model);

        self::assertEquals(1, $returnedId);

        $item = $this->out->getGalleryById(1);
        self::assertNotNull($item);
        self::assertEquals(1, $item->getId());
        self::assertEquals('Updated Gallery', $item->getTitle());
        self::assertEquals('Updated description', $item->getDesc());
    }

    /**
     * Tests that save() update does not affect other items.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new GalleryItemModel();
        $model->setId(1);
        $model->setSort(0);
        $model->setParentId(0);
        $model->setType(1);
        $model->setTitle('Changed');
        $model->setDesc('Changed');

        $this->out->save($model);

        $other = $this->out->getGalleryById(2);
        self::assertNotNull($other);
        self::assertEquals('Photos', $other->getTitle());
        self::assertEquals('Photo collection', $other->getDesc());
    }

    /**
     * Tests that saveItem() delegates to save().
     */
    public function testSaveItem()
    {
        $model = new GalleryItemModel();
        $model->setId(0);
        $model->setSort(5);
        $model->setParentId(0);
        $model->setType(1);
        $model->setTitle('Via SaveItem');
        $model->setDesc('Test saveItem');

        $newId = $this->out->saveItem($model);

        self::assertGreaterThan(3, $newId);

        $item = $this->out->getGalleryById($newId);
        self::assertNotNull($item);
        self::assertEquals('Via SaveItem', $item->getTitle());
    }

    /**
     * Tests that sort() updates sort position and parent_id.
     */
    public function testSort()
    {
        $result = $this->out->sort(2, 5, 0);

        self::assertTrue($result);

        $item = $this->out->getGalleryById(2);
        self::assertNotNull($item);
        self::assertEquals(0, $item->getParentId());
    }

    /**
     * Tests that sort() accepts a GalleryItem instance.
     */
    public function testSortWithModel()
    {
        $model = new GalleryItemModel();
        $model->setId(3);

        $result = $this->out->sort($model, 10, 0);

        self::assertTrue($result);
    }

    /**
     * Tests that deleteItem() removes an item.
     */
    public function testDeleteItem()
    {
        $result = $this->out->deleteItem(1);

        self::assertTrue($result);
        self::assertNull($this->out->getGalleryById(1));

        $items = $this->out->getGalleryItems();
        self::assertCount(2, $items);
        self::assertEquals(2, $items[0]->getId());
        self::assertEquals(3, $items[1]->getId());
    }

    /**
     * Tests that deleteItem() accepts a GalleryItem instance.
     */
    public function testDeleteItemWithModel()
    {
        $model = new GalleryItemModel();
        $model->setId(2);

        $result = $this->out->deleteItem($model);

        self::assertTrue($result);
        self::assertNull($this->out->getGalleryById(2));
    }

    /**
     * Tests that deleteItem() on a non-existent id does not remove other items.
     */
    public function testDeleteItemNotFound()
    {
        $this->out->deleteItem(9999);

        $items = $this->out->getGalleryItems();
        self::assertCount(3, $items);
    }

    /**
     * Tests that deleting a parent cascades to child images (FK CASCADE).
     */
    public function testDeleteItemCascadesImages()
    {
        $imageMapper = new ImageMapper();
        $imagesBefore = $imageMapper->getImageByGalleryId(1);
        self::assertCount(2, $imagesBefore);

        $this->out->deleteItem(1);

        $imagesAfter = $imageMapper->getImageByGalleryId(1);
        self::assertCount(0, $imagesAfter);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $mediaConfig = new MediaConfig();

        return $mediaConfig->getInstallSql() . $config->getInstallSql();
    }
}
