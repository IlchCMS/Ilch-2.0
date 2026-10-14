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
use Modules\User\Models\User;
use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\Vote\Models\Ip as IpModel;
use Modules\Vote\Models\Vote as EntriesModel;

/**
 * Tests for the Vote mapper of the Vote module
 *
 * @package ilch_phpunit
 */
class VoteTest extends DatabaseTestCase
{
    /** @var PhpunitDataset */
    protected PhpunitDataset $phpunitDataset;
    /** @var VoteMapper */
    private Vote $mapper;

    /**
     * Set up the test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new VoteMapper();

        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Test that getEntriesBy() returns all five fixture votes and that
     * getLastId() returns the highest vote id
     *
     * @return void
     */
    public function testGetVoteAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(5, $entries);

        self::assertEquals(5, $this->mapper->getLastId());
    }

    /**
     * Test that every fixture vote holds the expected values
     *
     * @return void
     */
    public function testGetVote()
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

    /**
     * Test that getVotes() returns all votes for the given group ids
     *
     * @return void
     */
    public function testGetVoteByAccessArray()
    {
        $entries = $this->mapper->getVotes([], [1, 2, 3]);

        self::assertCount(5, $entries);
    }

    /**
     * Test that getVotes() returns only the votes readable by guests
     *
     * @return void
     */
    public function testGetVoteByAccessGuest()
    {
        $entries = $this->mapper->getVotes([], [3]);

        self::assertCount(3, $entries);
    }

    /**
     * Test that getVotes() defaults to the guest group when no groups are given
     *
     * @return void
     */
    public function testGetVoteByAccessGuestDefault()
    {
        $entries = $this->mapper->getVotes();

        self::assertCount(3, $entries);
    }

    /**
     * Test that a new vote can be saved and read back with the normalized read access
     *
     * @return void
     */
    public function testSaveNewVote()
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

        // Set the id returned by the first save(), so the second save() call
        // updates the existing row instead of inserting a new one.
        $model->setId($id);
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

        // No additional row was created by the update.
        self::assertCount(6, $this->mapper->getEntriesBy());
    }

    /**
     * Test that a new vote is saved and that the admin group (1) is
     * prepended to the given read access
     *
     * @return void
     */
    public function testSaveUpdateExistingVote()
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

    /**
     * Test that saveReadAccess() updates the read access of an existing vote
     *
     * @return void
     */
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

    /**
     * Test that delete() removes the vote with the given id
     *
     * @return void
     */
    public function testDeleteVote()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getVoteById(1);
        self::assertNull($entry);
    }

    /**
     * Test that lock() locks a vote when no explicit status is given
     *
     * @return void
     */
    public function testLockVote()
    {
        self::assertSame(true, $this->mapper->lock(1));

        $entry = $this->mapper->getVoteById(1);
        self::assertTrue($entry->getStatus());
    }

    /**
     * Test that getVoteById() returns null for an unknown id
     *
     * @return void
     */
    public function testGetVoteByIdNotFound()
    {
        self::assertNull($this->mapper->getVoteById(9999));
    }

    /**
     * Test that passing null as groups skips the read access filter
     *
     * @return void
     */
    public function testGetVotesNoReadAccess()
    {
        // Passing null skips the read-access filter, so all votes are returned
        $entries = $this->mapper->getVotes([], null);

        self::assertCount(5, $entries);
    }

    /**
     * Test that only the votes with global read access remain for an unknown group
     *
     * @return void
     */
    public function testGetVotesUnknownGroup()
    {
        // No poll grants access to group 999, only the read_access_all poll remains
        $entries = $this->mapper->getVotes([], [999]);

        self::assertCount(1, $entries);
        self::assertEquals(3, $entries[0]->getId());
    }

    /**
     * Test that getVotes() returns the votes ordered by descending id
     *
     * @return void
     */
    public function testGetVotesAreSortedByDescendingId()
    {
        $entries = $this->mapper->getVotes([], [1, 2, 3]);

        self::assertCount(5, $entries);
        self::assertEquals(5, $entries[0]->getId());
        self::assertEquals(4, $entries[1]->getId());
        self::assertEquals(3, $entries[2]->getId());
        self::assertEquals(2, $entries[3]->getId());
        self::assertEquals(1, $entries[4]->getId());
    }

    /**
     * Test that saving a new vote returns the id of the new row
     *
     * @return void
     */
    public function testSaveNewVoteReturnsNewId()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setQuestion('Frage 6');
        $model->setKey('frage6');
        $model->setGroups('2');
        $model->setMultipleReply(false);
        $model->setReadAccess('2,3');
        $model->setStatus(false);

        $id = $this->mapper->save($model);

        self::assertGreaterThan(5, $id);
        self::assertEquals($id, $this->mapper->getLastId());
    }

    /**
     * Test that saving a vote with an existing id updates the row instead of inserting a new one
     *
     * @return void
     */
    public function testSaveUpdatesExistingVote()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setQuestion('Frage 1 (aktualisiert)');
        $model->setKey('frage1');
        $model->setGroups('1,2');
        $model->setMultipleReply(true);
        $model->setReadAccess('1,2');
        $model->setStatus(true);

        $id = $this->mapper->save($model);

        self::assertEquals(1, $id);

        $entry = $this->mapper->getVoteById(1);
        self::assertNotNull($entry);
        self::assertEquals('Frage 1 (aktualisiert)', $entry->getQuestion());
        self::assertEquals('1,2', $entry->getGroups());
        self::assertTrue($entry->getMultipleReply());
        self::assertEquals('1,2', $entry->getReadAccess());
        self::assertTrue($entry->getStatus());

        // No additional row was created
        self::assertCount(5, $this->mapper->getEntriesBy());
    }

    /**
     * Test that updating one vote leaves the other votes untouched
     *
     * @return void
     */
    public function testSaveUpdateDoesNotAffectOtherVotes()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setQuestion('Geaendert');
        $model->setGroups('2');
        $model->setMultipleReply(false);
        $model->setReadAccess('2');
        $model->setStatus(false);

        $this->mapper->save($model);

        $other = $this->mapper->getVoteById(2);
        self::assertNotNull($other);
        self::assertEquals('Frage 2', $other->getQuestion());
        self::assertTrue($other->getStatus());
    }

    /**
     * Test that saveReadAccess() accepts an array of group ids
     *
     * @return void
     */
    public function testSaveReadAccessWithArray()
    {
        $this->mapper->saveReadAccess(1, [2, 3]);

        $entry = $this->mapper->getVoteById(1);

        self::assertNotNull($entry);
        self::assertEquals('1,2,3', $entry->getReadAccess());
    }

    /**
     * Test that saveReadAccess() adds the admin group by default and returns the group ids
     *
     * @return void
     */
    public function testSaveReadAccessAddsAdminGroup()
    {
        $groupIds = $this->mapper->saveReadAccess(1, '3');

        self::assertSame(['3', '1'], $groupIds);

        $entry = $this->mapper->getVoteById(1);
        self::assertEquals('1,3', $entry->getReadAccess());
    }

    /**
     * Test that saveReadAccess() can skip adding the admin group
     *
     * @return void
     */
    public function testSaveReadAccessWithoutAdminGroup()
    {
        $groupIds = $this->mapper->saveReadAccess(1, '2,3', false);

        self::assertSame(['2', '3'], $groupIds);

        $entry = $this->mapper->getVoteById(1);
        self::assertEquals('2,3', $entry->getReadAccess());
    }

    /**
     * Test that deleting a vote also removes the related result and IP rows
     *
     * @return void
     */
    public function testDeleteVoteRemovesRelatedRows()
    {
        self::assertTrue($this->mapper->delete(1));

        self::assertNull($this->mapper->getVoteById(1));

        // Related rows are removed (explicitly by the mapper, previously via FK ON DELETE CASCADE)
        $resultMapper = new Result();
        self::assertNull($resultMapper->getVoteRes(1));

        $ipMapper = new Ip();
        self::assertNull($ipMapper->getEntriesBy(['poll_id' => 1]));

        // Other votes remain
        self::assertCount(4, $this->mapper->getEntriesBy());
    }

    /**
     * Test that lock() accepts an explicit status value
     *
     * @return void
     */
    public function testLockVoteWithExplicitStatus()
    {
        self::assertSame(true, $this->mapper->lock(1, 1));
        self::assertTrue($this->mapper->getVoteById(1)->getStatus());

        self::assertSame(true, $this->mapper->lock(1, 0));
        self::assertFalse($this->mapper->getVoteById(1)->getStatus());
    }

    /**
     * Test that lock() without an explicit status toggles the current status
     *
     * @return void
     */
    public function testLockVoteTogglesStatus()
    {
        // Poll 2 starts locked (status 1)
        self::assertTrue($this->mapper->getVoteById(2)->getStatus());

        self::assertSame(true, $this->mapper->lock(2));
        self::assertFalse($this->mapper->getVoteById(2)->getStatus());

        self::assertSame(true, $this->mapper->lock(2));
        self::assertTrue($this->mapper->getVoteById(2)->getStatus());
    }

    /**
     * canVote() is a model method, but it depends on the DB (via the Ip
     * mapper), so it is tested here against the fixture data.
     *
     * Test that canVote() allows voting for a group that is granted on the vote
     *
     * @return void
     */
    public function testCanVoteGroupAllowed()
    {
        $vote = $this->mapper->getVoteById(1); // groups: '2', status: 0

        self::assertTrue($vote->canVote('10.0.0.1', null, [2]));
    }

    /**
     * Test that canVote() denies voting for a group that is not granted on the vote
     *
     * @return void
     */
    public function testCanVoteGroupNotAllowed()
    {
        $vote = $this->mapper->getVoteById(1); // groups: '2', status: 0

        self::assertFalse($vote->canVote('10.0.0.1', null, [3]));
    }

    /**
     * Test that canVote() denies voting for an IP that has already voted on the poll
     *
     * @return void
     */
    public function testCanVoteAlreadyVotedByIp()
    {
        $vote = $this->mapper->getVoteById(1);

        // 192.168.10.100 has already voted on poll 1 (see fixture)
        self::assertFalse($vote->canVote('192.168.10.100', null, [2]));
    }

    /**
     * Test that canVote() denies voting for a user that has already voted on the poll
     *
     * @return void
     */
    public function testCanVoteAlreadyVotedByUser()
    {
        $vote = $this->mapper->getVoteById(1);

        // Simulate that user 7 has already voted on poll 1
        $ipMapper = new Ip();
        $ipModel = new IpModel();
        $ipModel->setPollId(1)->setIP('10.9.9.9')->setUserId(7);
        $ipMapper->saveIP($ipModel);

        $user = new User();
        $user->setId(7);

        self::assertFalse($vote->canVote('10.0.0.1', $user, [2]));
    }

    /**
     * Test that canVote() denies voting on a locked vote
     *
     * @return void
     */
    public function testCanVoteLockedVote()
    {
        $vote = $this->mapper->getVoteById(2); // status: 1

        self::assertFalse($vote->canVote('10.0.0.1', null, [2]));
    }

    /**
     * Test that a vote with the groups value 'all' allows guests to vote
     *
     * @return void
     */
    public function testCanVoteGroupsAllAllowsGuest()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setQuestion('Frage 7');
        $model->setKey('frage7');
        $model->setGroups('all');
        $model->setMultipleReply(false);
        $model->setReadAccess('1,2,3');
        $model->setStatus(false);

        $id = $this->mapper->save($model);

        $vote = $this->mapper->getVoteById($id);
        self::assertNotNull($vote);

        // groups: 'all' → every group (here: guest) may vote
        self::assertTrue($vote->canVote('10.0.0.1', null, [3]));
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
