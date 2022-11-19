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
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Tests containsCookieStolen(). Should return 1.
     */
    public function testContainsCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        self::assertEquals(1, $mapper->containsCookieStolen(1));
    }

    /**
     * Tests containsCookieStolen(). Should return 0.
     */
    public function testContainsCookieStolenNotExisting()
    {
        $mapper = new CookieStolenMapper();

        self::assertEquals(0, $mapper->containsCookieStolen(0));
    }

    /**
     * Tests adding a cookie stolen entry.
     */
    public function testAddCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        $mapper->addCookieStolen(2);
        self::assertEquals(1, $mapper->containsCookieStolen(2));
    }

    /**
     * Tests deleting a cookie stolen entry for a user with a specific id.
     */
    public function testDeleteCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        self::assertEquals(1, $mapper->deleteCookieStolen(1));
    }

    /**
     * Tests deleting a cookie stolen when there is no entry for that user.
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
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql().$config->getInstallSql();
    }
}
