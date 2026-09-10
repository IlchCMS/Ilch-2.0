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
use Modules\Media\Config\Config as MediaConfig;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Models\Enemy as EntriesModel;

/**
 * @package ilch_phpunit
 */
class EnemyTest extends DatabaseTestCase
{
    protected PhpunitDataset $phpunitDataset;
    private Enemy $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

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
