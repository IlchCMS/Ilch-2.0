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
use Modules\Training\Mappers\Training as TrainingMapper;
use Modules\Training\Models\Training as EntriesModel;

/**
 * @package ilch_phpunit
 */
class TrainingTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    /** @var TrainingMapper $mapper */
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new TrainingMapper();

        self::assertTrue($this->mapper->existsTable($this->mapper->tablename));
    }

    /**
     * All test routines
     */
    public function testTrainingGetAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(3, $entries);
    }

    public function testGetTraining()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(3, $entries);

        /** @var EntriesModel $entry */
        $entry = $entries[0];
        self::assertEquals(1, $entry->getId());
        self::assertEquals('Tag 1', $entry->getTitle());
        self::assertEquals("2024-01-15 05:00:00", $entry->getDate());
        self::assertEquals("2024-01-15 05:30:00", $entry->getEnd());
        self::assertEquals("", $entry->getPlace());
        self::assertEquals(1, $entry->getContact());
        self::assertEquals(0, $entry->getVoiceServer());
        self::assertEquals("", $entry->getVoiceServerIP());
        self::assertEquals("", $entry->getVoiceServerPW());
        self::assertEquals(0, $entry->getGameServer());
        self::assertEquals("", $entry->getGameServerIP());
        self::assertEquals("", $entry->getGameServerPW());
        self::assertEquals("", $entry->getText());
        self::assertEquals(0, $entry->getShow());
        self::assertEquals("1,2,3", $entry->getReadAccess());

        $entry = $entries[1];
        self::assertEquals(2, $entry->getId());
        self::assertEquals('Tag 2', $entry->getTitle());
        self::assertEquals("2024-02-15 05:00:00", $entry->getDate());
        self::assertEquals("2024-02-15 05:30:00", $entry->getEnd());
        self::assertEquals("", $entry->getPlace());
        self::assertEquals(1, $entry->getContact());
        self::assertEquals(0, $entry->getVoiceServer());
        self::assertEquals("", $entry->getVoiceServerIP());
        self::assertEquals("", $entry->getVoiceServerPW());
        self::assertEquals(0, $entry->getGameServer());
        self::assertEquals("", $entry->getGameServerIP());
        self::assertEquals("", $entry->getGameServerPW());
        self::assertEquals("", $entry->getText());
        self::assertEquals(0, $entry->getShow());
        self::assertEquals("1,2", $entry->getReadAccess());

        $entry = $entries[2];
        self::assertEquals(3, $entry->getId());
        self::assertEquals('Tag 3', $entry->getTitle());
        self::assertEquals("2024-03-15 05:00:00", $entry->getDate());
        self::assertEquals("2024-03-15 05:30:00", $entry->getEnd());
        self::assertEquals("", $entry->getPlace());
        self::assertEquals(1, $entry->getContact());
        self::assertEquals(0, $entry->getVoiceServer());
        self::assertEquals("", $entry->getVoiceServerIP());
        self::assertEquals("", $entry->getVoiceServerPW());
        self::assertEquals(0, $entry->getGameServer());
        self::assertEquals("", $entry->getGameServerIP());
        self::assertEquals("", $entry->getGameServerPW());
        self::assertEquals("", $entry->getText());
        self::assertEquals(0, $entry->getShow());
        self::assertEquals("all", $entry->getReadAccess());
    }

    public function testSaveNewTraining()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setTitle('Tag 4');
        $model->setDate('2024-04-15 05:00:00');
        $model->setEnd('2024-04-15 05:30:00');
        $model->setPlace('');
        $model->setContact(1);
        $model->setVoiceServer(false);
        $model->setVoiceServerIP('');
        $model->setVoiceServerPW('');
        $model->setGameServer(false);
        $model->setGameServerIP('');
        $model->setGameServerPW('');
        $model->setText('');
        $model->setShow(false);
        $model->setReadAccess('2');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getTrainingById($id, null);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getTitle(), $entry->getTitle());
        self::assertEquals($model->getDate(), $entry->getDate());
        self::assertEquals($model->getEnd(), $entry->getEnd());
        self::assertEquals($model->getPlace(), $entry->getPlace());
        self::assertEquals($model->getContact(), $entry->getContact());
        self::assertEquals($model->getVoiceServer(), $entry->getVoiceServer());
        self::assertEquals($model->getVoiceServerIP(), $entry->getVoiceServerIP());
        self::assertEquals($model->getVoiceServerPW(), $entry->getVoiceServerPW());
        self::assertEquals($model->getGameServer(), $entry->getGameServer());
        self::assertEquals($model->getGameServerIP(), $entry->getGameServerIP());
        self::assertEquals($model->getGameServerPW(), $entry->getGameServerPW());
        self::assertEquals($model->getText(), $entry->getText());
        self::assertEquals($model->getShow(), $entry->getShow());
        self::assertEquals('1,' . $model->getReadAccess(), $entry->getReadAccess());

        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getTrainingById($id, null);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals('all', $entry->getReadAccess());
    }

    public function testUpdateTraining()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPlace('');
        $model->setContact(1);
        $model->setVoiceServer(false);
        $model->setVoiceServerIP('');
        $model->setVoiceServerPW('');
        $model->setGameServer(false);
        $model->setGameServerIP('');
        $model->setGameServerPW('');
        $model->setText('');
        $model->setShow(false);
        $model->setReadAccess('2');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getTrainingById($id, null);
        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertEquals($model->getTitle(), $entry->getTitle());
        self::assertEquals($model->getDate(), $entry->getDate());
        self::assertEquals($model->getEnd(), $entry->getEnd());
        self::assertEquals($model->getPlace(), $entry->getPlace());
        self::assertEquals($model->getContact(), $entry->getContact());
        self::assertEquals($model->getVoiceServer(), $entry->getVoiceServer());
        self::assertEquals($model->getVoiceServerIP(), $entry->getVoiceServerIP());
        self::assertEquals($model->getVoiceServerPW(), $entry->getVoiceServerPW());
        self::assertEquals($model->getGameServer(), $entry->getGameServer());
        self::assertEquals($model->getGameServerIP(), $entry->getGameServerIP());
        self::assertEquals($model->getGameServerPW(), $entry->getGameServerPW());
        self::assertEquals($model->getText(), $entry->getText());
        self::assertEquals($model->getShow(), $entry->getShow());
        self::assertEquals('1,' . $model->getReadAccess(), $entry->getReadAccess());
    }

    public function testUpdateVoteAccess()
    {
        $this->mapper->saveAccess(1, 'all');

        $entry = $this->mapper->getTrainingById(1, null);

        self::assertNotNull($entry);
        self::assertEquals('1', $entry->getReadAccess());

        $this->mapper->saveAccess(2, '2,3');

        $entry = $this->mapper->getTrainingById(2, null);

        self::assertNotNull($entry);
        self::assertEquals('1,2,3', $entry->getReadAccess());
    }

    public function testDeleteTraining()
    {
        $this->mapper->delete(1);

        $entry = $this->mapper->getTrainingById(1, null);
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
