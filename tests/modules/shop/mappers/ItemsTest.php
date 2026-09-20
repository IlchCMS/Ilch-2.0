<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Models\Item as ItemsModel;

class ItemsTest extends DatabaseTestCase
{
    /**
     * @var Items
     */
    protected Items $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new Items();
    }

    /**
     * Tests that getShopItems() returns all items from the sample data.
     */
    public function testGetShopItems()
    {
        $items = $this->out->getShopItems();

        self::assertIsArray($items);
        self::assertCount(7, $items);
        self::assertArrayHasKey(1, $items);
        self::assertInstanceOf(ItemsModel::class, $items[1]);
    }

    /**
     * Tests that getShopItems() returns items ordered by name ASC.
     */
    public function testGetShopItemsOrdered()
    {
        $items = $this->out->getShopItems();

        self::assertEquals([5, 6, 4, 7, 3, 1, 2], array_keys($items));
    }

    /**
     * Tests that getShopItemById() returns the correct fields for the first sample item.
     */
    public function testGetShopItemByIdFields()
    {
        $item = $this->out->getShopItemById(1);

        self::assertInstanceOf(ItemsModel::class, $item);
        self::assertEquals(1, $item->getId());
        self::assertEquals(1, $item->getCatId());
        self::assertEquals('T-Shirt Totenkopf', $item->getName());
        self::assertEquals('0815nr1', $item->getItemnumber());
        self::assertEquals(14, $item->getStock());
        self::assertEquals('St&uuml;ck', $item->getUnitName());
        self::assertEquals(1, $item->getCordon());
        self::assertEquals('NEU', $item->getCordonText());
        self::assertEquals('green', $item->getCordonColor());
        self::assertEqualsWithDelta(25.00, (float)$item->getPrice(), 0.001);
        self::assertEquals(19, $item->getTax());
        self::assertEqualsWithDelta(0.00, (float)$item->getShippingCosts(), 0.001);
        self::assertEquals(5, $item->getShippingTime());
        self::assertEquals(1, $item->getStatus());
    }

    /**
     * Tests that getShopItemById() returns the correct fields for another sample item.
     */
    public function testGetShopItemByIdSecond()
    {
        $item = $this->out->getShopItemById(2);

        self::assertInstanceOf(ItemsModel::class, $item);
        self::assertEquals(2, $item->getId());
        self::assertEquals(1, $item->getCatId());
        self::assertEquals('T-Shirt Türkies', $item->getName());
        self::assertEquals(23, $item->getStock());
        self::assertEqualsWithDelta(15.00, (float)$item->getPrice(), 0.001);
        self::assertEquals(1, $item->getStatus());
    }

    /**
     * Tests that getShopItems() accepts a where array for status.
     */
    public function testGetShopItemsWhereStatus()
    {
        $active = $this->out->getShopItems(['items.status' => 1]);
        $inactive = $this->out->getShopItems(['items.status' => 0]);

        self::assertCount(6, $active);
        self::assertCount(1, $inactive);
        self::assertArrayHasKey(4, $inactive);
    }

    /**
     * Tests that getShopItems() accepts a where array for category id.
     */
    public function testGetShopItemsWhereCatId()
    {
        $items = $this->out->getShopItems(['items.cat_id' => 1]);

        self::assertCount(3, $items);
        self::assertArrayHasKey(1, $items);
        self::assertArrayHasKey(2, $items);
        self::assertArrayHasKey(3, $items);
    }

    /**
     * Tests that getShopItems() returns an empty array when no items exist.
     */
    public function testGetShopItemsEmpty()
    {
        foreach ([1, 2, 3, 4, 5, 6, 7] as $id) {
            $this->out->delete($id);
        }

        $items = $this->out->getShopItems();

        self::assertIsArray($items);
        self::assertCount(0, $items);
    }

    /**
     * Tests that getShopItemById() returns null for a non-existent id.
     */
    public function testGetShopItemByIdNotFound()
    {
        $item = $this->out->getShopItemById(9999);

        self::assertNull($item);
    }

    /**
     * Tests that getShopItemsByIds() returns only the requested items.
     */
    public function testGetShopItemsByIds()
    {
        $items = $this->out->getShopItemsByIds([1, 5, 7]);

        self::assertCount(3, $items);
        self::assertArrayHasKey(1, $items);
        self::assertArrayHasKey(5, $items);
        self::assertArrayHasKey(7, $items);
    }

    /**
     * Tests that getCountOfItems() returns the number of active items by default.
     */
    public function testGetCountOfItems()
    {
        self::assertEquals(6, $this->out->getCountOfItems());
    }

    /**
     * Tests that getCountOfItems() accepts a status filter.
     */
    public function testGetCountOfItemsWithStatus()
    {
        self::assertEquals(6, $this->out->getCountOfItems());
        self::assertEquals(1, $this->out->getCountOfItems(0));
        self::assertEquals(0, $this->out->getCountOfItems(99));
    }

    /**
     * Tests that getCountOfItemsPerCategory() returns counts for multiple categories.
     */
    public function testGetCountOfItemsPerCategory()
    {
        $counts = $this->out->getCountOfItemsPerCategory([1, 2, 3]);

        self::assertEquals([
            1 => 3,
            2 => 2,
            3 => 1,
        ], $counts);
    }

    /**
     * Tests that getCountOfItemsPerCategory() returns only the requested category.
     */
    public function testGetCountOfItemsPerCategorySingle()
    {
        $counts = $this->out->getCountOfItemsPerCategory([2]);

        self::assertEquals([
            2 => 2,
        ], $counts);
    }

    /**
     * Tests that getCountOfItemsPerCategory() respects the status filter.
     */
    public function testGetCountOfItemsPerCategoryWithStatus()
    {
        $counts = $this->out->getCountOfItemsPerCategory([1, 2, 3], 0);

        self::assertEquals([
            2 => 1,
        ], $counts);
    }

    /**
     * Tests that getCountOfItemsPerCategory() returns an empty array for empty category ids.
     */
    public function testGetCountOfItemsPerCategoryEmpty()
    {
        $counts = $this->out->getCountOfItemsPerCategory();

        self::assertIsArray($counts);
        self::assertCount(0, $counts);
    }

    /**
     * Tests inserting a new item via save().
     */
    public function testSaveInsert()
    {
        $model = new ItemsModel();
        $model->setId(0)
            ->setCatId(1)
            ->setName('Test Item')
            ->setCode('')
            ->setItemnumber('testnr1')
            ->setStock(10)
            ->setUnitName('Piece')
            ->setCordon(0)
            ->setCordonText('')
            ->setCordonColor(null)
            ->setPrice('10.00')
            ->setTax(19)
            ->setShippingCosts('0.00')
            ->setShippingTime('5')
            ->setImage('')
            ->setImage1('')
            ->setImage2('')
            ->setImage3('')
            ->setInfo('')
            ->setDesc('')
            ->setStatus(1);

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(7, $newId);

        $item = $this->out->getShopItemById($newId);

        self::assertInstanceOf(ItemsModel::class, $item);
        self::assertEquals($newId, $item->getId());
        self::assertEquals(1, $item->getCatId());
        self::assertEquals('Test Item', $item->getName());
        self::assertMatchesRegularExpression('/^testitem_\d+$/', $item->getCode());
        self::assertEquals('testnr1', $item->getItemnumber());
        self::assertEquals(10, $item->getStock());
        self::assertEquals('Piece', $item->getUnitName());
        self::assertEqualsWithDelta(10.00, (float)$item->getPrice(), 0.001);
        self::assertEquals(1, $item->getStatus());

        self::assertCount(8, $this->out->getShopItems());
    }

    /**
     * Tests that save() with id 0 does not affect existing items.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getShopItems();
        self::assertCount(7, $before);

        $model = new ItemsModel();
        $model->setId(0)
            ->setCatId(1)
            ->setName('New Entry')
            ->setCode('')
            ->setItemnumber('newnr')
            ->setStock(1)
            ->setUnitName('Piece')
            ->setCordon(0)
            ->setCordonText('')
            ->setCordonColor(null)
            ->setPrice('1.00')
            ->setTax(19)
            ->setShippingCosts('0.00')
            ->setShippingTime('5')
            ->setImage('')
            ->setImage1('')
            ->setImage2('')
            ->setImage3('')
            ->setInfo('')
            ->setDesc('')
            ->setStatus(1);

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(7, $newId);

        $after = $this->out->getShopItems();
        self::assertCount(8, $after);

        $existing = $this->out->getShopItemById(1);
        self::assertEquals('T-Shirt Totenkopf', $existing->getName());
    }

    /**
     * Tests updating an existing item via save().
     */
    public function testSaveUpdate()
    {
        $model = new ItemsModel();
        $model->setId(1)
            ->setCatId(1)
            ->setName('Updated Item')
            ->setCode('')
            ->setItemnumber('0815nr1')
            ->setStock(99)
            ->setUnitName('Piece')
            ->setCordon(0)
            ->setCordonText('')
            ->setCordonColor(null)
            ->setPrice('99.99')
            ->setTax(19)
            ->setShippingCosts('0.00')
            ->setShippingTime('5')
            ->setImage('')
            ->setImage1('')
            ->setImage2('')
            ->setImage3('')
            ->setInfo('')
            ->setDesc('')
            ->setStatus(1);

        $result = $this->out->save($model);

        self::assertSame(1, $result);

        $item = $this->out->getShopItemById(1);

        self::assertInstanceOf(ItemsModel::class, $item);
        self::assertEquals(1, $item->getId());
        self::assertEquals('Updated Item', $item->getName());
        self::assertMatchesRegularExpression('/^updateditem_\d+$/', $item->getCode());
        self::assertEquals(99, $item->getStock());
        self::assertEqualsWithDelta(99.99, (float)$item->getPrice(), 0.001);
    }

    /**
     * Tests that update does not affect other items.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new ItemsModel();
        $model->setId(1)
            ->setCatId(1)
            ->setName('Changed Item')
            ->setCode('')
            ->setItemnumber('0815nr1')
            ->setStock(5)
            ->setUnitName('Piece')
            ->setCordon(0)
            ->setCordonText('')
            ->setCordonColor(null)
            ->setPrice('5.00')
            ->setTax(19)
            ->setShippingCosts('0.00')
            ->setShippingTime('5')
            ->setImage('')
            ->setImage1('')
            ->setImage2('')
            ->setImage3('')
            ->setInfo('')
            ->setDesc('')
            ->setStatus(1);

        $this->out->save($model);

        $other = $this->out->getShopItemById(2);

        self::assertInstanceOf(ItemsModel::class, $other);
        self::assertEquals('T-Shirt Türkies', $other->getName());
        self::assertEquals(23, $other->getStock());
    }

    /**
     * Tests that updateStock() sets the stock to a specific quantity.
     */
    public function testUpdateStock()
    {
        $updatedId = $this->out->updateStock(1, 100);

        self::assertSame(1, $updatedId);

        $item = $this->out->getShopItemById(1);

        self::assertEquals(100, $item->getStock());
    }

    /**
     * Tests that updateStock() returns null for a non-existent item.
     */
    public function testUpdateStockNotFound()
    {
        self::assertNull($this->out->updateStock(9999, 100));
    }

    /**
     * Tests that updateStock() does not affect other items.
     */
    public function testUpdateStockDoesNotAffectOthers()
    {
        $this->out->updateStock(1, 100);

        $other = $this->out->getShopItemById(2);

        self::assertEquals(23, $other->getStock());
    }

    /**
     * Tests that addStock() increases the stock.
     */
    public function testAddStock()
    {
        $newStock = $this->out->addStock(1, 5);

        self::assertSame(19, $newStock);

        $item = $this->out->getShopItemById(1);

        self::assertEquals(19, $item->getStock());
    }

    /**
     * Tests that addStock() returns null for a non-existent item.
     */
    public function testAddStockNotFound()
    {
        self::assertNull($this->out->addStock(9999, 5));
    }

    /**
     * Tests that removeStock() decreases the stock.
     */
    public function testRemoveStock()
    {
        $newStock = $this->out->removeStock(1, 4);

        self::assertSame(10, $newStock);

        $item = $this->out->getShopItemById(1);

        self::assertEquals(10, $item->getStock());
    }

    /**
     * Tests that removeStock() returns null for a non-existent item.
     */
    public function testRemoveStockNotFound()
    {
        self::assertNull($this->out->removeStock(9999, 4));
    }

    /**
     * Tests that changeStatus() changes the status of multiple items.
     */
    public function testChangeStatusMultiple()
    {
        $affected = $this->out->changeStatus([1, 2], false);

        self::assertSame(2, $affected);

        $item1 = $this->out->getShopItemById(1);
        $item2 = $this->out->getShopItemById(2);
        $item3 = $this->out->getShopItemById(3);

        self::assertEquals(0, $item1->getStatus());
        self::assertEquals(0, $item2->getStatus());
        self::assertEquals(1, $item3->getStatus());
    }

    /**
     * Tests that changeStatus() changes the status of a single item.
     */
    public function testChangeStatusSingle()
    {
        $affected = $this->out->changeStatus([4], true);

        self::assertSame(1, $affected);

        $item = $this->out->getShopItemById(4);

        self::assertEquals(1, $item->getStatus());
    }

    /**
     * Tests that delete() removes an item.
     */
    public function testDelete()
    {
        $deleted = $this->out->delete(1);

        self::assertTrue($deleted);
        self::assertNull($this->out->getShopItemById(1));

        $items = $this->out->getShopItems();
        self::assertCount(6, $items);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $deleted = $this->out->delete(9999);

        self::assertFalse($deleted);

        $items = $this->out->getShopItems();
        self::assertCount(7, $items);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $coreSql = 'CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                    `moduleKey` VARCHAR(255) NOT NULL,
                    `type` VARCHAR(255) NOT NULL,
                    `desc` VARCHAR(255) NOT NULL,
                    `text` TEXT NOT NULL,
                    `locale` VARCHAR(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_groups` (`id`, `name`) VALUES
                    (1, "Admin"),
                    (2, "Member"),
                    (3, "Guest");';

        $config = new ModuleConfig();

        return $coreSql . "\n" . $config->getInstallSql();
    }
}
