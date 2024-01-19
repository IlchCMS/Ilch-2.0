<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Vote\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Vote\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Vote\Mappers\Ip as IpMapper;
use Modules\Vote\Models\Ip as EntriesModel;

/**
 * @package ilch_phpunit
 */
class IpTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    /** @var IpMapper $mapper */
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new IpMapper();

        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * All test routines
     */
    public function testVoteIpgetAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(4, $entries);
    }

    public function testgetVoteIp()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(4, $entries);

        $i = 0;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals(1, $entry->getPollId());
        self::assertEquals('192.168.10.100', $entry->getIP());
        self::assertEquals(0, $entry->getUserId());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(2, $entry->getPollId());
        self::assertEquals('192.168.10.101', $entry->getIP());
        self::assertEquals(1, $entry->getUserId());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(2, $entry->getPollId());
        self::assertEquals('192.168.10.102', $entry->getIP());
        self::assertEquals(0, $entry->getUserId());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(2, $entry->getPollId());
        self::assertEquals('192.168.10.100', $entry->getIP());
        self::assertEquals(0, $entry->getUserId());
    }

    public function testsaveNewVoteIp()
    {
        $model = new EntriesModel();
        $model->setIP('192.168.10.110');
        $model->setUserId(2);
        $model->setPollId(1);
        $this->mapper->saveIP($model);

        $entry = $this->mapper->getIP(1, '192.168.10.110');

        self::assertNotNull($entry);
        self::assertEquals($model->getIP(), $entry->getIP());
        self::assertEquals($model->getUserId(), $entry->getUserId());
        self::assertEquals($model->getPollId(), $entry->getPollId());

        $entry = $this->mapper->getVotedUser(1, 2);

        self::assertNotNull($entry);
        self::assertEquals($model->getIP(), $entry->getIP());
        self::assertEquals($model->getUserId(), $entry->getUserId());
        self::assertEquals($model->getPollId(), $entry->getPollId());
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
