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
use Modules\Comment\Config\Config as CommentConfig;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Models\War as EntriesModel;

/**
 * @package ilch_phpunit
 */
class WarTest extends DatabaseTestCase
{
    protected static $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private War $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new WarMapper();
    }

    /**
     * All test routines
     */
    public function testgetWarAllRows()
    {
        $entries = $this->mapper->getWars();

        self::assertCount(4, $entries);
    }

    public function testgetWar()
    {
        $entries = $this->mapper->getWars();

        self::assertCount(4, $entries);

        $i = 0;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertEquals(1, $entries[$i]->getWarEnemy());
        self::assertEquals(2, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1,3', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entries[$i]->getWarGroupTag());
        self::assertSame('TG1', $entries[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getWarEnemy());
        self::assertEquals(1, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1,2', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG1', $entries[$i]->getWarGroupTag());
        self::assertSame('TG2', $entries[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertEquals(2, $entries[$i]->getWarEnemy());
        self::assertEquals(2, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entries[$i]->getWarGroupTag());
        self::assertSame('TG2', $entries[$i]->getWarEnemyTag());

        $i++;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertEquals(1, $entries[$i]->getWarEnemy());
        self::assertEquals(1, $entries[$i]->getWarGroup());
        self::assertSame('2021-05-10 08:10:38', $entries[$i]->getWarTime());
        self::assertSame('', $entries[$i]->getWarMaps());
        self::assertSame('localhost', $entries[$i]->getWarServer());
        self::assertSame('', $entries[$i]->getWarPassword());
        self::assertSame('1on1', $entries[$i]->getWarXonx());
        self::assertSame('CSS', $entries[$i]->getWarGame());
        self::assertSame('Train', $entries[$i]->getWarMatchtype());
        self::assertSame('', $entries[$i]->getWarReport());
        self::assertEquals(0, $entries[$i]->getWarStatus());
        self::assertEquals(1, $entries[$i]->getShow());
        self::assertSame('1,2,3', $entries[$i]->getReadAccess());
        self::assertEquals(0, $entries[$i]->getLastAcceptTime());
        self::assertSame('ILCH2-TG1', $entries[$i]->getWarGroupTag());
        self::assertSame('TG1', $entries[$i]->getWarEnemyTag());
    }

    public function testgetWarByAccess()
    {
        $entries = $this->mapper->getWars(['ra.group_id' => [1,2,3]]);

        self::assertCount(4, $entries);
    }

    public function testgetWarByAccessGuest()
    {
        $entries = $this->mapper->getWars(['ra.group_id' => [3]]);

        self::assertCount(2, $entries);
    }

    public function testgetWarByDate()
    {
        $entries = $this->mapper->getWarsForJson('2021-05-03', '2021-05-09', '1,2,3');
        self::assertCount(3, $entries);
    }

    public function testsaveNewWar()
    {
        $model = new EntriesModel();
        $model->setWarEnemy(1);
        $model->setWarGroup(2);
        $model->setWarTime('2021-05-12 08:10:38');
        $model->setWarMaps('');
        $model->setWarServer('localhost');
        $model->setWarPassword('');
        $model->setWarXonx('1on1');
        $model->setWarGame('CSS');
        $model->setWarMatchtype('Train');
        $model->setWarReport('');
        $model->setWarStatus(0);
        $model->setShow(1);
        $model->setReadAccess('all');
        $model->setLastAcceptTime(0);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getWarById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertEquals(1, $entry->getWarEnemy());
        self::assertEquals(2, $entry->getWarGroup());
        self::assertSame('2021-05-12 08:10:38', $entry->getWarTime());
        self::assertSame('', $entry->getWarMaps());
        self::assertSame('localhost', $entry->getWarServer());
        self::assertSame('', $entry->getWarPassword());
        self::assertSame('1on1', $entry->getWarXonx());
        self::assertSame('CSS', $entry->getWarGame());
        self::assertSame('Train', $entry->getWarMatchtype());
        self::assertSame('', $entry->getWarReport());
        self::assertEquals(0, $entry->getWarStatus());
        self::assertEquals(1, $entry->getShow());
        // Read access is stored in war_access, not in the war table.
        // 'all' results in only the admin group (1) being granted,
        // because saveReadAccess() adds the admin group by default.
        self::assertSame('1', $entry->getReadAccess());
        self::assertEquals(0, $entry->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entry->getWarGroupTag());
        self::assertSame('TG1', $entry->getWarEnemyTag());

        self::assertCount(5, $this->mapper->getWars());
        self::assertSame([1], $this->getAccessGroupIds($id));
    }

    public function testsaveUpdateExistingWar()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setWarEnemy(1);
        $model->setWarGroup(2);
        $model->setWarTime('2021-05-12 08:10:38');
        $model->setWarMaps('');
        $model->setWarServer('localhost');
        $model->setWarPassword('');
        $model->setWarXonx('1on1');
        $model->setWarGame('CSS');
        $model->setWarMatchtype('Train');
        $model->setWarReport('');
        $model->setWarStatus(0);
        $model->setShow(1);
        $model->setReadAccess('all');
        $model->setLastAcceptTime(0);
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getWarById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals($id, $entry->getId());
        self::assertEquals(1, $entry->getWarEnemy());
        self::assertEquals(2, $entry->getWarGroup());
        self::assertSame('2021-05-12 08:10:38', $entry->getWarTime());
        self::assertSame('', $entry->getWarMaps());
        self::assertSame('localhost', $entry->getWarServer());
        self::assertSame('', $entry->getWarPassword());
        self::assertSame('1on1', $entry->getWarXonx());
        self::assertSame('CSS', $entry->getWarGame());
        self::assertSame('Train', $entry->getWarMatchtype());
        self::assertSame('', $entry->getWarReport());
        self::assertEquals(0, $entry->getWarStatus());
        self::assertEquals(1, $entry->getShow());
        // The old access rows (1,2,3) are replaced, only admin (1) remains.
        self::assertSame('1', $entry->getReadAccess());
        self::assertEquals(0, $entry->getLastAcceptTime());
        self::assertSame('ILCH2-TG2', $entry->getWarGroupTag());
        self::assertSame('TG1', $entry->getWarEnemyTag());

        self::assertSame([1], $this->getAccessGroupIds(1));
    }

    public function testdeleteWar()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getWarById(1);
        self::assertNull($entry);
    }

    /**
     * Tests that checkDB() returns true if the tables exist.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getWarById() returns null for a non-existent id.
     */
    public function testGetWarByIdNotFound()
    {
        self::assertNull($this->mapper->getWarById(9999));
    }

    /**
     * Tests that getWarById() accepts a model instance.
     */
    public function testGetWarByIdWithModel()
    {
        $model = new EntriesModel();
        $model->setId(2);

        $entry = $this->mapper->getWarById($model);

        self::assertNotNull($entry);
        self::assertEquals(2, $entry->getId());
    }

    /**
     * Tests that getNextWars() returns all wars ordered by id DESC.
     */
    public function testGetNextWars()
    {
        $entries = $this->mapper->getNextWars();

        self::assertCount(4, $entries);
        self::assertEquals(4, $entries[0]->getId());
        self::assertEquals(1, $entries[3]->getId());
    }

    /**
     * Tests that getWarsByWhere() filters with a where clause.
     */
    public function testGetWarsByWhere()
    {
        $entries = $this->mapper->getWarsByWhere(['w.game' => 'CSS']);

        self::assertCount(4, $entries);
        // Ordered by time DESC, war 1 (2021-05-10) comes first.
        self::assertEquals(1, $entries[0]->getId());
    }

    /**
     * Tests that getWarListByStatus() filters by an explicit status, 0 included.
     * Status 0 is a valid filter value; passing null would skip the filter.
     * Returns null if no war has the given status.
     */
    public function testGetWarListByStatus()
    {
        self::assertCount(4, $this->mapper->getWarListByStatus(0));

        self::assertNull($this->mapper->getWarListByStatus(1));
    }

    /**
     * Tests that getWarList() filters by read access for guests.
     */
    public function testGetWarListGuest()
    {
        $entries = $this->mapper->getWarList(null, '3');

        self::assertCount(2, $entries);
        // Ordered by time DESC: war 1 (2021-05-10) then war 2 (2021-05-09).
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
    }

    /**
     * Tests that getWarList() additionally filters by war group.
     */
    public function testGetWarListWithGroupId()
    {
        $entries = $this->mapper->getWarList(null, '1,2,3', 1);

        self::assertCount(2, $entries);
        // Ordered by time DESC: war 1 (2021-05-10) then war 3 (2021-05-09).
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(3, $entries[1]->getId());
    }

    /**
     * Tests that getWarListByStatusAndLimt() limits the number of results.
     */
    public function testGetWarListByStatusAndLimit()
    {
        $entries = $this->mapper->getWarListByStatusAndLimit(null, 2, '1,2,3', 'ASC');

        self::assertCount(2, $entries);
    }

    /**
     * Tests the distinct xonx options.
     */
    public function testGetWarOptDistinctXonx()
    {
        $entries = $this->mapper->getWarOptDistinctXonx();

        self::assertCount(1, $entries);
        self::assertSame('1on1', $entries[0]->getWarXonx());
    }

    /**
     * Tests the distinct game options.
     */
    public function testGetWarOptDistinctGame()
    {
        $entries = $this->mapper->getWarOptDistinctGame();

        self::assertCount(1, $entries);
        self::assertSame('CSS', $entries[0]->getWarGame());
    }

    /**
     * Tests the distinct matchtype options.
     */
    public function testGetWarOptDistinctMatchtype()
    {
        $entries = $this->mapper->getWarOptDistinctMatchtype();

        self::assertCount(1, $entries);
        self::assertSame('Train', $entries[0]->getWarMatchtype());
    }

    /**
     * Tests that getWarsForJson() returns an empty array if no war matches.
     */
    public function testGetWarsForJsonNoMatch()
    {
        self::assertSame([], $this->mapper->getWarsForJson('2030-01-01', '2030-01-02', '1,2,3'));
    }

    /**
     * Tests that getWarsForJson() returns null without start and end.
     */
    public function testGetWarsForJsonWithoutDates()
    {
        self::assertNull($this->mapper->getWarsForJson(null, null, '1,2,3'));
    }

    /**
     * Tests that getWarsForJson() accepts an array of group ids.
     */
    public function testGetWarsForJsonArrayGroupIds()
    {
        self::assertCount(3, $this->mapper->getWarsForJson('2021-05-03', '2021-05-09', [1, 2, 3]));
    }

    /**
     * Tests that a war with read_access_all = 1 is visible to every group.
     */
    public function testGetWarByAccessReadAccessAll()
    {
        // War 4 is only readable by group 1. Grant "read all" via read_access_all.
        $this->db->update('war')
            ->values(['read_access_all' => 1])
            ->where(['id' => 4])
            ->execute();

        $entries = $this->mapper->getWars(['ra.group_id' => [3]]);

        self::assertCount(3, $entries);
        self::assertContains(4, array_map(static fn ($war): int => $war->getId(), $entries));
    }

    /**
     * Tests that saveReadAccess() replaces the old entries and adds the admin group.
     */
    public function testSaveReadAccessAddsAdminGroup()
    {
        $this->mapper->saveReadAccess(1, '2,3');

        self::assertSame([1, 2, 3], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that saveReadAccess() skips the admin group if disabled.
     */
    public function testSaveReadAccessWithoutAdmin()
    {
        $this->mapper->saveReadAccess(1, '2,3', false);

        self::assertSame([2, 3], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that saveReadAccess() with 'all' only grants the admin group by default.
     */
    public function testSaveReadAccessAllWithAdmin()
    {
        $this->mapper->saveReadAccess(1, 'all');

        self::assertSame([1], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that saveReadAccess() with 'all' and no admin adds no entries.
     */
    public function testSaveReadAccessAllWithoutAdmin()
    {
        $this->mapper->saveReadAccess(1, 'all', false);

        self::assertSame([], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that saveReadAccess() accepts an array of group ids.
     */
    public function testSaveReadAccessArrayInput()
    {
        $this->mapper->saveReadAccess(1, [2, 3]);

        self::assertSame([1, 2, 3], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that countdown() returns "live" for a past date.
     */
    public function testCountdownPast()
    {
        self::assertSame('live', $this->mapper->countdown('2020-01-01 12:00:00'));
    }

    /**
     * Tests that countdown() returns "live" for a date within the next minute.
     * The offset is buffered to avoid flaky second-level truncation.
     */
    public function testCountdownSoonLive()
    {
        $date = (new \DateTime('+30 seconds'))->format('Y-m-d H:i:s');

        self::assertSame('live', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns minutes only.
     */
    public function testCountdownMinutes()
    {
        $date = (new \DateTime('+5 minutes 20 seconds'))->format('Y-m-d H:i:s');

        self::assertSame('5m', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns hours and minutes.
     */
    public function testCountdownHoursAndMinutes()
    {
        $date = (new \DateTime('+1 hour 31 minutes 20 seconds'))->format('Y-m-d H:i:s');

        self::assertSame('1h 31m', $this->mapper->countdown($date));
    }

    /**
     * Tests that countdown() returns days and hours.
     */
    public function testCountdownDaysAndHours()
    {
        $date = (new \DateTime('+1 day 1 hour 20 minutes'))->format('Y-m-d H:i:s');

        self::assertSame('1d 1h', $this->mapper->countdown($date));
    }

    /**
     * Tests that updateStatus() toggles the current status.
     */
    public function testUpdateStatusToggles()
    {
        self::assertSame(true, $this->mapper->updateStatus(1));
        self::assertEquals(1, $this->mapper->getWarById(1)->getWarStatus());

        self::assertSame(true, $this->mapper->updateStatus(1));
        self::assertEquals(0, $this->mapper->getWarById(1)->getWarStatus());
    }

    /**
     * Tests that updateStatus() sets an explicit status.
     */
    public function testUpdateStatusExplicit()
    {
        self::assertSame(true, $this->mapper->updateStatus(2, 1));

        self::assertEquals(1, $this->mapper->getWarById(2)->getWarStatus());
    }

    /**
     * Tests that updateStatus() accepts a model instance.
     */
    public function testUpdateStatusWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setWarStatus(0);

        self::assertSame(true, $this->mapper->updateStatus($model));

        self::assertEquals(1, $this->mapper->getWarById(1)->getWarStatus());
    }

    /**
     * Tests that updateShow() toggles between 1 and 2.
     */
    public function testUpdateShowToggles()
    {
        self::assertSame(true, $this->mapper->updateShow(1));
        self::assertEquals(2, $this->mapper->getWarById(1)->getShow());

        self::assertSame(true, $this->mapper->updateShow(1));
        self::assertEquals(1, $this->mapper->getWarById(1)->getShow());
    }

    /**
     * Tests that updateShow() sets an explicit value.
     */
    public function testUpdateShowExplicit()
    {
        self::assertSame(true, $this->mapper->updateShow(2, 2));

        self::assertEquals(2, $this->mapper->getWarById(2)->getShow());
    }

    /**
     * Tests that updateShow() accepts a model instance.
     */
    public function testUpdateShowWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setShow(1);

        self::assertSame(true, $this->mapper->updateShow($model));

        self::assertEquals(2, $this->mapper->getWarById(1)->getShow());
    }

    /**
     * Tests that updateShow() sets show to 1 if the current value is 0.
     */
    public function testUpdateShowFromZero()
    {
        $this->db->update('war')
            ->values(['show' => 0])
            ->where(['id' => 3])
            ->execute();

        self::assertSame(true, $this->mapper->updateShow(3));

        self::assertEquals(1, $this->mapper->getWarById(3)->getShow());
    }

    /**
     * Tests that delete() accepts a model instance.
     */
    public function testDeleteWarWithModel()
    {
        $model = new EntriesModel();
        $model->setId(1);

        self::assertSame(true, $this->mapper->delete($model));

        self::assertNull($this->mapper->getWarById(1));
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteWarNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(4, $this->mapper->getWars());
    }

    /**
     * Returns the group ids with read access for the given war, sorted.
     *
     * @param int $warId
     * @return int[]
     */
    private function getAccessGroupIds(int $warId): array
    {
        $rows = $this->db->select(['group_id'])
            ->from(['war_access'])
            ->where(['war_id' => $warId])
            ->execute()
            ->fetchList();

        $rows = array_map(static fn ($value): int => (int) $value, $rows);
        sort($rows);

        return $rows;
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
        $configComment = new CommentConfig();

        return $configAdmin->getInstallSql() . $configUser->getInstallSql() . $configComment->getInstallSql() . $config->getInstallSql();
    }
}
