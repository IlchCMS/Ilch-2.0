<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Teams\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Ilch\Date;
use Modules\Teams\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Models\Joins as EntriesModel;

/**
 * @package ilch_phpunit
 */
class JoinsTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Joins $mapper;

    /**
     * Loads the fixture data and creates the mapper before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new JoinsMapper();
    }

    /**
     * Tests that the teams_joins table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all join rows.
     */
    public function testgetJoinsAllRows()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(4, $entries);
    }

    /**
     * Tests that getEntriesBy() returns the expected fields in ascending id order.
     */
    public function testgetJoins()
    {
        $entries = $this->mapper->getEntriesBy();

        self::assertCount(4, $entries);

        $i = 0;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertEquals(0, $entries[$i]->getUserId());
        self::assertEquals('Testuser', $entries[$i]->getName());
        self::assertEquals('Testuser@localhost.net', $entries[$i]->getEmail());
        self::assertEquals(3, $entries[$i]->getGender());
        self::assertEquals('2000-01-02', $entries[$i]->getBirthday());
        self::assertEquals('Zuhause', $entries[$i]->getPlace());
        self::assertEquals(1, $entries[$i]->getSkill());
        self::assertEquals(2, $entries[$i]->getTeamId());
        self::assertEquals('de_DE', $entries[$i]->getLocale());
        self::assertEquals('2023-03-23 00:00:00', $entries[$i]->getDateCreated());
        self::assertEquals('', $entries[$i]->getText());
        self::assertEquals(0, $entries[$i]->getDecision());
        self::assertEquals(1, $entries[$i]->getUndecided());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertEquals(4, $entries[$i]->getUserId());
        self::assertEquals('', $entries[$i]->getName());
        self::assertEquals('', $entries[$i]->getEmail());
        self::assertEquals(1, $entries[$i]->getGender());
        self::assertEquals('2000-01-01', $entries[$i]->getBirthday());
        self::assertEquals('Server', $entries[$i]->getPlace());
        self::assertEquals(3, $entries[$i]->getSkill());
        self::assertEquals(3, $entries[$i]->getTeamId());
        self::assertEquals('de_DE', $entries[$i]->getLocale());
        self::assertEquals('2023-03-23 00:00:00', $entries[$i]->getDateCreated());
        self::assertEquals('', $entries[$i]->getText());
        self::assertEquals(0, $entries[$i]->getDecision());
        self::assertEquals(1, $entries[$i]->getUndecided());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getUserId());
        self::assertEquals('HistoryUser', $entries[$i]->getName());
        self::assertEquals('history@example.com', $entries[$i]->getEmail());
        self::assertEquals(1, $entries[$i]->getGender());
        self::assertEquals('1999-05-05', $entries[$i]->getBirthday());
        self::assertEquals('Office', $entries[$i]->getPlace());
        self::assertEquals(2, $entries[$i]->getSkill());
        self::assertEquals(2, $entries[$i]->getTeamId());
        self::assertEquals('en_EN', $entries[$i]->getLocale());
        self::assertEquals('2023-03-24 00:00:00', $entries[$i]->getDateCreated());
        self::assertEquals('Some application text', $entries[$i]->getText());
        self::assertEquals(1, $entries[$i]->getDecision());
        self::assertEquals(0, $entries[$i]->getUndecided());

        $i++;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getUserId());
        self::assertEquals('HistoryUser', $entries[$i]->getName());
        self::assertEquals('history@example.com', $entries[$i]->getEmail());
        self::assertEquals(1, $entries[$i]->getGender());
        self::assertEquals('1999-05-05', $entries[$i]->getBirthday());
        self::assertEquals('Office', $entries[$i]->getPlace());
        self::assertEquals(2, $entries[$i]->getSkill());
        self::assertEquals(1, $entries[$i]->getTeamId());
        self::assertEquals('en_EN', $entries[$i]->getLocale());
        self::assertEquals('2023-03-25 00:00:00', $entries[$i]->getDateCreated());
        self::assertEquals('Second application text', $entries[$i]->getText());
        self::assertEquals(2, $entries[$i]->getDecision());
        self::assertEquals(0, $entries[$i]->getUndecided());
    }

    /**
     * Tests that getEntriesBy() filters by the given where clause.
     */
    public function testGetEntriesByWhere()
    {
        $entries = $this->mapper->getEntriesBy(['teamId' => 2]);

        self::assertCount(2, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(3, $entries[1]->getId());
    }

    /**
     * Tests that getEntriesBy() supports a custom order.
     */
    public function testGetEntriesByOrderDesc()
    {
        $entries = $this->mapper->getEntriesBy([], ['id' => 'DESC']);

        self::assertCount(4, $entries);
        self::assertEquals(4, $entries[0]->getId());
        self::assertEquals(1, $entries[3]->getId());
    }

    /**
     * Tests that getEntryById() returns the matching join.
     */
    public function testGetEntryById()
    {
        $entry = $this->mapper->getEntryById(1);

        self::assertNotNull($entry);
        self::assertInstanceOf(EntriesModel::class, $entry);
        self::assertEquals(1, $entry->getId());
        self::assertEquals('Testuser', $entry->getName());
        self::assertEquals(2, $entry->getTeamId());
    }

    /**
     * Tests that getEntryById() accepts a model instance.
     */
    public function testGetEntryByIdWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);

        $entry = $this->mapper->getEntryById($model);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getId());
        self::assertEquals(4, $entry->getUserId());
    }

    /**
     * Tests that getEntryById() returns null for a non-existent id.
     */
    public function testGetEntryByIdNotFound()
    {
        self::assertNull($this->mapper->getEntryById(9999));
    }

    /**
     * Tests that getApplications() returns all applications/joins.
     */
    public function testGetApplications()
    {
        $entries = $this->mapper->getApplications();

        self::assertCount(4, $entries);
    }

    /**
     * Tests that getJoins() only returns undecided entries (undecided = 1).
     */
    public function testGetJoinsOnlyUndecided()
    {
        $entries = $this->mapper->getJoins();

        self::assertCount(2, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
    }

    /**
     * Tests that getJoins() also applies an additional where clause.
     */
    public function testGetJoinsWithWhere()
    {
        $entries = $this->mapper->getJoins(['teamId' => 2]);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
    }

    /**
     * Tests that getApplicationHistory() only returns decided entries (undecided = 0).
     */
    public function testGetApplicationHistory()
    {
        $entries = $this->mapper->getApplicationHistory();

        self::assertCount(2, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(4, $entries[1]->getId());
        self::assertEquals(1, $entries[0]->getDecision());
        self::assertEquals(2, $entries[1]->getDecision());
    }

    /**
     * Tests that getJoinById() returns the matching join.
     */
    public function testGetJoinById()
    {
        $entry = $this->mapper->getJoinById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getId());
    }

    /**
     * Tests that getJoinInHistoryById() only finds decided entries.
     */
    public function testGetJoinInHistoryById()
    {
        $entry = $this->mapper->getJoinInHistoryById(3);

        self::assertNotNull($entry);
        self::assertEquals(3, $entry->getId());
        self::assertEquals(0, $entry->getUndecided());
    }

    /**
     * Tests that getJoinInHistoryById() returns null for undecided or missing entries.
     */
    public function testGetJoinInHistoryByIdNotFound()
    {
        self::assertNull($this->mapper->getJoinInHistoryById(1));
        self::assertNull($this->mapper->getJoinInHistoryById(9999));
    }

    /**
     * Tests that getApplicationHistoryByUserId() returns the history of the given user.
     */
    public function testGetApplicationHistoryByUserId()
    {
        $entries = $this->mapper->getApplicationHistoryByUserId(2);

        self::assertCount(2, $entries);
        self::assertEquals(3, $entries[0]->getId());
        self::assertEquals(4, $entries[1]->getId());
    }

    /**
     * Tests that getApplicationHistoryByUserId() returns null when the user has no decided entries.
     */
    public function testGetApplicationHistoryByUserIdNotFound()
    {
        self::assertNull($this->mapper->getApplicationHistoryByUserId(4));
    }

    /**
     * Tests that getAge() returns the correct age for relative birthdays.
     */
    public function testGetAge()
    {
        $birthday = date('Y-m-d', strtotime('-18 years'));

        self::assertSame(18, $this->mapper->getAge($birthday));
        self::assertSame(0, $this->mapper->getAge(date('Y-m-d')));
    }

    /**
     * Tests that getAge() also accepts an \Ilch\Date instance, as documented in the mapper.
     * The mapper stringifies the Date object explicitly before calling strtotime().
     */
    public function testGetAgeWithDateObject()
    {
        $birthday = new Date(date('Y-m-d', strtotime('-18 years')));

        self::assertSame(18, $this->mapper->getAge($birthday));
    }

    /**
     * Tests that updateDecision() marks an entry as decided (by int id).
     */
    public function testUpdateDecision()
    {
        self::assertSame(true, $this->mapper->updateDecision(1, 1));

        $entry = $this->mapper->getEntryById(1);

        self::assertNotNull($entry);
        self::assertEquals(1, $entry->getDecision());
        self::assertEquals(0, $entry->getUndecided());

        // The entry must now be part of the history instead of the open joins.
        self::assertCount(1, $this->mapper->getJoins());
        self::assertCount(3, $this->mapper->getApplicationHistory());
    }

    /**
     * Tests that updateDecision() accepts a model instance.
     */
    public function testUpdateDecisionWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);

        self::assertSame(true, $this->mapper->updateDecision($model, 2));

        $entry = $this->mapper->getEntryById(2);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getDecision());
        self::assertEquals(0, $entry->getUndecided());
    }

    /**
     * Tests that clearHistory() removes only the decided entries.
     */
    public function testClearHistory()
    {
        self::assertSame(true, $this->mapper->clearHistory());

        self::assertNull($this->mapper->getApplicationHistory());
        self::assertCount(2, $this->mapper->getJoins());
    }

    /**
     * Tests inserting a new join via save().
     */
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

    /**
     * Tests updating an existing join via save().
     */
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

    /**
     * Tests that delete() removes a join by id.
     */
    public function testdeleteJoins()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getEntryById(1);
        self::assertNull($entry);

        self::assertCount(3, $this->mapper->getEntriesBy());
    }

    /**
     * Tests that delete() accepts a model instance.
     */
    public function testDeleteJoinsWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);

        self::assertSame(true, $this->mapper->delete($model));

        self::assertNull($this->mapper->getEntryById(2));
        self::assertCount(3, $this->mapper->getEntriesBy());
    }

    /**
     * Tests that delete() returns false for a non-existent id and leaves all entries untouched,
     * consistent with Teams::delete().
     */
    public function testDeleteJoinsNotFound()
    {
        self::assertFalse($this->mapper->delete(9999));

        self::assertCount(4, $this->mapper->getEntriesBy());
    }

    /**
     * Tests that delete() returns false when the given model refers to a non-existent join.
     */
    public function testDeleteJoinsWithModelNotFound()
    {
        $model = new EntriesModel();
        $model->setId(9999);

        self::assertFalse($this->mapper->delete($model));

        self::assertCount(4, $this->mapper->getEntriesBy());
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
