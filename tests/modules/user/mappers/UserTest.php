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

    public function setUp(): void
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
     * Test getUserListByGroupId to see if the correct user will be returned.
     *
     * @return void
     */
    public function testGetUserListByGroupId()
    {
        $userMapper = new UserMapper();

        /** @var \Modules\User\Models\User[] $users */
        $users = $userMapper->getUserListByGroupId(1, 1);
        self::assertNotEmpty($users);
        self::assertEquals(1, $users[0]->getId());
        self::assertEquals('Testuser1', $users[0]->getName());
        self::assertEquals(1, $users[0]->getConfirmed());
    }

    /**
     * Test getUserListByGroupIds to see if the correct users will be returned.
     *
     * @return void
     */
    public function testGetUserListByGroupIds()
    {
        $userMapper = new UserMapper();

        /** @var \Modules\User\Models\User[] $users */
        $users = $userMapper->getUserListByGroupIds([1, 3], 1);
        self::assertNotEmpty($users);
        self::assertEquals(1, $users[0]->getId());
        self::assertEquals('Testuser1', $users[0]->getName());
        self::assertEquals(1, $users[0]->getConfirmed());

        self::assertEquals(1, $users[1]->getId());
        self::assertEquals('Testuser1', $users[1]->getName());
        self::assertEquals(1, $users[1]->getConfirmed());

        self::assertEquals(2, $users[2]->getId());
        self::assertEquals('Testuser2', $users[2]->getName());
        self::assertEquals(1, $users[2]->getConfirmed());
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
