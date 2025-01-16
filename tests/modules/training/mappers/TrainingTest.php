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
    protected PhpunitDataset $phpunitDataset;
    /** @var TrainingMapper $mapper */
    private Training $mapper;

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
        self::assertSame(1, $entry->getId());
        self::assertSame('Tag 1', $entry->getTitle());
        self::assertSame("2024-01-15 05:00:00", $entry->getDate());
        self::assertSame("2024-01-15 05:30:00", $entry->getEnd());
        self::assertSame("", $entry->getPeriodType());
        self::assertSame(1, $entry->getPeriodDay());
        self::assertSame("1000-01-01 00:00:00", $entry->getRepeatUntil());
        self::assertSame(1, $entry->getContact());
        self::assertSame(false, $entry->getVoiceServer());
        self::assertSame("", $entry->getVoiceServerIP());
        self::assertSame("", $entry->getVoiceServerPW());
        self::assertSame(false, $entry->getGameServer());
        self::assertSame("", $entry->getGameServerIP());
        self::assertSame("", $entry->getGameServerPW());
        self::assertSame("", $entry->getText());
        self::assertSame(false, $entry->getShow());
        self::assertSame("1,2,3", $entry->getReadAccess());

        $entry = $entries[1];
        self::assertSame(2, $entry->getId());
        self::assertSame('Tag 2', $entry->getTitle());
        self::assertSame("2024-02-15 05:00:00", $entry->getDate());
        self::assertSame("2024-02-15 05:30:00", $entry->getEnd());
        self::assertSame("", $entry->getPeriodType());
        self::assertSame(1, $entry->getPeriodDay());
        self::assertSame("1000-01-01 00:00:00", $entry->getRepeatUntil());
        self::assertSame("", $entry->getPlace());
        self::assertSame(1, $entry->getContact());
        self::assertSame(false, $entry->getVoiceServer());
        self::assertSame("", $entry->getVoiceServerIP());
        self::assertSame("", $entry->getVoiceServerPW());
        self::assertSame(false, $entry->getGameServer());
        self::assertSame("", $entry->getGameServerIP());
        self::assertSame("", $entry->getGameServerPW());
        self::assertSame("", $entry->getText());
        self::assertSame(false, $entry->getShow());
        self::assertSame("1,2", $entry->getReadAccess());

        $entry = $entries[2];
        self::assertSame(3, $entry->getId());
        self::assertSame('Tag 3', $entry->getTitle());
        self::assertSame("2024-03-15 05:00:00", $entry->getDate());
        self::assertSame("2024-03-15 05:30:00", $entry->getEnd());
        self::assertSame("", $entry->getPeriodType());
        self::assertSame(1, $entry->getPeriodDay());
        self::assertSame("1000-01-01 00:00:00", $entry->getRepeatUntil());
        self::assertSame("", $entry->getPlace());
        self::assertSame(1, $entry->getContact());
        self::assertSame(false, $entry->getVoiceServer());
        self::assertSame("", $entry->getVoiceServerIP());
        self::assertSame("", $entry->getVoiceServerPW());
        self::assertSame(false, $entry->getGameServer());
        self::assertSame("", $entry->getGameServerIP());
        self::assertSame("", $entry->getGameServerPW());
        self::assertSame("", $entry->getText());
        self::assertSame(false, $entry->getShow());
        self::assertSame("all", $entry->getReadAccess());
    }

    public function testSaveNewTraining()
    {
        $currentDate = new \Ilch\Date();
        $currentDate = $currentDate->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(0);
        $model->setTitle('Tag 4');
        $model->setDate('2024-04-15 05:00:00');
        $model->setEnd('2024-04-15 05:30:00');
        $model->setPeriodType('weekly');
        $model->setPeriodDay('1');
        $model->setRepeatUntil($currentDate);
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
        self::assertSame($id, $entry->getId());
        self::assertSame($model->getTitle(), $entry->getTitle());
        self::assertSame($model->getDate(), $entry->getDate());
        self::assertSame($model->getEnd(), $entry->getEnd());
        self::assertSame($model->getPeriodType(), $entry->getPeriodType());
        self::assertSame($model->getPeriodDay(), $entry->getPeriodDay());
        self::assertSame($model->getRepeatUntil(), $entry->getRepeatUntil());
        self::assertSame($model->getPlace(), $entry->getPlace());
        self::assertSame($model->getContact(), $entry->getContact());
        self::assertSame($model->getVoiceServer(), $entry->getVoiceServer());
        self::assertSame($model->getVoiceServerIP(), $entry->getVoiceServerIP());
        self::assertSame($model->getVoiceServerPW(), $entry->getVoiceServerPW());
        self::assertSame($model->getGameServer(), $entry->getGameServer());
        self::assertSame($model->getGameServerIP(), $entry->getGameServerIP());
        self::assertSame($model->getGameServerPW(), $entry->getGameServerPW());
        self::assertSame($model->getText(), $entry->getText());
        self::assertSame($model->getShow(), $entry->getShow());
        self::assertSame('1,' . $model->getReadAccess(), $entry->getReadAccess());

        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getTrainingById($id, null);

        self::assertNotNull($entry);
        self::assertSame($id, $entry->getId());
        self::assertSame('all', $entry->getReadAccess());
    }

    public function testUpdateTraining()
    {
        $currentDate = new \Ilch\Date();
        $currentDate = $currentDate->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPeriodType('weekly');
        $model->setPeriodDay('1');
        $model->setRepeatUntil($currentDate);
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
        self::assertSame(1, $id);
        self::assertSame(1, $entry->getId());
        self::assertSame($model->getTitle(), $entry->getTitle());
        self::assertSame($model->getDate(), $entry->getDate());
        self::assertSame($model->getEnd(), $entry->getEnd());
        self::assertSame($model->getPeriodType(), $entry->getPeriodType());
        self::assertSame($model->getPeriodDay(), $entry->getPeriodDay());
        self::assertSame($model->getRepeatUntil(), $entry->getRepeatUntil());
        self::assertSame($model->getPlace(), $entry->getPlace());
        self::assertSame($model->getContact(), $entry->getContact());
        self::assertSame($model->getVoiceServer(), $entry->getVoiceServer());
        self::assertSame($model->getVoiceServerIP(), $entry->getVoiceServerIP());
        self::assertSame($model->getVoiceServerPW(), $entry->getVoiceServerPW());
        self::assertSame($model->getGameServer(), $entry->getGameServer());
        self::assertSame($model->getGameServerIP(), $entry->getGameServerIP());
        self::assertSame($model->getGameServerPW(), $entry->getGameServerPW());
        self::assertSame($model->getText(), $entry->getText());
        self::assertSame($model->getShow(), $entry->getShow());
        self::assertSame('1,' . $model->getReadAccess(), $entry->getReadAccess());
    }

    public function testUpdateVoteAccess()
    {
        $this->mapper->saveAccess(1, 'all');

        $entry = $this->mapper->getTrainingById(1, null);

        self::assertNotNull($entry);
        self::assertSame('1', $entry->getReadAccess());

        $this->mapper->saveAccess(2, '2,3');

        $entry = $this->mapper->getTrainingById(2, null);

        self::assertNotNull($entry);
        self::assertSame('1,2,3', $entry->getReadAccess());
    }

    public function testDeleteTraining()
    {
        $this->mapper->delete(1);

        $entry = $this->mapper->getTrainingById(1, null);
        self::assertNull($entry);
    }

    /**
     * Should return an unchanged start and end date as it is not a recurrent training.
     *
     * @return void
     * @throws \DateMalformedPeriodStringException
     */
    public function testCalculateNextTrainingNotRecurrent()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPeriodType('');
        $model->setPeriodDay('1');
        $model->setRepeatUntil("1000-01-01 00:00:00");
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

        $this->mapper->calculateNextTrainingDate($model);

        self::assertSame('2024-10-15 05:00:00', $model->getDate());
        self::assertSame('2024-10-15 05:30:00', $model->getEnd());
    }

    /**
     * Should return an unchanged start and end date as the repeat until date is in the past.
     *
     * @return void
     * @throws \DateMalformedPeriodStringException
     */
    public function testCalculateNextTrainingExpired()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPeriodType('weekly');
        $model->setPeriodDay('1');
        $model->setRepeatUntil("2024-10-15 05:30:00");
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

        $this->mapper->calculateNextTrainingDate($model);

        self::assertSame('2024-10-15 05:00:00', $model->getDate());
        self::assertSame('2024-10-15 05:30:00', $model->getEnd());
    }

    /**
     * Should return an unchanged start and end date as they are still in the future.
     *
     * @return void
     * @throws \DateMalformedPeriodStringException
     */
    public function testCalculateNextTrainingInitialDateInFuture()
    {
        $date = new \Ilch\Date();
        $startDate = date_add($date, new \DateInterval('P1D'));
        $endDate = date_add($startDate, new \DateInterval('PT1H'))->format('Y-m-d H:i:s');
        $startDate = $startDate->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate($startDate);
        $model->setEnd($endDate);
        $model->setPeriodType('weekly');
        $model->setPeriodDay('1');
        $model->setRepeatUntil($endDate);
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

        $this->mapper->calculateNextTrainingDate($model);

        self::assertSame($startDate, $model->getDate());
        self::assertSame($endDate, $model->getEnd());
    }

    /**
     * Should return an updated start and end date as we are dealing with a reccurent training, the initial date is in the past
     * and the repeat until date in the future.
     *
     * @return void
     * @throws \DateMalformedPeriodStringException
     */
    public function testCalculateNextTraining()
    {
        $date = new \Ilch\Date();
        $date->add(new \DateInterval('P1M'));
        $date = $date->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPeriodType('weekly');
        $model->setPeriodDay('1');
        $model->setRepeatUntil($date);
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

        $this->mapper->calculateNextTrainingDate($model);

        self::assertNotSame('2024-10-15 05:00:00', $model->getDate());
        self::assertNotSame('2024-10-15 05:30:00', $model->getEnd());
    }

    public function testGetNextTrainings()
    {
        $date = new \Ilch\Date();
        $date->add(new \DateInterval('P1M'));
        $date = $date->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(0);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPeriodType('weekly');
        $model->setPeriodDay('1');
        $model->setRepeatUntil($date);
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
        $model->setReadAccess('1');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getNextTrainings(5,1)[0];

        self::assertSame(4, $id);
        self::assertSame(4, $entry->getId());
        self::assertSame($model->getTitle(), $entry->getTitle());
        self::assertNotSame('2024-10-15 05:00:00', $entry->getDate());
        self::assertNotSame('2024-10-15 05:30:00', $entry->getEnd());
        self::assertSame($model->getPeriodType(), $entry->getPeriodType());
        self::assertSame($model->getPeriodDay(), $entry->getPeriodDay());
        self::assertSame($model->getRepeatUntil(), $entry->getRepeatUntil());
        self::assertSame($model->getPlace(), $entry->getPlace());
        self::assertSame($model->getContact(), $entry->getContact());
        self::assertSame($model->getVoiceServer(), $entry->getVoiceServer());
        self::assertSame($model->getVoiceServerIP(), $entry->getVoiceServerIP());
        self::assertSame($model->getVoiceServerPW(), $entry->getVoiceServerPW());
        self::assertSame($model->getGameServer(), $entry->getGameServer());
        self::assertSame($model->getGameServerIP(), $entry->getGameServerIP());
        self::assertSame($model->getGameServerPW(), $entry->getGameServerPW());
        self::assertSame($model->getText(), $entry->getText());
        self::assertSame($model->getShow(), $entry->getShow());
        self::assertSame('1', $entry->getReadAccess());
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
