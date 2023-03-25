<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Teams\Mappers;

use Ilch\Pagination;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Teams\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Media\Config\Config as MediaConfig;
use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Models\Joins as EntriesModel;

/**
 * @package ilch_phpunit
 */
class JoinsTest extends DatabaseTestCase
{
    protected $phpunitDataset;
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');

        $this->mapper = new JoinsMapper();
    }

    /**
     * All test routines
     */
    public function testgetJoinsAllRows()
    {
        $entrys = $this->mapper->getEntriesBy();

        self::assertCount(2, $entrys);
    }

    public function testgetJoins()
    {
        $entrys = $this->mapper->getEntriesBy();

        self::assertCount(2, $entrys);

        $i = 0;
        self::assertEquals(1, $entrys[$i]->getId());
        self::assertEquals(0, $entrys[$i]->getUserId());
        self::assertEquals('Testuser', $entrys[$i]->getName());
        self::assertEquals('Testuser@localhost.net', $entrys[$i]->getEmail());
        self::assertEquals(3, $entrys[$i]->getGender());
        self::assertEquals('2000-01-02', $entrys[$i]->getBirthday());
        self::assertEquals('Zuhause', $entrys[$i]->getPlace());
        self::assertEquals(1, $entrys[$i]->getSkill());
        self::assertEquals(2, $entrys[$i]->getTeamId());
        self::assertEquals('de_DE', $entrys[$i]->getLocale());
        self::assertEquals('2023-03-23 00:00:00', $entrys[$i]->getDateCreated());
        self::assertEquals('', $entrys[$i]->getText());
        self::assertEquals(0, $entrys[$i]->getDecision());
        self::assertEquals(1, $entrys[$i]->getUndecided());
        
        $i++;
        self::assertEquals(2, $entrys[$i]->getId());
        self::assertEquals(4, $entrys[$i]->getUserId());
        self::assertEquals('', $entrys[$i]->getName());
        self::assertEquals('', $entrys[$i]->getEmail());
        self::assertEquals(1, $entrys[$i]->getGender());
        self::assertEquals('2000-01-01', $entrys[$i]->getBirthday());
        self::assertEquals('Server', $entrys[$i]->getPlace());
        self::assertEquals(3, $entrys[$i]->getSkill());
        self::assertEquals(3, $entrys[$i]->getTeamId());
        self::assertEquals('de_DE', $entrys[$i]->getLocale());
        self::assertEquals('2023-03-23 00:00:00', $entrys[$i]->getDateCreated());
        self::assertEquals('', $entrys[$i]->getText());
        self::assertEquals(0, $entrys[$i]->getDecision());
        self::assertEquals(1, $entrys[$i]->getUndecided());
    }

    public function testsaveNewJoins()
    {
        $model = new EntriesModel();
        $model->setId(0);
        $model->setUserId(4);
        $model->setName('');
        $model->setEmail('');
        $model->setGender(3);
        $model->setBirthday('2000-01-02');
        $model->setPlace('');
        $model->setSkill(2);
        $model->setTeamId(3);
        $model->setLocale('de_DE');
        $model->setDateCreated('2023-03-23 00:00:00');
        $model->setText('');
        $model->setDecision(0);
        $model->setUndecided(1);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals($model->getUserId(), $entry->getUserId());
        self::assertEquals($model->getName(), $entry->getName());
        self::assertEquals($model->getEmail(), $entry->getEmail());
        self::assertEquals($model->getGender(), $entry->getGender());
        self::assertEquals($model->getBirthday(), $entry->getBirthday());
        self::assertEquals($model->getPlace(), $entry->getPlace());
        self::assertEquals($model->getSkill(), $entry->getSkill());
        self::assertEquals($model->getTeamId(), $entry->getTeamId());
        self::assertEquals($model->getLocale(), $entry->getLocale());
        self::assertEquals($model->getDateCreated(), $entry->getDateCreated());
        self::assertEquals($model->getText(), $entry->getText());
        self::assertEquals($model->getDecision(), $entry->getDecision());
        self::assertEquals($model->getUndecided(), $entry->getUndecided());
    }

    public function testsaveUpdateExistingJoins()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUserId(4);
        $model->setName('');
        $model->setEmail('');
        $model->setGender(3);
        $model->setBirthday('2000-01-02');
        $model->setPlace('');
        $model->setSkill(2);
        $model->setTeamId(3);
        $model->setLocale('de_DE');
        $model->setDateCreated('2023-03-23 00:00:00');
        $model->setText('');
        $model->setDecision(0);
        $model->setUndecided(1);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getEntryById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals(1, $entry->getId());
        self::assertEquals($model->getUserId(), $entry->getUserId());
        self::assertEquals($model->getName(), $entry->getName());
        self::assertEquals($model->getEmail(), $entry->getEmail());
        self::assertEquals($model->getGender(), $entry->getGender());
        self::assertEquals($model->getBirthday(), $entry->getBirthday());
        self::assertEquals($model->getPlace(), $entry->getPlace());
        self::assertEquals($model->getSkill(), $entry->getSkill());
        self::assertEquals($model->getTeamId(), $entry->getTeamId());
        self::assertEquals($model->getLocale(), $entry->getLocale());
        self::assertEquals($model->getDateCreated(), $entry->getDateCreated());
        self::assertEquals($model->getText(), $entry->getText());
        self::assertEquals($model->getDecision(), $entry->getDecision());
        self::assertEquals($model->getUndecided(), $entry->getUndecided());
    }

    public function testdeleteJoins()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntryById(1);
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

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $config->getInstallSql();
    }
}
