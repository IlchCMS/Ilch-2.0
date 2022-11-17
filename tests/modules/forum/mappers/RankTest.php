<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use Modules\Forum\Mappers\Rank as RankMapper;
use Modules\Forum\Models\Rank as RankModel;
use Modules\Forum\Config\Config as ModuleConfig;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the rank mapper class.
 *
 * @package ilch_phpunit
 */
class RankTest extends DatabaseTestCase
{
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Tests if getRanks() returns all ranks from the database.
     */
    public function testGetRanks()
    {
        $mapper = new RankMapper();
        $ranks = $mapper->getRanks();

        self::assertCount(3, $ranks);
    }

    /**
     * Tests if getRankById() returns the expected rank.
     */
    public function testGetRankById()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankById(1);

        self::assertEquals(1, $rank->getId());
        self::assertSame($rank->getTitle(), 'Gruenschnabel');
        self::assertEquals(0, $rank->getPosts());
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     */
    public function testGetRankByPosts()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(1);

        self::assertEquals(1, $rank->getId());
        self::assertSame($rank->getTitle(), 'Gruenschnabel');
        self::assertEquals(0, $rank->getPosts());
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     */
    public function testGetRankByPostsExact()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(25);

        self::assertEquals(2, $rank->getId());
        self::assertSame($rank->getTitle(), 'Jungspund');
        self::assertEquals(25, $rank->getPosts());
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     * Above needed posts for a rank, but below needed posts for the next rank.
     */
    public function testGetRankByPostsAbove()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(30);

        self::assertEquals(2, $rank->getId());
        self::assertSame($rank->getTitle(), 'Jungspund');
        self::assertEquals(25, $rank->getPosts());
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     */
    public function testGetRankByPostsHigh()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(50);

        self::assertEquals(3, $rank->getId());
        self::assertSame($rank->getTitle(), 'Mitglied');
        self::assertEquals(50, $rank->getPosts());
    }

    /**
     * Tests if save() add the rank.
     */
    public function testSaveAdd()
    {
        $mapper = new RankMapper();
        $model = new RankModel();

        $model->setTitle('TestTitle');
        $model->setPosts(100);

        $mapper->save($model);

        $rank = $mapper->getRankById(4);

        self::assertEquals(4, $rank->getId());
        self::assertSame($rank->getTitle(), 'TestTitle');
        self::assertEquals(100, $rank->getPosts());
    }

    /**
     * Tests if save() updates the existing rank.
     */
    public function testSaveEdit()
    {
        $mapper = new RankMapper();
        $model = new RankModel();

        $model->setId(3);
        $model->setTitle('TestTitle');
        $model->setPosts(100);

        $mapper->save($model);

        $rank = $mapper->getRankById(3);

        self::assertEquals(3, $rank->getId());
        self::assertSame($rank->getTitle(), 'TestTitle');
        self::assertEquals(100, $rank->getPosts());
    }

    /**
     * Tests if delete() deletes the rank.
     */
    public function testDelete()
    {
        $mapper = new RankMapper();

        $mapper->delete(3);
        $ranks = $mapper->getRanks();

        self::assertCount(2, $ranks);
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        $installSql = 'CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                `moduleKey` VARCHAR(255) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                `desc` VARCHAR(255) NOT NULL,
                `text` TEXT NOT NULL,
                `locale` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        $config = new ModuleConfig();
        return $installSql.$config->getInstallSql();
    }
}
