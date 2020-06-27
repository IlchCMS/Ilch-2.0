<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use \Modules\User\Mappers\CookieStolen as CookieStolenMapper;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;

/**
 * Tests the cookie stolen mapper class.
 *
 * @package ilch_phpunit
 */
class CookieStolenTest extends DatabaseTestCase
{
    /**
     * Tests containsCookieStolen(). Should return 1.
     */
    public function testContainsCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        $this->assertEquals(1, $mapper->containsCookieStolen(1));
    }

    /**
     * Tests containsCookieStolen(). Should return 0.
     */
    public function testContainsCookieStolenNotExisting()
    {
        $mapper = new CookieStolenMapper();

        $this->assertEquals(0, $mapper->containsCookieStolen(0));
    }

    /**
     * Tests adding a cookie stolen entry.
     */
    public function testAddCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        $mapper->addCookieStolen(2);
        $this->assertEquals(1, $mapper->containsCookieStolen(2));
    }

    /**
     * Tests deleting a cookie stolen entry for a user with a specific id.
     */
    public function testDeleteCookieStolen()
    {
        $mapper = new CookieStolenMapper();

        $this->assertEquals(1, $mapper->deleteCookieStolen(1));
    }

    /**
     * Tests deleting a cookie stolen when there is no entry for that user.
     */
    public function testDeleteCookieStolenNotExisting()
    {
        $mapper = new CookieStolenMapper();

        $this->assertEquals(0, $mapper->deleteCookieStolen(0));
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet(__DIR__ . '/../_files/mysql_database.yml');
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
