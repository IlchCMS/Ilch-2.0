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
use Modules\Vote\Mappers\Result as ResultMapper;
use Modules\Vote\Models\Result as EntriesModel;

/**
 * Tests for the Result mapper of the Vote module
 *
 * @package ilch_phpunit
 */
class ResultTest extends DatabaseTestCase
{
    /** @var PhpunitDataset */
    protected PhpunitDataset $phpunitDataset;
    /** @var ResultMapper */
    private Result $mapper;

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

        $this->mapper = new ResultMapper();

        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Test that getEntriesBy() returns all ten result rows from the fixture
     *
     * @return void
     */
    public function testVoteResultGetAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(10, $entries);
    }

    /**
     * Test that every fixture row holds the expected poll id, reply and result
     *
     * @return void
     */
    public function testGetVoteResul()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(10, $entries);

        $i = 0;
        /** @var EntriesModel $entry */
        $entry = $entries[$i];
        self::assertEquals(1, $entry->getPollId());
        self::assertEquals('Antwort 1.1', $entry->getReply());
        self::assertEquals(0, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(1, $entry->getPollId());
        self::assertEquals('Antwort 1.2', $entry->getReply());
        self::assertEquals(1, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(2, $entry->getPollId());
        self::assertEquals('Antwort 2.1', $entry->getReply());
        self::assertEquals(1, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(2, $entry->getPollId());
        self::assertEquals('Antwort 2.2', $entry->getReply());
        self::assertEquals(2, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(3, $entry->getPollId());
        self::assertEquals('Antwort 3.1', $entry->getReply());
        self::assertEquals(0, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(3, $entry->getPollId());
        self::assertEquals('Antwort 3.2', $entry->getReply());
        self::assertEquals(0, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(4, $entry->getPollId());
        self::assertEquals('Antwort 4.1', $entry->getReply());
        self::assertEquals(0, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(4, $entry->getPollId());
        self::assertEquals('Antwort 4.2', $entry->getReply());
        self::assertEquals(0, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(5, $entry->getPollId());
        self::assertEquals('Antwort 5.1', $entry->getReply());
        self::assertEquals(0, $entry->getResult());

        $i++;
        $entry = $entries[$i];
        self::assertEquals(5, $entry->getPollId());
        self::assertEquals('Antwort 5.2', $entry->getReply());
        self::assertEquals(0, $entry->getResult());
    }

    /**
     * Test that a new result can be saved and fetched again via getResultByIdAndReply()
     *
     * @return void
     */
    public function testSaveNewVoteResult()
    {
        $model = new EntriesModel();
        $model->setResult(5);
        $model->setPollId(5);
        $model->setReply('Antwort 5.3');
        $this->mapper->saveReply($model);

        $result = $this->mapper->getResultByIdAndReply($model->getPollId(), $model->getReply());

        self::assertEquals($model->getResult(), $result);
    }

    /**
     * Test that an existing result can be updated via saveResult()
     *
     * @return void
     */
    public function testUpdateVoteResult()
    {
        $model = new EntriesModel();
        $model->setPollId(1);
        $model->setResult(100);
        $model->setReply('Antwort 1.1');
        $this->mapper->saveResult($model);

        $result = $this->mapper->getResultByIdAndReply($model->getPollId(), $model->getReply());
        self::assertEquals($model->getResult(), $result);
    }

    /**
     * Test that delete() removes all result rows of the given poll
     *
     * @return void
     */
    public function testDeleteVoteResult()
    {
        $this->mapper->delete(5);

        $entry = $this->mapper->getEntriesBy(['poll_id' => 5]);
        self::assertNull($entry);
    }

    /**
     * Test that resetResult() sets every result of the given poll to zero
     *
     * @return void
     */
    public function testResetVoteResult()
    {
        self::assertTrue($this->mapper->resetResult(1));

        $entries = $this->mapper->getVoteRes(1);
        foreach ($entries as $entry) {
            self::assertEquals(0, $entry->getResult());
        }
    }

    /**
     * Test that getVoteRes() returns the result rows of the given poll
     *
     * @return void
     */
    public function testGetVoteRes()
    {
        $entries = $this->mapper->getVoteRes(1);

        self::assertNotNull($entries);
        self::assertCount(2, $entries);
        self::assertInstanceOf(EntriesModel::class, $entries[0]);

        self::assertEquals(1, $entries[0]->getPollId());
        self::assertEquals('Antwort 1.1', $entries[0]->getReply());
        self::assertEquals(0, $entries[0]->getResult());

        self::assertEquals(1, $entries[1]->getPollId());
        self::assertEquals('Antwort 1.2', $entries[1]->getReply());
        self::assertEquals(1, $entries[1]->getResult());
    }

    /**
     * Test that getVoteRes() returns null for an unknown poll
     *
     * @return void
     */
    public function testGetVoteResNotFound()
    {
        self::assertNull($this->mapper->getVoteRes(9999));
    }

    /**
     * Test that getEntriesBy() filters the result rows by poll id
     *
     * @return void
     */
    public function testGetEntriesByWithWhere()
    {
        $entries = $this->mapper->getEntriesBy(['poll_id' => 5]);

        self::assertCount(2, $entries);

        foreach ($entries as $entry) {
            self::assertEquals(5, $entry->getPollId());
        }
    }

    /**
     * Test that getResultById() returns the sum of all results of the given poll
     *
     * @return void
     */
    public function testGetResultById()
    {
        // poll 1: 0 + 1, poll 2: 1 + 2
        self::assertEquals(1, $this->mapper->getResultById(1));
        self::assertEquals(3, $this->mapper->getResultById(2));
    }

    /**
     * Test that getResultById() returns 0 for a poll without entries
     *
     * @return void
     */
    public function testGetResultByIdWithoutEntries()
    {
        self::assertEquals(0, $this->mapper->getResultById(9999));
    }

    /**
     * Test that getResultByIdAndReply() returns 0 for unknown poll id / reply combinations
     *
     * @return void
     */
    public function testGetResultByIdAndReplyNotFound()
    {
        self::assertEquals(0, $this->mapper->getResultByIdAndReply(1, 'unbekannte Antwort'));
        self::assertEquals(0, $this->mapper->getResultByIdAndReply(9999, 'Antwort 1.1'));
    }

    /**
     * Test that saving a new result increases the total row count
     *
     * @return void
     */
    public function testSaveNewVoteResultIncreasesCount()
    {
        $model = new EntriesModel();
        $model->setResult(5);
        $model->setPollId(5);
        $model->setReply('Antwort 5.3');
        $this->mapper->saveReply($model);

        self::assertCount(11, $this->mapper->getEntriesBy());
    }

    /**
     * Test that updating one result leaves the other rows untouched
     *
     * @return void
     */
    public function testUpdateVoteResultDoesNotAffectOthers()
    {
        $model = new EntriesModel();
        $model->setPollId(1);
        $model->setResult(100);
        $model->setReply('Antwort 1.1');
        $this->mapper->saveResult($model);

        self::assertEquals(100, $this->mapper->getResultByIdAndReply(1, 'Antwort 1.1'));

        // Other reply of the same poll stays unchanged
        self::assertEquals(1, $this->mapper->getResultByIdAndReply(1, 'Antwort 1.2'));

        // Other polls stay unchanged
        self::assertEquals(3, $this->mapper->getResultById(2));
    }

    /**
     * Test that resetResult() also removes the IP entries of the given poll
     *
     * @return void
     */
    public function testResetVoteResultDeletesIpEntries()
    {
        self::assertTrue($this->mapper->resetResult(1));

        $ipMapper = new Ip();

        // Ip entries of the reset poll are gone
        self::assertNull($ipMapper->getEntriesBy(['poll_id' => 1]));

        // Ip entries of other polls stay
        self::assertCount(3, $ipMapper->getEntriesBy(['poll_id' => 2]));
    }

    /**
     * Test that delete() also removes the IP entries of the given poll
     *
     * @return void
     */
    public function testDeleteVoteResultRemovesIpEntries()
    {
        self::assertTrue($this->mapper->delete(2));

        self::assertNull($this->mapper->getVoteRes(2));

        $ipMapper = new Ip();

        // Ip entries of the deleted poll are gone
        self::assertNull($ipMapper->getEntriesBy(['poll_id' => 2]));

        // Results of other polls stay
        self::assertCount(2, $this->mapper->getVoteRes(1));
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
