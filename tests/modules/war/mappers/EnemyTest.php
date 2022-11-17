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
    protected $phpunitDataset;
    private $mapper;

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
        $entrys = $this->mapper->getEnemy();

        self::assertCount(2, $entrys);
    }

    public function testgetWarEnemy()
    {
        $entrys = $this->mapper->getEnemy();

        self::assertCount(2, $entrys);

        $i = 0;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertSame('Testgegner2', $entrys[$i]->getEnemyName());
        self::assertSame('TG2', $entrys[$i]->getEnemyTag());
        self::assertSame('', $entrys[$i]->getEnemyImage());
        self::assertSame('', $entrys[$i]->getEnemyHomepage());
        self::assertSame('', $entrys[$i]->getEnemyContactName());
        self::assertSame('', $entrys[$i]->getEnemyContactEmail());
        
        $i++;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertSame('Testgegner1', $entrys[$i]->getEnemyName());
        self::assertSame('TG1', $entrys[$i]->getEnemyTag());
        self::assertSame('', $entrys[$i]->getEnemyImage());
        self::assertSame('', $entrys[$i]->getEnemyHomepage());
        self::assertSame('', $entrys[$i]->getEnemyContactName());
        self::assertSame('', $entrys[$i]->getEnemyContactEmail());
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
    protected static function getSchemaSQLQueries()
    {
        $config = new ModuleConfig();
        $configUser = new UserConfig();
        $configAdmin = new AdminConfig();
        $configMedia = new MediaConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configMedia->getInstallSql() . $config->getInstallSql();
    }
}
