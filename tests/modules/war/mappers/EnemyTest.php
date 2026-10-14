<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\War\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Media\Config\Config as MediaConfig;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Models\Enemy as EntriesModel;

/**
 * @package ilch_phpunit
 */
class EnemyTest extends DatabaseTestCase
{
    protected static $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Enemy $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new EnemyMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarEnemyAllRows()
    {
        $entries = $this->mapper->getEnemy();

        self::assertCount(2, $entries);
    }

    public function testgetWarEnemy()
    {
        $entries = $this->mapper->getEnemy();

        self::assertCount(2, $entries);

        $i = 0;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertSame('Testgegner2', $entries[$i]->getEnemyName());
        self::assertSame('TG2', $entries[$i]->getEnemyTag());
        self::assertSame('', $entries[$i]->getEnemyImage());
        self::assertSame('', $entries[$i]->getEnemyHomepage());
        self::assertSame('', $entries[$i]->getEnemyContactName());
        self::assertSame('', $entries[$i]->getEnemyContactEmail());

        $i++;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertSame('Testgegner1', $entries[$i]->getEnemyName());
        self::assertSame('TG1', $entries[$i]->getEnemyTag());
        self::assertSame('', $entries[$i]->getEnemyImage());
        self::assertSame('', $entries[$i]->getEnemyHomepage());
        self::assertSame('', $entries[$i]->getEnemyContactName());
        self::assertSame('', $entries[$i]->getEnemyContactEmail());
    }

    public function testsaveNewWarEnemy()
    {
        $model = new EntriesModel();
        $model->setEnemyName('Testgegner3');
        $model->setEnemyTag('TG3');
        $model->setEnemyImage('');
        $model->setEnemyHomepage('');
        $model->setEnemyContactName('');
        $model->setEnemyContactEmail('');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEnemyById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('Testgegner3', $entry->getEnemyName());
        self::assertSame('TG3', $entry->getEnemyTag());
        self::assertSame('', $entry->getEnemyImage());
        self::assertSame('', $entry->getEnemyHomepage());
        self::assertSame('', $entry->getEnemyContactName());
        self::assertSame('', $entry->getEnemyContactEmail());
    }

    public function testsaveUpdateExistingWarEnemy()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setEnemyName('Testgegner3');
        $model->setEnemyTag('TG3');
        $model->setEnemyImage('');
        $model->setEnemyHomepage('');
        $model->setEnemyContactName('');
        $model->setEnemyContactEmail('');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEnemyById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Testgegner3', $entry->getEnemyName());
        self::assertSame('TG3', $entry->getEnemyTag());
        self::assertSame('', $entry->getEnemyImage());
        self::assertSame('', $entry->getEnemyHomepage());
        self::assertSame('', $entry->getEnemyContactName());
        self::assertSame('', $entry->getEnemyContactEmail());
    }

    public function testdeleteWarEnemy()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEnemyById(1);
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
     * Tests that getEnemyById() returns null for a non-existent id.
     */
    public function testGetEnemyByIdNotFound()
    {
        self::assertNull($this->mapper->getEnemyById(9999));
    }

    /**
     * Tests that getEnemyById() accepts a model instance.
     */
    public function testGetEnemyByIdWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        $entry = $this->mapper->getEnemyById($model);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
        self::assertSame('Testgegner1', $entry->getEnemyName());
    }

    /**
     * Tests that getEnemy() filters with a where clause.
     */
    public function testGetEnemyByWhere()
    {
        $entries = $this->mapper->getEnemy(['e.name' => 'Testgegner1']);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertSame('TG1', $entries[0]->getEnemyTag());
    }

    /**
     * Tests that getEnemyList() returns all enemies.
     */
    public function testGetEnemyList()
    {
        $entries = $this->mapper->getEnemyList();

        self::assertCount(2, $entries);
    }

    /**
     * Tests that getEnemy() respects pagination.
     */
    public function testGetEnemyPagination()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(1);

        $entries = $this->mapper->getEnemy([], $pagination);

        self::assertCount(1, $entries);
        self::assertEquals(2, $pagination->getRows());
    }

    /**
     * Tests that delete() accepts a model instance.
     */
    public function testDeleteWarEnemyWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        self::assertSame(true, $this->mapper->delete($model));

        self::assertNull($this->mapper->getEnemyById(1));
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteWarEnemyNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(2, $this->mapper->getEnemy());
    }

    /**
     * Tests that getJson() returns a JSON encoded array of all enemies.
     */
    public function testGetJson()
    {
        $json = $this->mapper->getJson();

        self::assertIsString($json);

        $entries = json_decode($json, true);
        self::assertIsArray($entries);
        self::assertCount(2, $entries);
    }

    /**
     * Tests that getJson() returns an empty JSON array when no enemies exist.
     */
    public function testGetJsonEmpty()
    {
        $this->mapper->delete(1);
        $this->mapper->delete(2);

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
        $configMedia = new MediaConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configMedia->getInstallSql() . $config->getInstallSql();
    }
}
