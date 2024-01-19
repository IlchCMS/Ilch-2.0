<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Training\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Training\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Training\Mappers\Entrants as EntrantsMapper;
use Modules\Training\Models\Entrants as EntriesModel;

/**
 * @package ilch_phpunit
 */
class EntrantsTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    /** @var EntrantsMapper $mapper */
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new EntrantsMapper();
    }

    /**
     * All test routines
     */
    public function testGetEntrantsAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(3, $entries);
    }

    public function testGetEntrants()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(3, $entries);

        $i = 0;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals(1, $entry->getTrainId());
        self::assertEquals(1, $entry->getUserId());
        self::assertEquals('', $entry->getNote());

        $i++;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals(1, $entry->getTrainId());
        self::assertEquals(2, $entry->getUserId());
        self::assertEquals('', $entry->getNote());

        $i++;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals(2, $entry->getTrainId());
        self::assertEquals(2, $entry->getUserId());
        self::assertEquals('', $entry->getNote());
    }

    public function testSaveNewEntrants()
    {
        $model = new EntriesModel();
        $model->setTrainId(3);
        $model->setUserId(1);
        $model->setNote('');
        $this->mapper->saveUserOnTrain($model);

        $entry = $this->mapper->getEntrants($model->getTrainId(), $model->getUserId());

        self::assertNotNull($entry);
    }

    public function testDeleteTraining()
    {
        $this->mapper->deleteUserFromTrain(3, 1);

        $entry = $this->mapper->getEntrants(3, 1);
        self::assertNull($entry);
    }

    public function testDeleteTrainingAll()
    {
        $this->mapper->deleteAllUser(1);

        $entry = $this->mapper->getEntrantsById(1);
        self::assertNull($entry);
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
