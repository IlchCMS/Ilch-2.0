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
use Ilch\Pagination;

/**
 * @package ilch_phpunit
 */
class EntrantsTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    /** @var EntrantsMapper $mapper */
    private Entrants $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

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

        /** @var EntriesModel $entry */
        $entry = $entries[0];
        self::assertEquals(1, $entry->getTrainId());
        self::assertEquals(1, $entry->getUserId());
        self::assertEquals('', $entry->getNote());

        /** @var EntriesModel $entry */
        $entry = $entries[1];
        self::assertEquals(1, $entry->getTrainId());
        self::assertEquals(2, $entry->getUserId());
        self::assertEquals('', $entry->getNote());

        /** @var EntriesModel $entry */
        $entry = $entries[2];
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
     * Tests that getEntriesBy() filters by train_id.
     */
    public function testGetEntriesByTrainIdFilter()
    {
        $entries = $this->mapper->getEntriesBy(['train_id' => 1]);

        self::assertCount(2, $entries);
        self::assertSame(1, $entries[0]->getTrainId());
        self::assertSame(1, $entries[0]->getUserId());
        self::assertSame(2, $entries[1]->getUserId());
    }

    /**
     * Tests that getEntriesBy() filters by user_id.
     */
    public function testGetEntriesByUserIdFilter()
    {
        $entries = $this->mapper->getEntriesBy(['user_id' => 2]);

        self::assertCount(2, $entries);
        self::assertSame(1, $entries[0]->getTrainId());
        self::assertSame(2, $entries[0]->getUserId());
        self::assertSame(2, $entries[1]->getTrainId());
    }

    /**
     * Tests that getEntriesBy() returns null when no entries match.
     */
    public function testGetEntriesByEmpty()
    {
        $entries = $this->mapper->getEntriesBy(['train_id' => 9999]);

        self::assertNull($entries);
    }

    /**
     * Tests that getEntriesBy() respects a custom order.
     */
    public function testGetEntriesByOrder()
    {
        $entries = $this->mapper->getEntriesBy([], ['train_id' => 'DESC']);

        self::assertCount(3, $entries);
        self::assertSame(2, $entries[0]->getTrainId());
        self::assertSame(1, $entries[1]->getTrainId());
        self::assertSame(1, $entries[2]->getTrainId());
    }

    /**
     * Tests that getEntriesBy() limits the results and sets the total rows on the pagination.
     */
    public function testGetEntriesByPagination()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(2);

        $entries = $this->mapper->getEntriesBy([], [], $pagination);

        self::assertCount(2, $entries);
        self::assertSame(3, $pagination->getRows());
    }

    /**
     * Tests that getEntrants() returns the matching entrant with its fields.
     */
    public function testGetEntrantsFound()
    {
        $entry = $this->mapper->getEntrants(1, 1);

        self::assertNotNull($entry);
        self::assertSame(1, $entry->getTrainId());
        self::assertSame(1, $entry->getUserId());
        self::assertSame('', $entry->getNote());
    }

    /**
     * Tests that getEntrants() returns null when no entrant exists for the combination.
     */
    public function testGetEntrantsNotFound()
    {
        self::assertNull($this->mapper->getEntrants(3, 1));
    }

    /**
     * Tests that getEntrantsById() returns all entrants of a training as models.
     */
    public function testGetEntrantsById()
    {
        $entries = $this->mapper->getEntrantsById(1);

        self::assertCount(2, $entries);
        self::assertInstanceOf(EntriesModel::class, $entries[0]);
    }

    /**
     * Tests that getEntrantsById() returns null when the training has no entrants.
     */
    public function testGetEntrantsByIdEmpty()
    {
        self::assertNull($this->mapper->getEntrantsById(3));
    }

    /**
     * Tests that saveUserOnTrain() persists all fields of the model.
     */
    public function testSaveUserOnTrainPersistsFields()
    {
        $model = new EntriesModel();
        $model->setTrainId(3);
        $model->setUserId(2);
        $model->setNote('Present');

        $this->mapper->saveUserOnTrain($model);

        $entry = $this->mapper->getEntrants(3, 2);

        self::assertNotNull($entry);
        self::assertSame(3, $entry->getTrainId());
        self::assertSame(2, $entry->getUserId());
        self::assertSame('Present', $entry->getNote());
    }

    /**
     * Tests that deleteUserFromTrain() only removes the matching entrant.
     */
    public function testDeleteUserFromTrainExisting()
    {
        $this->mapper->deleteUserFromTrain(1, 1);

        self::assertNull($this->mapper->getEntrants(1, 1));

        // The other entrant of training 1 must still exist.
        $remaining = $this->mapper->getEntrantsById(1);
        self::assertCount(1, $remaining);
        self::assertSame(2, $remaining[0]->getUserId());
    }

    /**
     * Tests that deleteAllUser() removes all entrants of one training but keeps the others.
     */
    public function testDeleteAllUserKeepsOtherTrainings()
    {
        $this->mapper->deleteAllUser(1);

        self::assertNull($this->mapper->getEntrantsById(1));

        $remaining = $this->mapper->getEntrantsById(2);
        self::assertCount(1, $remaining);
        self::assertSame(2, $remaining[0]->getUserId());
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
