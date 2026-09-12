<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Downloads\Tests;

use Modules\Admin\Config\Config as AdminModuleConfig;
use Modules\Downloads\Mappers\File;
use Modules\Media\Config\Config as MediaModuleConfig;
use Modules\User\Config\Config as UserModuleConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Downloads\Config\Config as ModuleConfig;
use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Models\DownloadsItem;

class DownloadsTest extends DatabaseTestCase
{
    /**
     * @var DownloadsMapper
     */
    protected DownloadsMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new DownloadsMapper();
    }

    /**
     * Tests that getDownloadsItems() returns all items.
     */
    public function testGetDownloadsItems()
    {
        $items = $this->out->getDownloadsItems();

        self::assertNotNull($items);
        self::assertCount(3, $items);
        self::assertInstanceOf(DownloadsItem::class, $items[0]);
    }

    /**
     * Tests that getDownloadsItems() returns items ordered by sort ASC.
     */
    public function testGetDownloadsItemsOrderBySortAsc()
    {
        $items = $this->out->getDownloadsItems();

        self::assertEquals(1, $items[0]->getId());
        self::assertEquals(2, $items[1]->getId());
        self::assertEquals(3, $items[2]->getId());
    }

    /**
     * Tests that getDownloadsItems() populates model fields correctly.
     */
    public function testGetDownloadsItemsFields()
    {
        $items = $this->out->getDownloadsItems();

        $item = $items[0];
        self::assertEquals(1, $item->getId());
        self::assertEquals(1, $item->getType());
        self::assertEquals('Software', $item->getTitle());
        self::assertEquals('All software downloads', $item->getDesc());
        self::assertEquals(0, $item->getParentId());
    }

    /**
     * Tests that getDownloadsItems() populates access from downloads_access table.
     */
    public function testGetDownloadsItemsAccess()
    {
        $items = $this->out->getDownloadsItems();

        $item2 = $items[1];
        self::assertEquals(2, $item2->getId());
        self::assertNotEquals('', $item2->getAccess());

        $accessGroups = explode(',', $item2->getAccess());
        self::assertContains('1', $accessGroups);
        self::assertContains('2', $accessGroups);
    }

    /**
     * Tests that getDownloadsItems() returns null when table is empty.
     */
    public function testGetDownloadsItemsEmptyTable()
    {
        $this->db->delete('downloads_items')->execute();

        $items = $this->out->getDownloadsItems();

        self::assertNull($items);
    }

    /**
     * Tests that getDownloadsItemsByParent() returns only children of the given parent.
     */
    public function testGetDownloadsItemsByParent()
    {
        $items = $this->out->getDownloadsItemsByParent(1);

        self::assertNotNull($items);
        self::assertCount(2, $items);

        self::assertEquals(2, $items[0]->getId());
        self::assertEquals(3, $items[1]->getId());
    }

    /**
     * Tests that getDownloadsItemsByParent() returns null when no children exist.
     */
    public function testGetDownloadsItemsByParentNoResults()
    {
        $items = $this->out->getDownloadsItemsByParent(9999);

        self::assertNull($items);
    }

    /**
     * Tests that getDownloadsItemsByParent() populates fields correctly.
     */
    public function testGetDownloadsItemsByParentFields()
    {
        $items = $this->out->getDownloadsItemsByParent(1);

        $item = $items[0];
        self::assertEquals(2, $item->getId());
        self::assertEquals(1, $item->getType());
        self::assertEquals('Linux', $item->getTitle());
        self::assertEquals('Linux distributions', $item->getDesc());
        self::assertEquals(1, $item->getParentId());
    }

    /**
     * Tests that getDownloadsById() returns the correct item.
     */
    public function testGetDownloadsById()
    {
        $item = $this->out->getDownloadsById(2);

        self::assertNotNull($item);
        self::assertEquals(2, $item->getId());
        self::assertEquals(1, $item->getType());
        self::assertEquals('Linux', $item->getTitle());
        self::assertEquals('Linux distributions', $item->getDesc());
        self::assertEquals(1, $item->getParentId());
    }

    /**
     * Tests that getDownloadsById() returns null for a non-existent id.
     */
    public function testGetDownloadsByIdNotFound()
    {
        $item = $this->out->getDownloadsById(9999);

        self::assertNull($item);
    }

    /**
     * Tests that getDownloadsById() populates access correctly.
     */
    public function testGetDownloadsByIdAccess()
    {
        $item = $this->out->getDownloadsById(3);

        self::assertNotNull($item);
        self::assertNotEquals('', $item->getAccess());

        $accessGroups = explode(',', $item->getAccess());
        self::assertContains('1', $accessGroups);
    }

    /**
     * Tests that saveItem() inserts a new item when id is not set (null).
     */
    public function testSaveItemInsert()
    {
        $model = new DownloadsItem();
        $model->setTitle('New Category');
        $model->setDesc('New description');
        $model->setType(1);
        $model->setParentId(0);
        $model->setSort(3);

        $id = $this->out->saveItem($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getDownloadsById($id);
        self::assertNotNull($saved);
        self::assertEquals('New Category', $saved->getTitle());
        self::assertEquals('New description', $saved->getDesc());
        self::assertEquals(1, $saved->getType());
        self::assertEquals(0, $saved->getParentId());
    }

    /**
     * Tests that saveItem() updates an existing item when id is set.
     */
    public function testSaveItemUpdate()
    {
        $model = new DownloadsItem();
        $model->setId(1);
        $model->setTitle('Updated Category');
        $model->setDesc('Updated description');
        $model->setType(2);
        $model->setParentId(0);
        $model->setSort(0);

        $id = $this->out->saveItem($model);

        self::assertEquals(1, $id);

        $saved = $this->out->getDownloadsById(1);
        self::assertNotNull($saved);
        self::assertEquals('Updated Category', $saved->getTitle());
        self::assertEquals('Updated description', $saved->getDesc());
        self::assertEquals(2, $saved->getType());
    }

    /**
     * Tests that saveItem() update does not affect other items.
     */
    public function testSaveItemUpdatePreservesOthers()
    {
        $model = new DownloadsItem();
        $model->setId(1);
        $model->setTitle('Changed');
        $model->setDesc('Changed desc');
        $model->setType(1);
        $model->setParentId(0);
        $model->setSort(0);

        $this->out->saveItem($model);

        $item2 = $this->out->getDownloadsById(2);
        self::assertEquals('Linux', $item2->getTitle());
        self::assertEquals('Linux distributions', $item2->getDesc());
    }

    /**
     * Tests that saveItem() saves access rights alongside the item.
     */
    public function testSaveItemWithAccess()
    {
        $model = new DownloadsItem();
        $model->setTitle('Protected Item');
        $model->setDesc('Protected description');
        $model->setType(1);
        $model->setParentId(0);
        $model->setSort(5);
        $model->setAccess('1,2');

        $id = $this->out->saveItem($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getDownloadsById($id);
        self::assertNotNull($saved);
        self::assertNotEquals('', $saved->getAccess());

        $accessGroups = explode(',', $saved->getAccess());
        self::assertContains('1', $accessGroups);
        self::assertContains('2', $accessGroups);
    }

    /**
     * Tests that saveItem() with null access does not insert access rows.
     */
    public function testSaveItemWithNullAccess()
    {
        $model = new DownloadsItem();
        $model->setTitle('No Access Item');
        $model->setDesc('No access');
        $model->setType(1);
        $model->setParentId(0);
        $model->setSort(1);
        $model->setAccess('');

        $id = $this->out->saveItem($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getDownloadsById($id);
        self::assertNotNull($saved);
        self::assertEquals('', $saved->getAccess());
    }

    /**
     * Tests that deleteItem() removes an item.
     */
    public function testDeleteItem()
    {
        $model = new DownloadsItem();
        $model->setId(1);

        $this->out->deleteItem($model);

        self::assertNull($this->out->getDownloadsById(1));

        $remaining = $this->out->getDownloadsItems();
        self::assertCount(2, $remaining);
    }

    /**
     * Tests that deleteItem() cascade deletes associated files.
     */
    public function testDeleteItemCascadeDeletesFiles()
    {
        $model = new DownloadsItem();
        $model->setId(2);

        $this->out->deleteItem($model);

        $filesMapper = new File();
        $count = $filesMapper->getCountOfFilesByItemId(2);
        self::assertEquals(0, $count);
    }

    /**
     * Tests that deleteItem() does not throw for a non-existent item.
     */
    public function testDeleteItemNotFound()
    {
        $model = new DownloadsItem();
        $model->setId(9999);

        $this->out->deleteItem($model);

        $items = $this->out->getDownloadsItems();
        self::assertCount(3, $items);
    }

    /**
     * Tests that deleteItem() removes the last remaining item.
     */
    public function testDeleteLastItem()
    {
        $model1 = new DownloadsItem();
        $model1->setId(1);
        $this->out->deleteItem($model1);

        $model2 = new DownloadsItem();
        $model2->setId(2);
        $this->out->deleteItem($model2);

        $model3 = new DownloadsItem();
        $model3->setId(3);
        $this->out->deleteItem($model3);

        $items = $this->out->getDownloadsItems();
        self::assertNull($items);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userModuleConfig = new UserModuleConfig();
        $mediaModuleConfig = new MediaModuleConfig();
        $adminModuleConfig = new AdminModuleConfig();

        return $adminModuleConfig->getInstallSql() . $userModuleConfig->getInstallSql() . $mediaModuleConfig->getInstallSql() . $config->getInstallSql();
    }
}
