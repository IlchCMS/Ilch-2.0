<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Mappers\CookieStolen as CookieStolenMapper;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the cookie stolen mapper class.
 *
 * @package ilch_phpunit
 */
class CookieStolenTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Tests containsCookieStolen() for an existing entry. Should return true.
     */
    public function testContainsCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        self::assertTrue($mapper->containsCookieStolen(1));
    }

    /**
     * Tests containsCookieStolen() for a non-existing entry. Should return false.
     */
    public function testContainsCookieStolenNotExisting()
    {
        $mapper = new CookieStolenMapper();

        self::assertFalse($mapper->containsCookieStolen(0));
    }

    /**
     * Tests adding a cookie stolen entry for a user without an existing entry.
     */
    public function testAddCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        // The fixture contains 1 cookie stolen row (id 1), so the new row gets id 2.
        self::assertEquals(2, $mapper->addCookieStolen(2));
        self::assertTrue($mapper->containsCookieStolen(2));
    }

    /**
     * Tests that adding a cookie stolen entry for a user with an existing
     * entry does not insert a duplicate.
     */
    public function testAddCookieStolenExisting()
    {
        $mapper = new CookieStolenMapper();

        self::assertEquals(0, $mapper->addCookieStolen(1));

        // Only one row for the user should exist, so exactly one row can be deleted.
        self::assertTrue($mapper->containsCookieStolen(1));
        self::assertEquals(1, $mapper->deleteCookieStolen(1));
    }

    /**
     * Tests deleting a cookie stolen entry for a user with a specific id.
     */
    public function testDeleteCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        self::assertEquals(1, $mapper->deleteCookieStolen(1));
        self::assertFalse($mapper->containsCookieStolen(1));
    }

    /**
     * Tests deleting a cookie stolen entry when there is no entry for that user.
     */
    public function testDeleteCookieStolenNotExisting()
    {
        $mapper = new CookieStolenMapper();

        self::assertEquals(0, $mapper->deleteCookieStolen(0));
    }

    /**
     * Returns database schema sql statements to initialize database
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
