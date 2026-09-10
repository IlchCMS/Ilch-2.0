<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Checkoutbasic\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Checkoutbasic\Config\Config as ModuleConfig;
use Modules\Checkoutbasic\Mappers\Checkout as CheckoutMapper;
use Modules\Checkoutbasic\Models\Entry as CheckoutModel;

class CheckoutTest extends DatabaseTestCase
{
    /**
     * @var CheckoutMapper
     */
    protected Checkout $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new CheckoutMapper();
    }

    /**
     * Tests if the checkoutbasic table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests if getEntriesBy() returns all entries.
     */
    public function testGetEntriesByAll()
    {
        $entries = $this->out->getEntriesBy();

        self::assertNotNull($entries);
        self::assertCount(4, $entries);
        self::assertInstanceOf(CheckoutModel::class, $entries[0]);
    }

    /**
     * Tests if getEntriesBy() returns the correct field values.
     */
    public function testGetEntriesByFields()
    {
        $entries = $this->out->getEntriesBy([], ['date_created' => 'ASC']);

        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals('2025-01-15 10:30:00', $entries[0]->getDatetime());
        self::assertEquals('Alice', $entries[0]->getName());
        self::assertEquals('Membership fee', $entries[0]->getUsage());
        self::assertEquals(100.50, $entries[0]->getAmount());
    }

    /**
     * Tests if getEntriesBy() filters correctly with a WHERE clause.
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->out->getEntriesBy(['id' => 2]);

        self::assertNotNull($entries);
        self::assertCount(1, $entries);
        self::assertEquals('Bob', $entries[0]->getName());
        self::assertEquals(-50.25, $entries[0]->getAmount());
    }

    /**
     * Tests if getEntriesBy() returns null when no entries match.
     */
    public function testGetEntriesByNoMatch()
    {
        $entries = $this->out->getEntriesBy(['id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests if getEntries() returns all entries.
     */
    public function testGetEntries()
    {
        $entries = $this->out->getEntries();

        self::assertNotNull($entries);
        self::assertCount(4, $entries);
    }

    /**
     * Tests if getEntryById() returns the correct entry.
     */
    public function testGetEntryById()
    {
        $entry = $this->out->getEntryById(3);

        self::assertNotNull($entry);
        self::assertEquals(3, $entry->getId());
        self::assertEquals('Carol', $entry->getName());
        self::assertEquals(0.0, $entry->getAmount());
    }

    /**
     * Tests if getEntryById() returns null for a non-existent id.
     */
    public function testGetEntryByIdNotFound()
    {
        self::assertNull($this->out->getEntryById(9999));
    }

    /**
     * Tests if getAmount() returns the sum of all amounts.
     */
    public function testGetAmount()
    {
        // 100.50 + (-50.25) + 0.00 + 200.00 = 250.25
        self::assertEquals(250.25, $this->out->getAmount());
    }

    /**
     * Tests if getAmountPlus() returns the sum of positive amounts only.
     */
    public function testGetAmountPlus()
    {
        // amount > 0: 100.50 + 200.00 = 300.50
        self::assertEquals(300.50, $this->out->getAmountPlus());
    }

    /**
     * Tests if getAmountMinus() returns the sum of negative amounts only.
     */
    public function testGetAmountMinus()
    {
        // amount < 0: -50.25
        self::assertEquals(-50.25, $this->out->getAmountMinus());
    }

    /**
     * Tests if a new entry gets inserted properly via save().
     */
    public function testSaveInsert()
    {
        $model = new CheckoutModel();
        $model->setId(0)
            ->setDatetime('2025-06-01 12:00:00')
            ->setName('Eve')
            ->setUsage('New membership')
            ->setAmount(75.00);

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getEntryById($id);
        self::assertNotNull($saved);
        self::assertEquals('Eve', $saved->getName());
        self::assertEquals('New membership', $saved->getUsage());
        self::assertEquals(75.00, $saved->getAmount());
    }

    /**
     * Tests if an existing entry gets updated properly via save().
     */
    public function testSaveUpdate()
    {
        $model = new CheckoutModel();
        $model->setId(1)
            ->setDatetime('2025-07-01 08:00:00')
            ->setName('Alice Updated')
            ->setUsage('Corrected fee')
            ->setAmount(150.00);

        $id = $this->out->save($model);

        self::assertEquals(1, $id);

        $saved = $this->out->getEntryById(1);
        self::assertNotNull($saved);
        self::assertEquals('Alice Updated', $saved->getName());
        self::assertEquals('Corrected fee', $saved->getUsage());
        self::assertEquals(150.00, $saved->getAmount());
    }

    /**
     * Tests if deleteById() removes an entry.
     */
    public function testDeleteById()
    {
        $result = $this->out->deleteById(2);

        self::assertTrue($result);
        self::assertNull($this->out->getEntryById(2));

        $entries = $this->out->getEntries();
        self::assertCount(3, $entries);
    }

    /**
     * Tests if deleteById() returns false for a non-existent id.
     */
    public function testDeleteByIdNotFound()
    {
        $result = $this->out->deleteById(9999);

        self::assertFalse($result);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
