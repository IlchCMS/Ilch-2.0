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
use Ilch\Pagination;

/**
 * @package ilch_phpunit
 */
class TrainingTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    /** @var TrainingMapper $mapper */
    private Training $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

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
        self::assertSame('2024-01-15 05:00:00', $entry->getDate());
        self::assertSame('2024-01-15 05:30:00', $entry->getEnd());
        self::assertSame('', $entry->getPeriodType());
        self::assertSame(1, $entry->getPeriodDay());
        self::assertSame('1000-01-01 00:00:00', $entry->getRepeatUntil());
        self::assertSame(1, $entry->getContact());
        self::assertSame(false, $entry->getVoiceServer());
        self::assertSame('', $entry->getVoiceServerIP());
        self::assertSame('', $entry->getVoiceServerPW());
        self::assertSame(false, $entry->getGameServer());
        self::assertSame('', $entry->getGameServerIP());
        self::assertSame('', $entry->getGameServerPW());
        self::assertSame('', $entry->getText());
        self::assertSame(false, $entry->getShow());
        self::assertSame('1,2,3', $entry->getReadAccess());

        $entry = $entries[1];
        self::assertSame(2, $entry->getId());
        self::assertSame('Tag 2', $entry->getTitle());
        self::assertSame('2024-02-15 05:00:00', $entry->getDate());
        self::assertSame('2024-02-15 05:30:00', $entry->getEnd());
        self::assertSame('', $entry->getPeriodType());
        self::assertSame(1, $entry->getPeriodDay());
        self::assertSame('1000-01-01 00:00:00', $entry->getRepeatUntil());
        self::assertSame('', $entry->getPlace());
        self::assertSame(1, $entry->getContact());
        self::assertSame(false, $entry->getVoiceServer());
        self::assertSame('', $entry->getVoiceServerIP());
        self::assertSame('', $entry->getVoiceServerPW());
        self::assertSame(false, $entry->getGameServer());
        self::assertSame('', $entry->getGameServerIP());
        self::assertSame('', $entry->getGameServerPW());
        self::assertSame('', $entry->getText());
        self::assertSame(false, $entry->getShow());
        self::assertSame('1,2', $entry->getReadAccess());

        $entry = $entries[2];
        self::assertSame(3, $entry->getId());
        self::assertSame('Tag 3', $entry->getTitle());
        self::assertSame('2024-03-15 05:00:00', $entry->getDate());
        self::assertSame('2024-03-15 05:30:00', $entry->getEnd());
        self::assertSame('', $entry->getPeriodType());
        self::assertSame(1, $entry->getPeriodDay());
        self::assertSame('1000-01-01 00:00:00', $entry->getRepeatUntil());
        self::assertSame('', $entry->getPlace());
        self::assertSame(1, $entry->getContact());
        self::assertSame(false, $entry->getVoiceServer());
        self::assertSame('', $entry->getVoiceServerIP());
        self::assertSame('', $entry->getVoiceServerPW());
        self::assertSame(false, $entry->getGameServer());
        self::assertSame('', $entry->getGameServerIP());
        self::assertSame('', $entry->getGameServerPW());
        self::assertSame('', $entry->getText());
        self::assertSame(false, $entry->getShow());
        self::assertSame('all', $entry->getReadAccess());
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
        $model->setRepeatUntil('1000-01-01 00:00:00');
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
        $model->setRepeatUntil('2024-10-15 05:30:00');
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
     * Should return an updated start and end date as we are dealing with a recurrent training, the initial date is in the past
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

        $entry = $this->mapper->getNextTrainings(5, 1)[0];

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
     * Builds a future, non-recurrent training model for the given date.
     *
     * @param string $title The title of the training
     * @param \Ilch\Date $date The date of the training
     * @param string $readAccess The read access of the training
     * @return EntriesModel
     */
    private function createFutureTrainingModel(string $title, \Ilch\Date $date, string $readAccess = '1'): EntriesModel
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setTitle($title);
        $model->setDate($date->format('Y-m-d H:i:s'));
        $model->setEnd($date->format('Y-m-d H:i:s'));
        $model->setPeriodType('');
        $model->setPeriodDay(1);
        $model->setRepeatUntil('1000-01-01 00:00:00');
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
        $model->setReadAccess($readAccess);

        return $model;
    }

    /**
     * Tests that getTraining() filters by a group id string and includes access_all trainings.
     */
    public function testGetTrainingGroupFilterString()
    {
        $entries = $this->mapper->getTraining([], '3');

        self::assertCount(2, $entries);
        self::assertSame(1, $entries[0]->getId());
        self::assertSame(3, $entries[1]->getId());
    }

    /**
     * Tests that getTraining() filters by an array of group ids.
     */
    public function testGetTrainingGroupFilterArray()
    {
        $entries = $this->mapper->getTraining([], [2]);

        self::assertCount(3, $entries);
    }

    /**
     * Tests that getTraining() without group ids returns all trainings.
     */
    public function testGetTrainingWithoutGroupIds()
    {
        $entries = $this->mapper->getTraining([], null);

        self::assertCount(3, $entries);
    }

    /**
     * Tests that getTrainingById() returns null for a training the group cannot access.
     */
    public function testGetTrainingByIdNotAccessible()
    {
        self::assertNull($this->mapper->getTrainingById(2, '3'));
    }

    /**
     * Tests that getTrainingById() returns the training when the group can access it.
     */
    public function testGetTrainingByIdAccessible()
    {
        $entry = $this->mapper->getTrainingById(2, '2');

        self::assertNotNull($entry);
        self::assertSame(2, $entry->getId());
    }

    /**
     * Tests that getTrainingById() returns access_all trainings for any group.
     */
    public function testGetTrainingByIdAccessAll()
    {
        $entry = $this->mapper->getTrainingById(3, '3');

        self::assertNotNull($entry);
        self::assertSame(3, $entry->getId());
        self::assertSame('all', $entry->getReadAccess());
    }

    /**
     * Tests that getTrainingById() returns null for a non-existent id.
     */
    public function testGetTrainingByIdNotFound()
    {
        self::assertNull($this->mapper->getTrainingById(9999, null));
    }

    /**
     * Tests that getTrainingsForJson() returns null when start or end is missing.
     */
    public function testGetTrainingsForJsonMissingParams()
    {
        self::assertNull($this->mapper->getTrainingsForJson(null, '2024-03-31 00:00:00'));
        self::assertNull($this->mapper->getTrainingsForJson('2024-01-01 00:00:00', null));
        self::assertNull($this->mapper->getTrainingsForJson(null, null));
    }

    /**
     * Tests that getTrainingsForJson() returns all trainings of the group within the range.
     */
    public function testGetTrainingsForJsonInRange()
    {
        $entries = $this->mapper->getTrainingsForJson('2024-01-01 00:00:00', '2024-03-31 00:00:00', '1');

        self::assertCount(3, $entries);
    }

    /**
     * Tests that getTrainingsForJson() only returns trainings of the given group,
     * including access_all trainings (consistent with getTraining()).
     */
    public function testGetTrainingsForJsonGroupFilter()
    {
        $entries = $this->mapper->getTrainingsForJson('2024-01-01 00:00:00', '2024-03-31 00:00:00', '3');

        self::assertCount(2, $entries);
        self::assertSame(1, $entries[0]->getId());
        self::assertSame(3, $entries[1]->getId());
    }

    /**
     * Tests that getTrainingsForJson() accepts \Ilch\Date objects as start and end.
     */
    public function testGetTrainingsForJsonDateObjects()
    {
        $start = new \Ilch\Date('2024-01-01 00:00:00');
        $end = new \Ilch\Date('2024-03-31 00:00:00');

        $entries = $this->mapper->getTrainingsForJson($start, $end, '1');

        self::assertCount(3, $entries);
    }

    /**
     * Tests that getTrainingsForJson() returns a recurrent training whose period covers the range.
     */
    public function testGetTrainingsForJsonRecurrent()
    {
        $date = new \Ilch\Date();
        $date = date_add($date, new \DateInterval('P1M'));
        $repeatUntil = $date->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(0);
        $model->setTitle('Recurrent JSON');
        $model->setDate('2024-01-15 05:00:00');
        $model->setEnd('2024-01-15 05:30:00');
        $model->setPeriodType('weekly');
        $model->setPeriodDay(1);
        $model->setRepeatUntil($repeatUntil);
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

        $entries = $this->mapper->getTrainingsForJson('2024-01-10 00:00:00', '2024-04-01 00:00:00', '1');

        self::assertCount(4, $entries);

        $found = false;
        foreach ($entries as $entry) {
            if ($entry->getId() === $id) {
                $found = true;
            }
        }
        self::assertTrue($found);
    }

    /**
     * Tests that getTrainingsForJson() does not return trainings that ended before the start date.
     *
     * Note: This test currently FAILS. The first branch of the OR condition only checks
     * "t.end <= end" without a lower bound on the start date, so training 1 (ended
     * 2024-01-15) is returned although the range starts on 2024-02-01.
     */
    public function testGetTrainingsForJsonExcludesTrainingsBeforeStart()
    {
        $entries = $this->mapper->getTrainingsForJson('2024-02-01 00:00:00', '2024-03-01 00:00:00', '1');

        self::assertCount(1, $entries);
        self::assertSame(2, $entries[0]->getId());
    }

    /**
     * Tests that the deprecated getTrainingsListWithLimt() limits the number of results.
     *
     * @deprecated No longer used in this module.
     */
    public function testGetTrainingsListWithLimt()
    {
        $entries = $this->mapper->getTrainingsListWithLimt(2, '1', 'ASC');

        self::assertCount(2, $entries);
        self::assertSame(1, $entries[0]->getId());
        self::assertSame(2, $entries[1]->getId());
    }

    /**
     * Tests that the deprecated getTrainingsListWithLimt() without a limit returns all trainings.
     *
     * @deprecated No longer used in this module.
     */
    public function testGetTrainingsListWithoutLimit()
    {
        $entries = $this->mapper->getTrainingsListWithLimt(null, '1', 'ASC');

        self::assertCount(3, $entries);
    }

    /**
     * Tests that getNextTrainings() returns an empty array when there is no future training.
     */
    public function testGetNextTrainingsEmpty()
    {
        self::assertSame([], $this->mapper->getNextTrainings(null, '1'));
    }

    /**
     * Tests that getNextTrainings() sorts future trainings by date and applies the limit.
     */
    public function testGetNextTrainingsSortedAndLimited()
    {
        $now = new \Ilch\Date();

        $firstId = $this->mapper->save($this->createFutureTrainingModel('Next 1', date_add($now, new \DateInterval('P1D'))));
        $secondId = $this->mapper->save($this->createFutureTrainingModel('Next 2', date_add($now, new \DateInterval('P2D'))));

        $entries = $this->mapper->getNextTrainings(null, '1');

        self::assertCount(2, $entries);
        self::assertSame($firstId, $entries[0]->getId());
        self::assertSame($secondId, $entries[1]->getId());

        $limited = $this->mapper->getNextTrainings(1, '1');

        self::assertCount(1, $limited);
        self::assertSame($firstId, $limited[0]->getId());
    }

    /**
     * Tests that getNextTrainings() accepts a comma separated group id string like the
     * other methods (e.g. getTraining()) do.
     *
     * Note: This test currently FAILS. getNextTrainings() does not explode a string, so
     * '1,2' is sent to the database as a single value and matches no training.
     */
    public function testGetNextTrainingsGroupIdsString()
    {
        $now = new \Ilch\Date();

        $id = $this->mapper->save($this->createFutureTrainingModel('Next 1', date_add($now, new \DateInterval('P1D')), '2'));

        $entries = $this->mapper->getNextTrainings(5, '1,2');

        self::assertCount(1, $entries);
        self::assertSame($id, $entries[0]->getId());
    }

    /**
     * Tests that countdown() returns the remaining minutes when less than one hour is left.
     */
    public function testCountdownMinutes()
    {
        $date = new \Ilch\Date();
        $date = date_add($date, new \DateInterval('PT30M'));

        self::assertSame('30m', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns the remaining hours and minutes.
     */
    public function testCountdownHoursAndMinutes()
    {
        $date = new \Ilch\Date();
        $date = date_add($date, new \DateInterval('PT90M'));

        self::assertSame('1h 30m', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns the remaining days and hours.
     */
    public function testCountdownDaysAndHours()
    {
        $date = new \Ilch\Date();
        $date = date_add($date, new \DateInterval('PT26H'));

        self::assertSame('1d 2h', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns "live" when the training is starting/running.
     */
    public function testCountdownLive()
    {
        $date = new \Ilch\Date();
        $date = date_add($date, new \DateInterval('PT30S'));

        self::assertSame('live', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns false when the training ended more than the
     * countdown window (default 60 minutes) ago.
     */
    public function testCountdownEnded()
    {
        $date = new \Ilch\Date();
        $date = date_sub($date, new \DateInterval('PT2H'));

        self::assertFalse($this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() still returns "live" when the training ended only minutes ago
     * (within the default 60 minute countdown window).
     *
     * Note: This test currently FAILS. In countdown() the condition
     * "$difference <= (60 * $countdown_time)" is always true for a negative difference,
     * so every past date returns false and the "live" state is unreachable.
     */
    public function testCountdownRecentlyEnded()
    {
        $date = new \Ilch\Date();
        $date = date_sub($date, new \DateInterval('PT10M'));

        self::assertSame('live', $this->mapper->countdown($date));
    }

    /**
     * Should update the start and end date to the next matching weekday, because we are
     * dealing with a day based recurrent training, the initial date is in the past and
     * the repeat until date is in the future.
     *
     * Note: This test is expected to FAIL. The "days" branch passes a date expression like
     * "next Monday 05:00:00" to DateInterval::createFromDateString(), which only parses
     * durations and therefore throws a \DateMalformedPeriodStringException.
     *
     * @return void
     * @throws \DateMalformedPeriodStringException
     */
    public function testCalculateNextTrainingDayBased()
    {
        $date = new \Ilch\Date();
        $date = date_add($date, new \DateInterval('P1M'));
        $repeatUntil = $date->format('Y-m-d H:i:s');

        $model = new EntriesModel();
        $model->setId(1);
        $model->setTitle('Tag 10');
        $model->setDate('2024-10-15 05:00:00');
        $model->setEnd('2024-10-15 05:30:00');
        $model->setPeriodType('days');
        $model->setPeriodDay(1);
        $model->setRepeatUntil($repeatUntil);
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

    /**
     * Tests that saveAccess() does not add the admin group when addAdmin is false.
     */
    public function testSaveAccessWithoutAdmin()
    {
        $this->mapper->saveAccess(1, '2', false);

        $entry = $this->mapper->getTrainingById(1, null);

        self::assertNotNull($entry);
        self::assertSame('2', $entry->getReadAccess());
    }

    /**
     * Tests that delete() returns true and removes the training.
     */
    public function testDeleteReturnsTrue()
    {
        self::assertTrue($this->mapper->delete(1));
        self::assertNull($this->mapper->getTrainingById(1, null));
    }

    /**
     * Tests that delete() also removes the entrants of the training via the foreign key.
     */
    public function testDeleteTrainingCascades()
    {
        $entrantsMapper = new Entrants();

        $this->mapper->delete(1);

        self::assertNull($entrantsMapper->getEntrantsById(1));
    }

    /**
     * Tests that existsTable() returns false for a table that does not exist.
     */
    public function testExistsTableFalse()
    {
        self::assertFalse($this->mapper->existsTable('training_does_not_exist'));
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
