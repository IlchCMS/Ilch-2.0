<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

require APPLICATION_PATH . '/modules/user/config/config.php';

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;

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
