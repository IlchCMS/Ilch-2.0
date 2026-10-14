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
 * Tests for the Ip mapper of the Vote module
 *
 * @package ilch_phpunit
 */
class IpTest extends DatabaseTestCase
{
    /** @var PhpunitDataset */
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    /** @var IpMapper */
    private Ip $mapper;

    /**
     * Set up the test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new IpMapper();

        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Test that getEntriesBy() returns all four IP rows from the fixture
     *
     * @return void
     */
    public function testVoteIpGetAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(4, $entries);
    }

    /**
     * Test that every fixture row holds the expected poll id, ip and user id
     *
     * @return void
     */
    public function testGetVoteIp()
    {
        // Use an explicit order: without ORDER BY the row order depends on the
        // access path the optimizer picks (table scan in insertion order vs.
        // index scan on the unique (poll_id, ip) index), which is not stable.
        $entries = $this->mapper->getEntriesBy([], ['poll_id' => 'ASC', 'ip' => 'ASC']);

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
    }

    /**
     * Test that a new IP entry can be saved and is found via getIP() and getVotedUser()
     *
     * @return void
     */
    public function testSaveNewVoteIp()
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
     * Test that getEntriesBy() filters the rows by poll id
     *
     * @return void
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->mapper->getEntriesBy(['poll_id' => 2]);

        self::assertCount(3, $entries);
        self::assertInstanceOf(EntriesModel::class, $entries[0]);

        foreach ($entries as $entry) {
            self::assertEquals(2, $entry->getPollId());
        }
    }

    /**
     * Test that getEntriesBy() returns null when no row matches the given where clause
     *
     * @return void
     */
    public function testGetEntriesByWithWhereNotFound()
    {
        self::assertNull($this->mapper->getEntriesBy(['poll_id' => 9999]));
    }

    /**
     * Test that getEntriesBy() respects a custom order by clause
     *
     * @return void
     */
    public function testGetEntriesByWithOrderBy()
    {
        $entries = $this->mapper->getEntriesBy([], ['poll_id' => 'DESC', 'ip' => 'ASC']);

        self::assertCount(4, $entries);

        self::assertEquals(2, $entries[0]->getPollId());
        self::assertEquals('192.168.10.100', $entries[0]->getIP());

        self::assertEquals(2, $entries[1]->getPollId());
        self::assertEquals('192.168.10.101', $entries[1]->getIP());

        self::assertEquals(2, $entries[2]->getPollId());
        self::assertEquals('192.168.10.102', $entries[2]->getIP());

        self::assertEquals(1, $entries[3]->getPollId());
        self::assertEquals('192.168.10.100', $entries[3]->getIP());
    }

    /**
     * Test that getEntriesBy() works when a pagination object is passed
     *
     * @return void
     */
    public function testGetEntriesByWithPagination()
    {
        $pagination = new \Ilch\Pagination();

        $entries = $this->mapper->getEntriesBy([], [], $pagination);

        self::assertNotNull($entries);
        self::assertCount(4, $entries);
    }

    /**
     * Test that getIP() returns null for unknown poll id / ip combinations
     *
     * @return void
     */
    public function testGetIPNotFound()
    {
        self::assertNull($this->mapper->getIP(1, '10.0.0.1'));
        self::assertNull($this->mapper->getIP(9999, '192.168.10.100'));
    }

    /**
     * Test that getVotedUser() finds a user vote that exists in the fixture
     *
     * @return void
     */
    public function testGetVotedUserFromFixture()
    {
        // Poll 2: user 1 voted from 192.168.10.101 (see fixture)
        $entry = $this->mapper->getVotedUser(2, 1);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getPollId());
        self::assertEquals('192.168.10.101', $entry->getIP());
        self::assertEquals(1, $entry->getUserId());
    }

    /**
     * Test that getVotedUser() returns null for a null user id
     *
     * @return void
     */
    public function testGetVotedUserWithNullUserId()
    {
        self::assertNull($this->mapper->getVotedUser(1, null));
    }

    /**
     * Test that getVotedUser() returns null for a zero user id
     *
     * @return void
     */
    public function testGetVotedUserWithZeroUserId()
    {
        self::assertNull($this->mapper->getVotedUser(1, 0));
    }

    /**
     * Test that getVotedUser() returns null when the user has not voted on the poll
     *
     * @return void
     */
    public function testGetVotedUserNotFound()
    {
        self::assertNull($this->mapper->getVotedUser(1, 9999));
    }

    /**
     * Test that saving a new IP entry increases the total row count
     *
     * @return void
     */
    public function testSaveNewVoteIpIncreasesCount()
    {
        $model = new EntriesModel();
        $model->setPollId(1)
            ->setIP('192.168.10.120')
            ->setUserId(0);

        $this->mapper->saveIP($model);

        self::assertCount(5, $this->mapper->getEntriesBy());
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
