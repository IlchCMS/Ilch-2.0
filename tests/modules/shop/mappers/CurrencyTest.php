<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Shop\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Shop\Config\Config as ModuleConfig;
use Modules\Shop\Models\Currency as CurrencyModel;

class CurrencyTest extends DatabaseTestCase
{
    /**
     * @var Currency
     */
    protected Currency $out;

    public function setUp(): void
    {
        parent::setUp();
        $this->out = new Currency();
    }

    /**
     * Tests that getCurrencies() returns all currencies from the sample data.
     */
    public function testGetCurrencies()
    {
        $currencies = $this->out->getCurrencies();

        self::assertIsArray($currencies);
        self::assertCount(6, $currencies);
        self::assertInstanceOf(CurrencyModel::class, $currencies[0]);
    }

    /**
     * Tests that getCurrencyById() returns the correct fields for the first sample currency.
     */
    public function testGetCurrencyByIdFields()
    {
        $currency = $this->out->getCurrencyById(1);

        self::assertInstanceOf(CurrencyModel::class, $currency);
        self::assertEquals(1, $currency->getId());
        self::assertEquals('EUR (€)', $currency->getName());
        self::assertEquals('EUR', $currency->getCode());
    }

    /**
     * Tests that getCurrencyById() returns the correct fields for the second sample currency.
     */
    public function testGetCurrencyByIdSecond()
    {
        $currency = $this->out->getCurrencyById(2);

        self::assertInstanceOf(CurrencyModel::class, $currency);
        self::assertEquals(2, $currency->getId());
        self::assertEquals('USD ($)', $currency->getName());
        self::assertEquals('USD', $currency->getCode());
    }

    /**
     * Tests that getCurrencies() returns currencies ordered by name ASC.
     */
    public function testGetCurrenciesOrdered()
    {
        $currencies = $this->out->getCurrencies();

        $codes = [];
        foreach ($currencies as $currency) {
            $codes[] = $currency->getCode();
        }

        self::assertEquals(['AUD', 'CHF', 'EUR', 'GBP', 'NZD', 'USD'], $codes);
    }

    /**
     * Tests that getCurrencies() accepts a where array.
     */
    public function testGetCurrenciesWhere()
    {
        $currencies = $this->out->getCurrencies(['code' => 'CHF']);

        self::assertIsArray($currencies);
        self::assertCount(1, $currencies);
        self::assertEquals(6, $currencies[0]->getId());
        self::assertEquals('CHF', $currencies[0]->getName());
        self::assertEquals('CHF', $currencies[0]->getCode());
    }

    /**
     * Tests that getCurrencies() returns an empty array when no currencies exist.
     */
    public function testGetCurrenciesEmpty()
    {
        foreach ([1, 2, 3, 4, 5, 6] as $id) {
            $this->out->deleteCurrencyById($id);
        }

        $currencies = $this->out->getCurrencies();

        self::assertIsArray($currencies);
        self::assertCount(0, $currencies);
    }

    /**
     * Tests that getCurrencyById() returns null for a non-existent id.
     */
    public function testGetCurrencyByIdNotFound()
    {
        $currency = $this->out->getCurrencyById(9999);

        self::assertNull($currency);
    }

    /**
     * Tests inserting a new currency via save().
     */
    public function testSaveInsert()
    {
        $model = new CurrencyModel();
        $model->setId(0);
        $model->setName('SEK (kr)');
        $model->setCode('SEK');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(6, $newId);

        $currency = $this->out->getCurrencyById($newId);
        self::assertInstanceOf(CurrencyModel::class, $currency);
        self::assertEquals('SEK (kr)', $currency->getName());
        self::assertEquals('SEK', $currency->getCode());

        self::assertCount(7, $this->out->getCurrencies());
    }

    /**
     * Tests that save() with id 0 performs an insert and does not affect existing currencies.
     */
    public function testSaveZeroIdInserts()
    {
        $before = $this->out->getCurrencies();
        self::assertCount(6, $before);

        $model = new CurrencyModel();
        $model->setId(0);
        $model->setName('New Currency');
        $model->setCode('NEW');

        $newId = $this->out->save($model);

        self::assertIsInt($newId);
        self::assertGreaterThan(6, $newId);

        $after = $this->out->getCurrencies();
        self::assertCount(7, $after);

        $existing = $this->out->getCurrencyById(1);
        self::assertEquals('EUR (€)', $existing->getName());
        self::assertEquals('EUR', $existing->getCode());
    }

    /**
     * Tests updating an existing currency via save().
     */
    public function testSaveUpdate()
    {
        $model = new CurrencyModel();
        $model->setId(1);
        $model->setName('Updated Euro');
        $model->setCode('EU1');

        $savedId = $this->out->save($model);

        self::assertSame(1, $savedId);

        $currency = $this->out->getCurrencyById(1);

        self::assertInstanceOf(CurrencyModel::class, $currency);
        self::assertEquals(1, $currency->getId());
        self::assertEquals('Updated Euro', $currency->getName());
        self::assertEquals('EU1', $currency->getCode());
    }

    /**
     * Tests that update does not affect other currencies.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new CurrencyModel();
        $model->setId(1);
        $model->setName('Changed Euro');
        $model->setCode('EU2');

        $this->out->save($model);

        $other = $this->out->getCurrencyById(2);

        self::assertInstanceOf(CurrencyModel::class, $other);
        self::assertEquals('USD ($)', $other->getName());
        self::assertEquals('USD', $other->getCode());
    }

    /**
     * Tests that deleteCurrencyById() removes a currency.
     */
    public function testDeleteCurrencyById()
    {
        $deleted = $this->out->deleteCurrencyById(1);

        self::assertTrue($deleted);

        self::assertNull($this->out->getCurrencyById(1));

        $currencies = $this->out->getCurrencies();
        self::assertCount(5, $currencies);
    }

    /**
     * Tests that deleteCurrencyById() on a non-existent id does not throw.
     */
    public function testDeleteCurrencyByIdNotFound()
    {
        $deleted = $this->out->deleteCurrencyById(9999);

        self::assertFalse($deleted);

        $currencies = $this->out->getCurrencies();
        self::assertCount(6, $currencies);
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
