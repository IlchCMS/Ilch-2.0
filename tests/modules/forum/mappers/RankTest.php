<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\Forum\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use \Modules\Forum\Mappers\Rank as RankMapper;
use \Modules\Forum\Models\Rank as RankModel;
use \Modules\Forum\Config\Config as ModuleConfig;

/**
 * Tests the rank mapper class.
 *
 * @package ilch_phpunit
 */
class RankTest extends DatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Tests if getRanks() returns all ranks from the database.
     */
    public function testGetRanks()
    {
        $mapper = new RankMapper();
        $ranks = $mapper->getRanks();

        $this->assertTrue(count($ranks) == 3);
    }

    /**
     * Tests if getRankById() returns the expected rank.
     */
    public function testGetRankById()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankById(1);

        $this->assertTrue($rank->getId() == 1);
        $this->assertTrue($rank->getTitle() == 'Gruenschnabel');
        $this->assertTrue($rank->getPosts() == 0);
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     */
    public function testGetRankByPosts()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(1);

        $this->assertTrue($rank->getId() == 1);
        $this->assertTrue($rank->getTitle() == 'Gruenschnabel');
        $this->assertTrue($rank->getPosts() == 0);
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     */
    public function testGetRankByPostsExact()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(25);

        $this->assertTrue($rank->getId() == 2);
        $this->assertTrue($rank->getTitle() == 'Jungspund');
        $this->assertTrue($rank->getPosts() == 25);
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     * Above needed posts for a rank, but below needed posts for the next rank.
     */
    public function testGetRankByPostsAbove()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(30);

        $this->assertTrue($rank->getId() == 2);
        $this->assertTrue($rank->getTitle() == 'Jungspund');
        $this->assertTrue($rank->getPosts() == 25);
    }

    /**
     * Tests if getRankByPosts() returns the expected rank.
     */
    public function testGetRankByPostsHigh()
    {
        $mapper = new RankMapper();
        $rank = $mapper->getRankByPosts(50);

        $this->assertTrue($rank->getId() == 3);
        $this->assertTrue($rank->getTitle() == 'Mitglied');
        $this->assertTrue($rank->getPosts() == 50);
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

        $this->assertTrue($rank->getId() == 4);
        $this->assertTrue($rank->getTitle() == 'TestTitle');
        $this->assertTrue($rank->getPosts() == 100);
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

        $this->assertTrue($rank->getId() == 3);
        $this->assertTrue($rank->getTitle() == 'TestTitle');
        $this->assertTrue($rank->getPosts() == 100);
    }

    /**
     * Tests if delete() deletes the rank.
     */
    public function testDelete()
    {
        $mapper = new RankMapper();

        $mapper->delete(3);
        $ranks = $mapper->getRanks();

        $this->assertTrue(count($ranks) == 2);
    }

    /**
     * Creates and returns a dataset object.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_AbstractDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet(__DIR__ . '/../_files/mysql_database.yml');
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
