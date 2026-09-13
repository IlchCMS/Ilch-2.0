<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Events\Tests;

use Modules\Comment\Config\Config as CommentModuleConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Events\Config\Config as ModuleConfig;
use Modules\Events\Mappers\Currency as CurrencyMapper;
use Modules\Events\Models\Currency as CurrencyModel;

class CurrencyTest extends DatabaseTestCase
{
    /**
     * @var CurrencyMapper
     */
    protected CurrencyMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new CurrencyMapper();
    }

    /**
     * Tests that checkDB() returns true when the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all entries.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(3, $entries);
        self::assertInstanceOf(CurrencyModel::class, $entries[0]);
    }

    /**
     * Tests that getEntriesBy() returns entries ordered by name ASC (default).
     */
    public function testGetEntriesByOrderAsc()
    {
        $entries = $this->out->getEntriesBy();

        self::assertEquals('EUR (€)', $entries[0]->getName());
        self::assertEquals('GBP (£)', $entries[1]->getName());
        self::assertEquals('USD ($)', $entries[2]->getName());
    }

    /**
     * Tests that getEntriesBy() with explicit DESC order returns descending order.
     */
    public function testGetEntriesByOrderDesc()
    {
        $entries = $this->out->getEntriesBy([], ['name' => 'DESC']);

        self::assertEquals('USD ($)', $entries[0]->getName());
        self::assertEquals('GBP (£)', $entries[1]->getName());
        self::assertEquals('EUR (€)', $entries[2]->getName());
    }

    /**
     * Tests that getEntriesBy() populates model fields correctly.
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy(['id' => 1]);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('EUR (€)', $entries[0]->getName());
    }

    /**
     * Tests that getEntriesBy() with WHERE clause filters correctly.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['name' => 'USD ($)']);

        self::assertCount(1, $entries);
        self::assertEquals(2, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesBy() returns null when no rows match.
     */
    public function testGetEntriesByNoResults()
    {
        $entries = $this->out->getEntriesBy(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesBy() returns null when the table is empty.
     */
    public function testGetEntriesByEmptyTable()
    {
        $this->db->delete('events_currencies')->execute();

        $entries = $this->out->getEntriesBy();

        self::assertNull($entries);
    }

    /**
     * Tests that getCurrencies() returns all currencies.
     */
    public function testGetCurrencies()
    {
        $currencies = $this->out->getCurrencies();

        self::assertIsArray($currencies);
        self::assertCount(3, $currencies);
        self::assertInstanceOf(CurrencyModel::class, $currencies[0]);
    }

    /**
     * Tests that getCurrencies() with WHERE clause filters correctly.
     */
    public function testGetCurrenciesWithWhere()
    {
        $currencies = $this->out->getCurrencies(['id' => 2]);

        self::assertCount(1, $currencies);
        self::assertEquals('USD ($)', $currencies[0]->getName());
    }

    /**
     * Tests that getCurrencies() returns empty array when no rows match.
     */
    public function testGetCurrenciesNoResults()
    {
        $currencies = $this->out->getCurrencies(['id' => 9999]);

        self::assertIsArray($currencies);
        self::assertCount(0, $currencies);
    }

    /**
     * Tests that getCurrencyById() returns the correct currency.
     */
    public function testGetCurrencyById()
    {
        $currencies = $this->out->getCurrencyById(1);

        self::assertCount(1, $currencies);
        self::assertEquals(1, $currencies[0]->getId());
        self::assertEquals('EUR (€)', $currencies[0]->getName());
    }

    /**
     * Tests that getCurrencyById() returns empty array for a non-existent id.
     */
    public function testGetCurrencyByIdNotFound()
    {
        $currencies = $this->out->getCurrencyById(9999);

        self::assertIsArray($currencies);
        self::assertCount(0, $currencies);
    }

    /**
     * Tests that save() inserts a new currency when id is 0.
     */
    public function testSaveInsert()
    {
        $model = new CurrencyModel();
        $model->setId(0);
        $model->setName('JPY (¥)');

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getCurrencyById($id);
        self::assertCount(1, $saved);
        self::assertEquals('JPY (¥)', $saved[0]->getName());
    }

    /**
     * Tests that save() with a non-existent id (update path) returns 0.
     */
    public function testSaveUpdateNonExistentId()
    {
        $model = new CurrencyModel();
        $model->setId(9999);
        $model->setName('Ghost Currency');

        $result = $this->out->save($model);

        self::assertEquals(0, $result);

        $saved = $this->out->getCurrencyById(9999);
        self::assertCount(0, $saved);
    }

    /**
     * Tests that save() updates an existing currency when id is set.
     */
    public function testSaveUpdate()
    {
        $model = new CurrencyModel();
        $model->setId(1);
        $model->setName('EUR Updated');

        $result = $this->out->save($model);

        self::assertEquals(1, $result);

        $saved = $this->out->getCurrencyById(1);
        self::assertCount(1, $saved);
        self::assertEquals('EUR Updated', $saved[0]->getName());
    }

    /**
     * Tests updating a row with the same values (which execute() would return 0 for,
     * since MySQL reports 0 affected rows when nothing actually changes)
     *
     * @return void
     */
    public function testSaveUpdateNoChange()
    {
        $model = new CurrencyModel();
        $model->setId(1);
        $model->setName('EUR (€)'); // same as fixture

        $result = $this->out->save($model);

        self::assertEquals(0, $result);
    }

    /**
     * Tests that save() update does not affect other currencies.
     */
    public function testSaveUpdatePreservesOthers()
    {
        $model = new CurrencyModel();
        $model->setId(1);
        $model->setName('Changed');

        $this->out->save($model);

        $currency2 = $this->out->getCurrencyById(2);
        self::assertCount(1, $currency2);
        self::assertEquals('USD ($)', $currency2[0]->getName());
    }

    /**
     * Tests that deleteCurrencyById() removes a currency.
     */
    public function testDeleteCurrencyById()
    {
        $result = $this->out->deleteCurrencyById(1);

        self::assertTrue($result);

        $remaining = $this->out->getCurrencies();
        self::assertCount(2, $remaining);
    }

    /**
     * Tests that deleteCurrencyById() for a non-existent id does not throw.
     */
    public function testDeleteCurrencyByIdNotFound()
    {
        $this->out->deleteCurrencyById(9999);

        $currencies = $this->out->getCurrencies();
        self::assertCount(3, $currencies);
    }

    /**
     * Tests that deleteCurrencyById() removes the last remaining currency.
     */
    public function testDeleteLastCurrency()
    {
        $this->out->deleteCurrencyById(1);
        $this->out->deleteCurrencyById(2);
        $this->out->deleteCurrencyById(3);

        $currencies = $this->out->getCurrencies();
        self::assertCount(0, $currencies);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $commentModuleConfig = new CommentModuleConfig();

        return $commentModuleConfig->getInstallSql() . $config->getInstallSql();
    }
}
