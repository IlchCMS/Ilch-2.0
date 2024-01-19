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
use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\Vote\Models\Vote as EntriesModel;

/**
 * @package ilch_phpunit
 */
class VoteTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    /** @var VoteMapper $mapper */
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new VoteMapper();

        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * All test routines
     */
    public function testgetVoteAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(5, $entries);

        self::assertEquals(5, $this->mapper->getLastId());
    }

    public function testgetVote()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(5, $entries);

        $i = 0;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals('2', $entry->getGroups());
        self::assertEquals('Frage 1', $entry->getQuestion());
        self::assertFalse($entry->getMultipleReply());
        self::assertEquals('1,2,3', $entry->getReadAccess());
        self::assertFalse($entry->getStatus());

        $i++;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals('2', $entry->getGroups());
        self::assertEquals('Frage 2', $entry->getQuestion());
        self::assertFalse($entry->getMultipleReply());
        self::assertEquals('1,2', $entry->getReadAccess());
        self::assertTrue($entry->getStatus());

        $i++;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals('2', $entry->getGroups());
        self::assertEquals('Frage 3', $entry->getQuestion());
        self::assertFalse($entry->getMultipleReply());
        self::assertEquals('all', $entry->getReadAccess());
        self::assertFalse($entry->getStatus());

        $i++;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals('1', $entry->getGroups());
        self::assertEquals('Frage 4', $entry->getQuestion());
        self::assertFalse($entry->getMultipleReply());
        self::assertEquals('1,2', $entry->getReadAccess());
        self::assertFalse($entry->getStatus());

        $i++;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals('1', $entry->getGroups());
        self::assertEquals('Frage 5', $entry->getQuestion());
        self::assertTrue($entry->getMultipleReply());
        self::assertEquals('1,2,3', $entry->getReadAccess());
        self::assertFalse($entry->getStatus());
    }

    public function testGetVoteByAccessArray()
    {
        $entries = $this->mapper->getVotes([], [1, 2, 3]);

        self::assertCount(5, $entries);
    }

    public function testGetVoteByAccessGuest()
    {
        $entries = $this->mapper->getVotes([], [3]);

        self::assertCount(3, $entries);
    }

    public function testGetVoteByAccessGuestDefault()
    {
        $entries = $this->mapper->getVotes();

        self::assertCount(3, $entries);
    }

    public function testsaveNewVote()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setGroups('2,3');
        $model->setQuestion('Frage 6');
        $model->setMultipleReply(true);
        $model->setReadAccess('2,3,1');
        $model->setStatus(true);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getVoteById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getGroups(), $entry->getGroups());
        self::assertEquals($model->getQuestion(), $entry->getQuestion());
        self::assertEquals($model->getMultipleReply(), $entry->getMultipleReply());
        self::assertEquals('1,2,3', $entry->getReadAccess());
        self::assertEquals($model->getStatus(), $entry->getStatus());

        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getVoteById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getGroups(), $entry->getGroups());
        self::assertEquals($model->getQuestion(), $entry->getQuestion());
        self::assertEquals($model->getMultipleReply(), $entry->getMultipleReply());
        self::assertEquals('all', $entry->getReadAccess());
        self::assertEquals($model->getStatus(), $entry->getStatus());
    }

    public function testsaveUpdateExistingVote()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setGroups(4);
        $model->setQuestion('Team 4');
        $model->setMultipleReply('');
        $model->setReadAccess('3');
        $model->setStatus('1');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getVoteById($id);

        self::assertNotNull($entry);
        self::assertEquals(6, $id);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getGroups(), $entry->getGroups());
        self::assertEquals($model->getQuestion(), $entry->getQuestion());
        self::assertEquals($model->getMultipleReply(), $entry->getMultipleReply());
        self::assertEquals('1,' . $model->getReadAccess(), $entry->getReadAccess());
        self::assertEquals($model->getStatus(), $entry->getStatus());
    }

    public function testUpdateVoteAccess()
    {
        $this->mapper->saveReadAccess(1, 'all');

        $entry = $this->mapper->getVoteById(1);

        self::assertNotNull($entry);
        self::assertEquals('1', $entry->getReadAccess());

        $this->mapper->saveReadAccess(2, '2,3');

        $entry = $this->mapper->getVoteById(2);

        self::assertNotNull($entry);
        self::assertEquals('1,2,3', $entry->getReadAccess());
    }

    public function testdeleteVote()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getVoteById(1);
        self::assertNull($entry);
    }

    public function testLockVote()
    {
        self::assertSame(true, $this->mapper->lock(1));

        $entry = $this->mapper->getVoteById(1);
        self::assertTrue($entry->getStatus());
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
