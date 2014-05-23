<?php
/**
 * Holds class Modules_User_Mappers_UserTest.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace User\Mappers;

require APPLICATION_PATH . '/modules/user/config/config.php';

use PHPUnit\Ilch\DatabaseTestCase;
use User\Config\Config as ModuleConfig;
use User\Mappers\User as UserMapper;
use User\Models\User as UserModel;

/**
 * Tests the user mapper class.
 *
 * @package ilch_phpunit
 */
class UserTest extends DatabaseTestCase
{
    /**
     * @var UserMapper
     */
    protected $out;

    public function setUp()
    {
        parent::setUp();
        $this->out = new UserMapper();
    }


    public function testGetAdministratorCount()
    {
        $this->assertEquals(1, $this->out->getAdministratorCount());
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        return $config->getInstallSql();
    }
}
