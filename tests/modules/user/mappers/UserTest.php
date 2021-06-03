<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

require APPLICATION_PATH . '/modules/user/config/config.php';

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use PHPUnit\Ilch\PhpunitDataset;

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

    protected $phpunitDataset;

    public function setUp()
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new UserMapper();
    }

    public function testGetAdministratorCount()
    {
        self::assertEquals(1, $this->out->getAdministratorCount());
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
