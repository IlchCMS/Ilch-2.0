<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\War\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\War\Models\Games as EntriesModel;

/**
 * @package ilch_phpunit
 */
class GamesTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Games $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new GamesMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarGamesAllRows()
    {
        $entries = $this->mapper->getGamesByWhere();

        self::assertCount(5, $entries);
    }

    public function testgetWarGames()
    {
        $entries = $this->mapper->getGamesByWhere();

        self::assertCount(5, $entries);

        $i = 0;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertEquals(4, $entries[$i]->getWarId());
        self::assertEquals(2, $entries[$i]->getMap());
        self::assertEquals(2, $entries[$i]->getGroupPoints());
        self::assertEquals(2, $entries[$i]->getEnemyPoints());

        $i++;
        self::assertEquals(5, $entries[$i]->getId());
        self::assertEquals(4, $entries[$i]->getWarId());
        self::assertEquals(1, $entries[$i]->getMap());
        self::assertEquals(1, $entries[$i]->getGroupPoints());
        self::assertEquals(2, $entries[$i]->getEnemyPoints());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertEquals(3, $entries[$i]->getWarId());
        self::assertEquals(1, $entries[$i]->getMap());
        self::assertEquals(2, $entries[$i]->getGroupPoints());
        self::assertEquals(2, $entries[$i]->getEnemyPoints());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getWarId());
        self::assertEquals(2, $entries[$i]->getMap());
        self::assertEquals(2, $entries[$i]->getGroupPoints());
        self::assertEquals(2, $entries[$i]->getEnemyPoints());

        $i++;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertEquals(1, $entries[$i]->getWarId());
        self::assertEquals(1, $entries[$i]->getMap());
        self::assertEquals(2, $entries[$i]->getGroupPoints());
        self::assertEquals(2, $entries[$i]->getEnemyPoints());
    }

    public function testsaveNewWarGames()
    {
        $model = new EntriesModel();
        $model->setWarId(2);
        $model->setMap(1);
        $model->setGroupPoints(3);
        $model->setEnemyPoints(3);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntriesBy(['id' => $id]);
        $entry = reset($entry);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals(2, $entry->getWarId());
        self::assertEquals(1, $entry->getMap());
        self::assertEquals(3, $entry->getGroupPoints());
        self::assertEquals(3, $entry->getEnemyPoints());
    }

    public function testsaveUpdateExistingWarGames()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setWarId(2);
        $model->setMap(1);
        $model->setGroupPoints(3);
        $model->setEnemyPoints(3);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntriesBy(['id' => $id]);
        $entry = reset($entry);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertEquals(2, $entry->getWarId());
        self::assertEquals(1, $entry->getMap());
        self::assertEquals(3, $entry->getGroupPoints());
        self::assertEquals(3, $entry->getEnemyPoints());
    }

    public function testdeleteWarGames()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntriesBy(['id' => 1]);
        self::assertNull($entry);
    }

    /**
     * Tests that checkDB() returns true if the table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getGamesByWarId() returns the games of the given war.
     */
    public function testGetGamesByWarId()
    {
        $entries = $this->mapper->getGamesByWarId(4);
        self::assertCount(2, $entries);

        $entries = $this->mapper->getGamesByWarId(1);
        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());

        self::assertNull($this->mapper->getGamesByWarId(9999));
    }

    /**
     * Tests that getGamesByWhere() filters with a where clause.
     */
    public function testGetGamesByWhereWarId()
    {
        $entries = $this->mapper->getGamesByWhere(['war_id' => 4]);

        self::assertCount(2, $entries);
    }

    /**
     * Tests that delete() accepts a model instance.
     */
    public function testDeleteWarGamesWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);

        self::assertSame(true, $this->mapper->delete($model));

        self::assertNull($this->mapper->getEntriesBy(['id' => 2]));
    }

    /**
     * Tests that deleteById() removes the game.
     */
    public function testDeleteWarGamesById()
    {
        self::assertSame(true, $this->mapper->deleteById(2));

        self::assertNull($this->mapper->getEntriesBy(['id' => 2]));
        self::assertCount(4, $this->mapper->getGamesByWhere());
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteWarGamesNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(5, $this->mapper->getGamesByWhere());
    }

    /**
     * Tests that getJson() returns a JSON encoded array of all games.
     */
    public function testGetJson()
    {
        $json = $this->mapper->getJson();

        self::assertIsString($json);

        $entries = json_decode($json, true);
        self::assertIsArray($entries);
        self::assertCount(5, $entries);
    }

    /**
     * Tests that getJson() returns an empty JSON array when no games exist.
     */
    public function testGetJsonEmpty()
    {
        foreach ([1, 2, 3, 4, 5] as $id) {
            $this->mapper->delete($id);
        }

        self::assertSame('[]', $this->mapper->getJson());
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
