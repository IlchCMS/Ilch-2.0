<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Calendar\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Calendar\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\Calendar\Models\Calendar as EntriesModel;
use Ilch\Date;

/**
 * Tests the Calendar mapper class.
 *
 * @package ilch_phpunit
 */
class CalendarTest extends DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;
    private Calendar $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->mapper = new CalendarMapper();
    }

    /**
     * Tests if getEntries() returns all articles from the database.
     */
    public function testgetCalendarAllRows()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(4, $entries);
    }

    public function testgetCalendar()
    {
        $entries = $this->mapper->getEntries();

        self::assertCount(4, $entries);

        $i = 0;
        self::assertEquals(1, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc1', $entries[$i]->getUid());
        self::assertSame('Training 1', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getStart());
        self::assertSame('2021-05-09 08:10:38', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame(0, $entries[$i]->getPeriodDay());
        self::assertSame('1,3', $entries[$i]->getReadAccess());

        $i++;
        self::assertEquals(2, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc2', $entries[$i]->getUid());
        self::assertSame('Training 2', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-09 09:10:38', $entries[$i]->getStart());
        self::assertSame('2021-05-09 09:10:38', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame(0, $entries[$i]->getPeriodDay());
        self::assertSame('1', $entries[$i]->getReadAccess());

        $i++;
        self::assertEquals(3, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc3', $entries[$i]->getUid());
        self::assertSame('Training 3', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-10 09:10:38', $entries[$i]->getStart());
        self::assertSame('2021-05-10 09:10:38', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame(0, $entries[$i]->getPeriodDay());
        self::assertSame('all', $entries[$i]->getReadAccess());

        $i++;
        self::assertEquals(4, $entries[$i]->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc4', $entries[$i]->getUid());
        self::assertSame('Training 4', $entries[$i]->getTitle());
        self::assertSame('', $entries[$i]->getPlace());
        self::assertSame('2021-05-10 18:00:00', $entries[$i]->getStart());
        self::assertSame('2021-05-10 21:00:00', $entries[$i]->getEnd());
        self::assertSame('', $entries[$i]->getText());
        self::assertSame('#32333B', $entries[$i]->getColor());
        self::assertSame('weekly', $entries[$i]->getPeriodType());
        self::assertSame(1, $entries[$i]->getPeriodDay());
        self::assertSame('2021-12-31 21:00:00', $entries[$i]->getRepeatUntil());
        self::assertSame('all', $entries[$i]->getReadAccess());
    }

    public function testgetCalendarByAccess()
    {
        $entries = $this->mapper->getEntries(['ra.group_id' => [1,2,3]]);

        self::assertCount(4, $entries);
    }

    public function testgetCalendarByAccessGuest()
    {
        $entries = $this->mapper->getEntries(['ra.group_id' => [3]]);

        self::assertCount(3, $entries);
    }

    public function testgetCalendarByDate()
    {
        $entries = $this->mapper->getEntriesForJson('2021-05-03', '2021-05-09', '1,2,3');

        self::assertCount(2, $entries);
    }

    public function testsaveNewCalendar()
    {
        $model = new EntriesModel();
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setPlace('');
        $model->setStart('2021-05-09 09:10:38');
        $model->setEnd('2021-05-09 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getCalendarById($id);

        self::assertNotNull($entry);
        self::assertEquals($id, $entry->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc5', $entry->getUid());
        self::assertSame('Training 5', $entry->getTitle());
        self::assertSame('', $entry->getPlace());
        self::assertSame('2021-05-09 09:10:38', $entry->getStart());
        self::assertSame('2021-05-09 09:10:38', $entry->getEnd());
        self::assertSame('', $entry->getText());
        self::assertSame('#32333B', $entry->getColor());
        self::assertSame(0, $entry->getPeriodDay());
        self::assertSame('all', $entry->getReadAccess());
    }

    public function testsaveUpdateExistingCalendar()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setPlace('');
        $model->setStart('2021-05-10 09:10:38');
        $model->setEnd('2021-05-10 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        $entry = $this->mapper->getCalendarById($id);

        self::assertNotNull($entry);
        self::assertEquals(1, $id);
        self::assertEquals($id, $entry->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc5', $entry->getUid());
        self::assertSame('Training 5', $entry->getTitle());
        self::assertSame('', $entry->getPlace());
        self::assertSame('2021-05-10 09:10:38', $entry->getStart());
        self::assertSame('2021-05-10 09:10:38', $entry->getEnd());
        self::assertSame('', $entry->getText());
        self::assertSame('#32333B', $entry->getColor());
        self::assertSame(0, $entry->getPeriodDay());
        self::assertSame('all', $entry->getReadAccess());
    }

    public function testdeleteCalendar()
    {
        self::assertSame(true, $this->mapper->delete(1));

        $entry = $this->mapper->getCalendarById(1);
        self::assertNull($entry);
    }

    /**
     * Tests that checkDB() returns true when the calendar table exists.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->mapper->checkDB());
    }

    /**
     * Tests that getCalendarById() returns null for a non-existent id.
     */
    public function testGetCalendarByIdNotFound()
    {
        self::assertNull($this->mapper->getCalendarById(9999));
    }

    /**
     * Tests that getCalendarById() also accepts a Calendar model.
     */
    public function testGetCalendarByIdAcceptsModel()
    {
        $byId = $this->mapper->getCalendarById(1);

        self::assertNotNull($byId);

        $byModel = $this->mapper->getCalendarById($byId);

        self::assertNotNull($byModel);
        self::assertEquals(1, $byModel->getId());
        self::assertSame('Training 1', $byModel->getTitle());
    }

    /**
     * Tests that getEntries() filters by calendar fields.
     */
    public function testGetEntriesByWhere()
    {
        $entries = $this->mapper->getEntries(['c.title' => 'Training 1']);

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesBy() respects the given order.
     */
    public function testGetEntriesByOrder()
    {
        $asc = $this->mapper->getEntriesBy([], ['c.id' => 'ASC']);
        self::assertEquals(1, $asc[0]->getId());
        self::assertEquals(4, $asc[3]->getId());

        $desc = $this->mapper->getEntriesBy([], ['c.id' => 'DESC']);
        self::assertEquals(4, $desc[0]->getId());
        self::assertEquals(1, $desc[3]->getId());
    }

    /**
     * Tests that getEntries() returns null when no calendars exist.
     */
    public function testGetEntriesEmptyReturnsNull()
    {
        foreach ([1, 2, 3, 4] as $id) {
            $this->mapper->delete($id);
        }

        self::assertNull($this->mapper->getEntries());
    }

    /**
     * Tests that getEntriesBy() returns null when nothing matches the where clause.
     */
    public function testGetEntriesByNoMatchReturnsNull()
    {
        self::assertNull($this->mapper->getEntriesBy(['c.title' => 'Does not exist']));
    }

    /**
     * Tests that getEntriesForJson() returns null without a valid start/end.
     */
    public function testGetEntriesForJsonWithoutDatesReturnsNull()
    {
        self::assertNull($this->mapper->getEntriesForJson('', ''));
        self::assertNull($this->mapper->getEntriesForJson(null, null));
    }

    /**
     * Tests that getEntriesForJson() accepts Date objects.
     */
    public function testGetEntriesForJsonWithDateObjects()
    {
        $entries = $this->mapper->getEntriesForJson(new Date('2021-05-03'), new Date('2021-05-09'), '1,2,3');

        self::assertCount(2, $entries);
    }

    /**
     * Tests that getEntriesForJson() accepts an array of group ids.
     */
    public function testGetEntriesForJsonWithGroupIdsArray()
    {
        $entries = $this->mapper->getEntriesForJson('2021-05-03', '2021-05-09', [1, 2, 3]);

        self::assertCount(2, $entries);
    }

    /**
     * Tests the default group ids (guest group 3) of getEntriesForJson().
     */
    public function testGetEntriesForJsonDefaultGroupIds()
    {
        // Only "Training 1" (groups 1,3) is visible for guest group 3 in this range.
        $entries = $this->mapper->getEntriesForJson('2021-05-03', '2021-05-09');

        self::assertCount(1, $entries);
        self::assertEquals(1, $entries[0]->getId());
    }

    /**
     * Tests that getEntriesForJson() returns null when no calendar is visible for the given group.
     */
    public function testGetEntriesForJsonGroupWithoutAccessReturnsNull()
    {
        // No calendar in this range grants access to group 2 or has read_access_all.
        self::assertNull($this->mapper->getEntriesForJson('2021-05-03', '2021-05-09', '2'));
    }

    /**
     * Tests that getEntriesForJson() also returns events that ended before the range starts.
     */
    public function testGetEntriesForJsonIncludesEarlierEndedEvents()
    {
        // Events 1 and 2 ended on 2021-05-09 and still match via the
        // "c.end <= range end" condition. Events 3 and 4 are inside the range.
        $entries = $this->mapper->getEntriesForJson('2021-05-10', '2021-05-16', '1,2,3');

        self::assertCount(4, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(3, $entries[2]->getId());
        self::assertEquals(4, $entries[3]->getId());
    }

    /**
     * Tests that save() stores the group ids from readAccess in calendar_access.
     */
    public function testSaveStoresReadAccessRows()
    {
        $model = new EntriesModel();
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setStart('2021-05-09 09:10:38');
        $model->setEnd('2021-05-09 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('1,3');
        $id = $this->mapper->save($model);

        self::assertSame([1, 3], $this->getAccessGroupIds($id));
    }

    /**
     * Tests that saving with read access "all" only stores the admin group.
     */
    public function testSaveAllAccessAddsOnlyAdminGroup()
    {
        $model = new EntriesModel();
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setStart('2021-05-09 09:10:38');
        $model->setEnd('2021-05-09 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('all');
        $id = $this->mapper->save($model);

        self::assertSame([1], $this->getAccessGroupIds($id));
        self::assertSame('all', $this->mapper->getCalendarById($id)->getReadAccess());
    }

    /**
     * Tests that saving an existing calendar replaces its access rows
     * and does not touch the access rows of other calendars.
     */
    public function testSaveUpdateReplacesAccessRows()
    {
        $model = new EntriesModel();
        $model->setId(1);
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setStart('2021-05-10 09:10:38');
        $model->setEnd('2021-05-10 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        $model->setReadAccess('2');
        $id = $this->mapper->save($model);

        // Old rows (1,3) removed, admin group (1) re-added.
        self::assertSame([1, 2], $this->getAccessGroupIds($id));
        // Calendar 2 keeps its original access rows.
        self::assertSame([1], $this->getAccessGroupIds(2));
    }

    /**
     * Tests saveReadAccess() with a string read access.
     */
    public function testSaveReadAccessString()
    {
        $result = $this->mapper->saveReadAccess(2, '1,3');

        sort($result);
        self::assertSame(['1', '3'], $result);
        self::assertSame([1, 3], $this->getAccessGroupIds(2));
    }

    /**
     * Tests saveReadAccess() with an array read access adds the admin group.
     */
    public function testSaveReadAccessArrayAddsAdmin()
    {
        $result = $this->mapper->saveReadAccess(2, ['2']);

        sort($result);
        self::assertSame(['1', '2'], $result);
        self::assertSame([1, 2], $this->getAccessGroupIds(2));
    }

    /**
     * Tests saveReadAccess() with "all" only adds the admin group.
     */
    public function testSaveReadAccessAll()
    {
        $result = $this->mapper->saveReadAccess(2, 'all');

        self::assertSame(['1'], $result);
        self::assertSame([1], $this->getAccessGroupIds(2));
    }

    /**
     * Tests saveReadAccess() without the admin group.
     */
    public function testSaveReadAccessWithoutAdmin()
    {
        $result = $this->mapper->saveReadAccess(2, '3', false);

        self::assertSame(['3'], $result);
        self::assertSame([3], $this->getAccessGroupIds(2));
    }

    /**
     * Tests that delete() also removes the calendar's access rows (foreign key cascade).
     */
    public function testDeleteRemovesAccessRows()
    {
        $this->mapper->delete(1);

        self::assertSame([], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that delete() also accepts a Calendar model.
     */
    public function testDeleteAcceptsModel()
    {
        $model = $this->mapper->getCalendarById(2);

        self::assertTrue($this->mapper->delete($model));
        self::assertNull($this->mapper->getCalendarById(2));
    }

    /**
     * Tests that delete() on a non-existent id does not affect existing entries.
     */
    public function testDeleteNotFound()
    {
        $this->mapper->delete(9999);

        self::assertCount(4, $this->mapper->getEntries());
    }

    /**
     * Tests repeat() with a daily period.
     */
    public function testRepeatDaily()
    {
        $recurrences = $this->mapper->repeat(
            'daily',
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-12 08:10:38')
        );

        self::assertCount(3, $recurrences);
        self::assertSame('2021-05-10 08:10:38', $recurrences[0]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-12 08:10:38', $recurrences[2]['start']->format('Y-m-d H:i:s'));
    }

    /**
     * Tests repeat() with a weekly period.
     */
    public function testRepeatWeekly()
    {
        $recurrences = $this->mapper->repeat(
            'weekly',
            new Date('2021-05-10 18:00:00'),
            new Date('2021-05-10 21:00:00'),
            new Date('2021-05-31 23:59:59')
        );

        self::assertCount(3, $recurrences);
        self::assertSame('2021-05-17 18:00:00', $recurrences[0]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-17 21:00:00', $recurrences[0]['end']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-31 18:00:00', $recurrences[2]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-31 21:00:00', $recurrences[2]['end']->format('Y-m-d H:i:s'));
    }

    /**
     * Tests repeat() with a monthly period.
     */
    public function testRepeatMonthly()
    {
        $recurrences = $this->mapper->repeat(
            'monthly',
            new Date('2021-05-09 10:00:00'),
            new Date('2021-05-09 12:00:00'),
            new Date('2021-07-15 00:00:00')
        );

        self::assertCount(2, $recurrences);
        self::assertSame('2021-06-09 10:00:00', $recurrences[0]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-06-09 12:00:00', $recurrences[0]['end']->format('Y-m-d H:i:s'));
        self::assertSame('2021-07-09 10:00:00', $recurrences[1]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-07-09 12:00:00', $recurrences[1]['end']->format('Y-m-d H:i:s'));
    }

    /**
     * Tests repeat() with a quarterly period.
     */
    public function testRepeatQuarterly()
    {
        $recurrences = $this->mapper->repeat(
            'quarterly',
            new Date('2021-05-09 10:00:00'),
            new Date('2021-05-09 12:00:00'),
            new Date('2021-12-31 00:00:00')
        );

        self::assertCount(2, $recurrences);
        self::assertSame('2021-08-09 10:00:00', $recurrences[0]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-11-09 10:00:00', $recurrences[1]['start']->format('Y-m-d H:i:s'));
    }

    /**
     * Tests repeat() with a yearly period.
     */
    public function testRepeatYearly()
    {
        $recurrences = $this->mapper->repeat(
            'yearly',
            new Date('2021-05-09 10:00:00'),
            new Date('2021-05-09 12:00:00'),
            new Date('2023-05-09 12:00:00')
        );

        self::assertCount(2, $recurrences);
        self::assertSame('2022-05-09 10:00:00', $recurrences[0]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2023-05-09 10:00:00', $recurrences[1]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2023-05-09 12:00:00', $recurrences[1]['end']->format('Y-m-d H:i:s'));
    }

    /**
     * Tests repeat() with a "days" period (factor = weekday, 1 = Monday).
     */
    public function testRepeatDays()
    {
        // 2021-05-09 is a Sunday, so the first recurrence (Monday) is 2021-05-10.
        $recurrences = $this->mapper->repeat(
            'days',
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-20 00:00:00'),
            1
        );

        self::assertCount(2, $recurrences);
        self::assertSame('2021-05-10 08:10:38', $recurrences[0]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-10 08:10:38', $recurrences[0]['end']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-17 08:10:38', $recurrences[1]['start']->format('Y-m-d H:i:s'));
        self::assertSame('2021-05-17 08:10:38', $recurrences[1]['end']->format('Y-m-d H:i:s'));
    }

    /**
     * Returns the sorted group ids from calendar_access for the given calendar.
     *
     * @param int $calendarId
     * @return int[]
     */
    private function getAccessGroupIds(int $calendarId): array
    {
        $rows = $this->db
            ->select('group_id')
            ->from(['calendar_access'])
            ->where(['calendar_id' => $calendarId])
            ->execute()
            ->fetchRows();

        $groupIds = array_map('intval', array_column($rows, 'group_id'));
        sort($groupIds);

        return $groupIds;
    }

    /**
     * Tests that saving a model without readAccess stores only the admin group
     * (no group_id 0 row, no FK violation).
     */
    public function testSaveWithEmptyReadAccess()
    {
        $model = new EntriesModel();
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc5');
        $model->setTitle('Training 5');
        $model->setStart('2021-05-09 09:10:38');
        $model->setEnd('2021-05-09 09:10:38');
        $model->setColor('#32333B');
        $model->setPeriodDay(0);
        // readAccess intentionally left at the default ''
        $id = $this->mapper->save($model);

        self::assertSame([1], $this->getAccessGroupIds($id));
    }

    /**
     * Tests that saving an existing calendar with empty readAccess
     * resets its access to the admin group only.
     */
    public function testSaveUpdateWithEmptyReadAccess()
    {
        $model = $this->mapper->getCalendarById(1);
        $model->setReadAccess('');

        $this->mapper->save($model);

        self::assertSame([1], $this->getAccessGroupIds(1));
    }

    /**
     * Tests that repeat() throws for an unknown period type instead of looping forever.
     */
    public function testRepeatUnknownTypeThrows()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->mapper->repeat(
            'fortnightly',
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-12 08:10:38')
        );
    }

    /**
     * Tests that repeat() throws for an out-of-range factor.
     */
    public function testRepeatFactorOutOfRangeThrows()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->mapper->repeat(
            'days',
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-09 08:10:38'),
            new Date('2021-05-20 00:00:00'),
            8
        );
    }

    /**
     * Tests that getEntriesForJson() excludes recurring events whose
     * repeat_until lies before the requested range.
     */
    public function testGetEntriesForJsonExcludesEndedRecurringEvents()
    {
        // Move calendar 4's repeat_until in front of the requested range.
        $model = $this->mapper->getCalendarById(4);
        $model->setRepeatUntil('2021-05-09 21:00:00');
        $this->mapper->save($model);

        $entries = $this->mapper->getEntriesForJson('2021-05-10', '2021-05-16', '1,2,3');

        self::assertCount(3, $entries);
        self::assertEquals(1, $entries[0]->getId());
        self::assertEquals(2, $entries[1]->getId());
        self::assertEquals(3, $entries[2]->getId());
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
