<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Statistic\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Statistic\Config\Config as ModuleConfig;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\Statistic\Models\Statistic as StatisticModel;

class StatisticTest extends DatabaseTestCase
{
    /**
     * @var StatisticMapper
     */
    protected Statistic $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new StatisticMapper();
    }

    /**
     * Tests that getVisitsOnline() returns null when no recent visits exist.
     */
    public function testGetVisitsOnlineEmpty()
    {
        $result = $this->out->getVisitsOnline();

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsOnline() returns StatisticModel[] for recent visits.
     */
    public function testGetVisitsOnlineWithRecentData()
    {
        $now = (new \Ilch\Date())->format('Y-m-d H:i:s', true);
        $this->db->insert('visits_online')
            ->values([
                'user_id'            => 0,
                'session_id'         => 'fresh_session',
                'site'               => 'test.com',
                'os'                 => 'macOS',
                'os_version'         => '14.2',
                'browser'            => 'Safari',
                'browser_version'    => '17.2',
                'ip_address'         => '10.0.0.1',
                'lang'               => 'en_EN',
                'date_last_activity' => $now,
            ])
            ->execute();

        $result = $this->out->getVisitsOnline();

        self::assertNotNull($result);
        self::assertGreaterThanOrEqual(1, count($result));
        self::assertInstanceOf(StatisticModel::class, $result[0]);

        // Find the row we just inserted
        $found = false;
        foreach ($result as $model) {
            if ($model->getSessionId() === 'fresh_session') {
                $found = true;
                self::assertSame('test.com', $model->getSite());
                self::assertSame('macOS', $model->getOS());
                self::assertSame('Safari', $model->getBrowser());
                self::assertSame('10.0.0.1', $model->getIPAdress());
                break;
            }
        }
        self::assertTrue($found, 'Freshly inserted row should be in the results');
    }

    /**
     * Tests that getVisitsCountOnline() returns 0 when no recent visits exist.
     */
    public function testGetVisitsCountOnlineEmpty()
    {
        $count = $this->out->getVisitsCountOnline();

        self::assertEquals(0, $count);
    }

    /**
     * Tests that getVisitsCountOnline() counts recent visits.
     */
    public function testGetVisitsCountOnlineWithRecentData()
    {
        $now = (new \Ilch\Date())->format('Y-m-d H:i:s', true);
        $this->db->insert('visits_online')
            ->values([
                'user_id'            => 0,
                'session_id'         => 'fresh_count_1',
                'site'               => 'test.com',
                'os'                 => 'Windows',
                'os_version'         => '11',
                'browser'            => 'Chrome',
                'browser_version'    => '125.0',
                'ip_address'         => '10.0.0.2',
                'lang'               => 'en_EN',
                'date_last_activity' => $now,
            ])
            ->execute();

        $this->db->insert('visits_online')
            ->values([
                'user_id'            => 5,
                'session_id'         => 'fresh_count_2',
                'site'               => 'test.com',
                'os'                 => 'Linux',
                'os_version'         => '5.15',
                'browser'            => 'Firefox',
                'browser_version'    => '121.0',
                'ip_address'         => '10.0.0.3',
                'lang'               => 'de_DE',
                'date_last_activity' => $now,
            ])
            ->execute();

        $count = $this->out->getVisitsCountOnline();

        self::assertEquals(2, $count);
    }

    /**
     * Tests that getVisitsHour() returns aggregated hour data for a given month.
     */
    public function testGetVisitsHour()
    {
        $result = $this->out->getVisitsHour(2024, 6);

        self::assertNotNull($result);
        self::assertCount(3, $result);
        self::assertInstanceOf(StatisticModel::class, $result[0]);

        // Ordered by date_hour DESC: hour 14, 10, 9
        self::assertSame(14, (int)$result[0]->getDate());
        self::assertEquals(1, $result[0]->getVisits());

        self::assertSame(10, (int)$result[1]->getDate());
        self::assertEquals(1, $result[1]->getVisits());

        self::assertSame(9, (int)$result[2]->getDate());
        self::assertEquals(1, $result[2]->getVisits());
    }

    /**
     * Tests that getVisitsHour() returns null when no data for the period.
     */
    public function testGetVisitsHourEmpty()
    {
        $result = $this->out->getVisitsHour(1999, 1);

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsHour() with year only aggregates all months.
     */
    public function testGetVisitsHourYearOnly()
    {
        $result = $this->out->getVisitsHour(2024);

        self::assertNotNull($result);
        self::assertGreaterThanOrEqual(3, count($result));
    }

    /**
     * Tests that getVisitsDay() returns weekday aggregates for a given month.
     */
    public function testGetVisitsDay()
    {
        $result = $this->out->getVisitsDay(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by date_week ASC: Thursday (3) then Saturday (5)
        self::assertEquals(1, $result[0]->getVisits());
        self::assertEquals(2, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsDay() returns null when no data for the period.
     */
    public function testGetVisitsDayEmpty()
    {
        $result = $this->out->getVisitsDay(1999, 12);

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsYearMonthDay() returns daily aggregates for a given month.
     */
    public function testGetVisitsYearMonthDay()
    {
        $result = $this->out->getVisitsYearMonthDay(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by date_full DESC: 2024-06-20 first, then 2024-06-15
        self::assertSame('2024-06-20', $result[0]->getDate());
        self::assertEquals(1, $result[0]->getVisits());

        self::assertSame('2024-06-15', $result[1]->getDate());
        self::assertEquals(2, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsYearMonthDay() returns null when no data.
     */
    public function testGetVisitsYearMonthDayEmpty()
    {
        $result = $this->out->getVisitsYearMonthDay(2000, 1);

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsYearMonth() returns monthly aggregates for a given year.
     */
    public function testGetVisitsYearMonth()
    {
        $result = $this->out->getVisitsYearMonth(2024);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by date_month DESC: July (7) first, then June (6)
        self::assertEquals(1, $result[0]->getVisits());
        self::assertEquals(3, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsYearMonth() returns null when no data for the year.
     */
    public function testGetVisitsYearMonthEmpty()
    {
        $result = $this->out->getVisitsYearMonth(1999);

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsYear() returns yearly aggregate for a given year.
     */
    public function testGetVisitsYear()
    {
        $result = $this->out->getVisitsYear(2024);

        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertEquals(4, $result[0]->getVisits());
    }

    /**
     * Tests that getVisitsYear() returns null when no data for the year.
     */
    public function testGetVisitsYearEmpty()
    {
        $result = $this->out->getVisitsYear(1999);

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsBrowser() returns browser aggregates for a month.
     */
    public function testGetVisitsBrowser()
    {
        $result = $this->out->getVisitsBrowser(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: Chrome (2), Firefox (1)
        self::assertSame('Chrome', $result[0]->getBrowser());
        self::assertEquals(2, $result[0]->getVisits());

        self::assertSame('Firefox', $result[1]->getBrowser());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsBrowser() filters by a specific browser.
     */
    public function testGetVisitsBrowserSpecific()
    {
        $result = $this->out->getVisitsBrowser(2024, 6, 'Chrome');

        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertSame('Chrome', $result[0]->getBrowser());
        self::assertEquals(2, $result[0]->getVisits());
    }

    /**
     * Tests that getVisitsBrowser() returns null for unknown browser/period.
     */
    public function testGetVisitsBrowserEmpty()
    {
        $result = $this->out->getVisitsBrowser(1999, 1, 'Chrome');

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsLanguage() returns language aggregates for a month.
     */
    public function testGetVisitsLanguage()
    {
        $result = $this->out->getVisitsLanguage(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: en_EN (2), de_DE (1)
        self::assertSame('en_EN', $result[0]->getLang());
        self::assertEquals(2, $result[0]->getVisits());

        self::assertSame('de_DE', $result[1]->getLang());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsLanguage() returns null when no data.
     */
    public function testGetVisitsLanguageEmpty()
    {
        $result = $this->out->getVisitsLanguage(1999, 1);

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsOS() returns OS aggregates for a month.
     */
    public function testGetVisitsOS()
    {
        $result = $this->out->getVisitsOS(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: Linux 5.15 (2), Windows 11 (1)
        self::assertSame('Linux', $result[0]->getOS());
        self::assertSame('5.15', $result[0]->getOSVersion());
        self::assertEquals(2, $result[0]->getVisits());

        self::assertSame('Windows', $result[1]->getOS());
        self::assertSame('11', $result[1]->getOSVersion());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsOS() filters by a specific OS.
     */
    public function testGetVisitsOSSpecific()
    {
        $result = $this->out->getVisitsOS(2024, 6, 'Linux');

        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertSame('Linux', $result[0]->getOS());
        self::assertSame('5.15', $result[0]->getOSVersion());
        self::assertEquals(2, $result[0]->getVisits());
    }

    /**
     * Tests that getVisitsOS() returns null for unknown OS/period.
     */
    public function testGetVisitOSEmpty()
    {
        $result = $this->out->getVisitsOS(1999, 1, 'Windows');

        self::assertNull($result);
    }

    /**
     * Tests that getVisitsCount() with explicit date filters by day.
     */
    public function testGetVisitsCountByDate()
    {
        $count = $this->out->getVisitsCount('2024-06-15');

        self::assertEquals(2, $count);
    }

    /**
     * Tests that getVisitsCount() with year and month filters by month.
     */
    public function testGetVisitsCountByYearMonth()
    {
        $count = $this->out->getVisitsCount(null, 2024, 6);

        self::assertEquals(3, $count);
    }

    /**
     * Tests that getVisitsCount() with year only filters by year.
     */
    public function testGetVisitsCountByYear()
    {
        $count = $this->out->getVisitsCount(null, 2024);

        self::assertEquals(4, $count);
    }

    /**
     * Tests that getVisitsCount() returns 0 for a date with no visits.
     */
    public function testGetVisitsCountZero()
    {
        $count = $this->out->getVisitsCount('1999-01-01');

        self::assertEquals(0, $count);
    }

    /**
     * Tests that getVisitsMonthCount() with year and month returns correct count.
     */
    public function testGetVisitsMonthCountWithParams()
    {
        $count = $this->out->getVisitsMonthCount(2024, 6);

        self::assertEquals(3, $count);
    }

    /**
     * Tests that getVisitsMonthCount() returns 0 for a month with no data.
     */
    public function testGetVisitsMonthCountEmpty()
    {
        $count = $this->out->getVisitsMonthCount(1999, 1);

        self::assertEquals(0, $count);
    }

    /**
     * Tests that getPercent() calculates percentage correctly.
     */
    public function testGetPercent()
    {
        self::assertEquals(25.0, $this->out->getPercent(50, 200));
    }

    /**
     * Tests that getPercent() rounds correctly.
     */
    public function testGetPercentRounds()
    {
        self::assertEquals(33.0, $this->out->getPercent(1, 3));
    }

    /**
     * Tests that getPercent() returns 0 for zero count.
     */
    public function testGetPercentZero()
    {
        self::assertEquals(0.0, $this->out->getPercent(0, 100));
    }

    /**
     * Tests that getPercent() returns 100 for equal values.
     */
    public function testGetPercentFull()
    {
        self::assertEquals(100.0, $this->out->getPercent(100, 100));
    }

    /**
     * Tests that deleteUserOnline() removes the user from visits_online.
     */
    public function testDeleteUserOnline()
    {
        $result = $this->out->deleteUserOnline(1);

        self::assertTrue($result);

        // Verify the row is gone
        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 1])
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $count);
    }

    /**
     * Tests that deleteUserOnline() does not affect other users.
     */
    public function testDeleteUserOnlineDoesNotAffectOthers()
    {
        $this->out->deleteUserOnline(1);

        // Guest row (user_id=0) should still exist
        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 0])
            ->execute()
            ->fetchCell();

        self::assertEquals(1, $count);
    }

    /**
     * Tests that cleanUpOnline() removes old guest entries by default.
     */
    public function testCleanUpOnlineRemovesOldGuests()
    {
        $this->out->cleanUpOnline();

        // Guest row (user_id=0, date 2020) should be deleted
        $guestCount = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 0])
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $guestCount);
    }

    /**
     * Tests that cleanUpOnline() keeps user entries by default.
     */
    public function testCleanUpOnlineKeepsUsers()
    {
        $this->out->cleanUpOnline();

        // User row (user_id=1, date 2020) should remain
        $userCount = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 1])
            ->execute()
            ->fetchCell();

        self::assertEquals(1, $userCount);
    }

    /**
     * Tests that cleanUpOnline(false) removes both guest and user entries.
     */
    public function testCleanUpOnlineRemoveAll()
    {
        $this->out->cleanUpOnline(false);

        $total = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $total);
    }

    /**
     * Tests that browserSeenBefore() returns true for a known browser.
     */
    public function testBrowserSeenBeforeKnown()
    {
        self::assertTrue($this->out->browserSeenBefore('Chrome'));
    }

    /**
     * Tests that browserSeenBefore() returns false for an unknown browser.
     */
    public function testBrowserSeenBeforeUnknown()
    {
        self::assertFalse($this->out->browserSeenBefore('Netscape Navigator 4'));
    }

    /**
     * Tests that osSeenBefore() returns true for a known OS.
     */
    public function testOsSeenBeforeKnown()
    {
        self::assertTrue($this->out->osSeenBefore('Linux'));
    }

    /**
     * Tests that osSeenBefore() returns false for an unknown OS.
     */
    public function testOsSeenBeforeUnknown()
    {
        self::assertFalse($this->out->osSeenBefore('AmigaOS'));
    }

    /**
     * Tests that saveVisit() inserts a new guest visit into both tables.
     */
    public function testSaveVisitNewGuest()
    {
        $row = [
            'user_id'       => 0,
            'session_id'    => 'brand_new_guest',
            'site'          => 'test.com',
            'os'            => 'macOS',
            'os_version'    => '14.2',
            'browser'       => 'Safari',
            'browser_version' => '17.2',
            'ip'            => '10.0.0.99',
            'lang'          => 'en_EN',
            'referer'       => 'https://test.com',
        ];

        $this->out->saveVisit($row);

        // Verify visits_online has the new row
        $onlineRows = $this->db->select('*')
            ->from('visits_online')
            ->where(['session_id' => 'brand_new_guest'])
            ->execute()
            ->fetchRows();

        self::assertNotEmpty($onlineRows);
        $onlineRow = $onlineRows[0];
        self::assertSame('macOS', $onlineRow['os']);
        self::assertSame('Safari', $onlineRow['browser']);
        self::assertSame('10.0.0.99', $onlineRow['ip_address']);
        self::assertSame('test.com', $onlineRow['site']);

        // Verify visits_stats has the new row
        $statsRows = $this->db->select('*')
            ->from('visits_stats')
            ->where(['session_id' => 'brand_new_guest'])
            ->execute()
            ->fetchRows();

        self::assertNotEmpty($statsRows);
        $statsRow = $statsRows[0];
        self::assertSame('macOS', $statsRow['os']);
        self::assertSame('Safari', $statsRow['browser']);
        self::assertSame('https://test.com', $statsRow['referer']);
    }

    /**
     * Tests that saveVisit() does not duplicate when called with the same session.
     */
    public function testSaveVisitNoDuplicate()
    {
        $row = [
            'user_id'       => 0,
            'session_id'    => 'repeat_guest',
            'site'          => 'test.com',
            'os'            => 'Windows',
            'os_version'    => '11',
            'browser'       => 'Chrome',
            'browser_version' => '125.0',
            'ip'            => '10.0.0.50',
            'lang'          => 'en_EN',
            'referer'       => 'https://repeat.com',
        ];

        $this->out->saveVisit($row);
        $this->out->saveVisit($row);

        // Should only have one row in visits_stats for this session today
        $count = $this->db->select('COUNT(*)')
            ->from('visits_stats')
            ->where(['session_id' => 'repeat_guest'])
            ->execute()
            ->fetchCell();

        self::assertEquals(1, $count);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
