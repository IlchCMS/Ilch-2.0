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
use Modules\Vote\Mappers\Result as ResultMapper;
use Modules\Vote\Models\Result as EntriesModel;

/**
 * @package ilch_phpunit
 */
class ResultTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    /** @var ResultMapper $mapper */
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new ResultMapper();

        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * All test routines
     */
    public function testVoteResultGetAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(10, $entries);
    }

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

    public function testDeleteVoteResult()
    {
        $this->mapper->delete(5);

        $entry = $this->mapper->getEntriesBy(['poll_id' => 5]);
        self::assertNull($entry);
    }

    public function testResetVoteResult()
    {
        self::assertTrue($this->mapper->resetResult(1));

        $entries = $this->mapper->getVoteRes(1);
        foreach ($entries as $entry) {
            self::assertEquals(0, $entry->getResult());
        }
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
