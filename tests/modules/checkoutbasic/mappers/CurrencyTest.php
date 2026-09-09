<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Checkoutbasic\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Checkoutbasic\Config\Config as ModuleConfig;
use Modules\Checkoutbasic\Mappers\Currency as CurrencyMapper;
use Modules\Checkoutbasic\Models\Currency as CurrencyModel;

class CurrencyTest extends DatabaseTestCase
{
    /**
     * @var CurrencyMapper
     */
    protected $out;
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new CurrencyMapper();
    }

    /**
     * Tests if the checkoutbasic_currencies table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests if getCurrencies() returns all currencies.
     */
    public function testGetCurrencies()
    {
        $currencies = $this->out->getCurrencies();

        self::assertNotNull($currencies);
        self::assertCount(3, $currencies);
        self::assertInstanceOf(CurrencyModel::class, $currencies[0]);
    }

    /**
     * Tests if getCurrencies() returns the correct field values.
     */
    public function testGetCurrenciesFields()
    {
        // Default orderBy is name ASC: EUR, GBP, USD
        $currencies = $this->out->getCurrencies();

        self::assertEquals(1, $currencies[0]->getId());
        self::assertEquals('EUR (€)', $currencies[0]->getName());

        self::assertEquals(3, $currencies[1]->getId());
        self::assertEquals('GBP (£)', $currencies[1]->getName());

        self::assertEquals(2, $currencies[2]->getId());
        self::assertEquals('USD ($)', $currencies[2]->getName());
    }

    /**
     * Tests if getCurrencies() filters correctly with a WHERE clause.
     */
    public function testGetCurrenciesWithWhere()
    {
        $currencies = $this->out->getCurrencies(['id' => 2]);

        self::assertNotNull($currencies);
        self::assertCount(1, $currencies);
        self::assertEquals('USD ($)', $currencies[0]->getName());
    }

    /**
     * Tests if getCurrencies() returns null when no currencies match.
     */
    public function testGetCurrenciesNoMatch()
    {
        self::assertNull($this->out->getCurrencies(['id' => 9999]));
    }

    /**
     * Tests if getCurrencyById() returns the correct currency.
     */
    public function testGetCurrencyById()
    {
        $currency = $this->out->getCurrencyById(3);

        self::assertNotNull($currency);
        self::assertEquals(3, $currency->getId());
        self::assertEquals('GBP (£)', $currency->getName());
    }

    /**
     * Tests if getCurrencyById() returns null for a non-existent id.
     */
    public function testGetCurrencyByIdNotFound()
    {
        self::assertNull($this->out->getCurrencyById(9999));
    }

    /**
     * Tests if a new currency gets inserted properly via save().
     */
    public function testSaveInsert()
    {
        $model = new CurrencyModel();
        $model->setId(0)
            ->setName('JPY (¥)');

        $id = $this->out->save($model);

        self::assertGreaterThan(0, $id);

        $saved = $this->out->getCurrencyById($id);
        self::assertNotNull($saved);
        self::assertEquals('JPY (¥)', $saved->getName());
    }

    /**
     * Tests if an existing currency gets updated properly via save().
     */
    public function testSaveUpdate()
    {
        $model = new CurrencyModel();
        $model->setId(1)
            ->setName('Euro');

        $id = $this->out->save($model);

        self::assertEquals(1, $id);

        $saved = $this->out->getCurrencyById(1);
        self::assertNotNull($saved);
        self::assertEquals('Euro', $saved->getName());
    }

    /**
     * Tests if deleteCurrencyById() removes a currency.
     */
    public function testDeleteCurrencyById()
    {
        $result = $this->out->deleteCurrencyById(2);

        self::assertTrue($result);
        self::assertNull($this->out->getCurrencyById(2));

        $currencies = $this->out->getCurrencies();
        self::assertCount(2, $currencies);
    }

    /**
     * Tests if deleteCurrencyById() returns false for a non-existent id.
     */
    public function testDeleteCurrencyByIdNotFound()
    {
        $result = $this->out->deleteCurrencyById(9999);

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
