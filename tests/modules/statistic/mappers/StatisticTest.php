<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Statistic\Mappers;

use Ilch\Date;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Statistic\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\Statistic\Models\Statistic as StatisticModel;

class StatisticTest extends DatabaseTestCase
{
    /**
     * @var StatisticMapper
     */
    protected Statistic $out;
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();

        if (!defined('ISHTTPSPAGE')) {
            define('ISHTTPSPAGE', false);
        }

        // Force the legacy user-agent code path (no client hints).
        unset($_SERVER['HTTP_SEC_CH_UA'], $_SERVER['HTTP_SEC_CH_UA_PLATFORM'], $_SERVER['HTTP_SEC_CH_UA_PLATFORM_VERSION']);

        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->out = new StatisticMapper();
    }

    /**
     * Returns a full guest row as expected by saveVisit().
     */
    private function guestVisit(array $overrides = []): array
    {
        return array_merge([
            'user_id' => 0,
            'session_id' => 'session-guest-1',
            'site' => 'example.com',
            'os' => 'Windows',
            'os_version' => '11',
            'browser' => 'Chrome',
            'browser_version' => '120.0',
            'ip' => '192.168.1.100',
            'referer' => 'https://google.com',
            'lang' => 'en',
        ], $overrides);
    }

    /**
     * Inserts a row into visits_online directly (with a configurable date).
     */
    private function insertOnlineRow(array $overrides = []): void
    {
        $this->db->insert('visits_online')
            ->values(array_merge([
                'user_id' => 0,
                'session_id' => 'session-direct',
                'site' => 'example.com',
                'os' => 'Linux',
                'os_version' => '',
                'browser' => 'Firefox',
                'browser_version' => '',
                'ip_address' => '10.0.0.1',
                'lang' => 'en',
                'date_last_activity' => (new Date())->format('Y-m-d H:i:s', true),
            ], $overrides))
            ->execute();
    }

    // ------------------------------------------------------------------
    // getVisitsOnline() / getVisitsCountOnline()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsOnline() returns null when no recent visits exist.
     */
    public function testGetVisitsOnlineEmpty()
    {
        self::assertNull($this->out->getVisitsOnline());
    }

    /**
     * Tests that getVisitsOnline() returns recent visits as models.
     */
    public function testGetVisitsOnline()
    {
        $this->insertOnlineRow();

        $visits = $this->out->getVisitsOnline();

        self::assertNotNull($visits);
        self::assertCount(1, $visits);
        self::assertInstanceOf(StatisticModel::class, $visits[0]);
        self::assertEquals(0, $visits[0]->getUserId());
        self::assertEquals('session-direct', $visits[0]->getSessionId());
        self::assertEquals('example.com', $visits[0]->getSite());
        self::assertEquals('Linux', $visits[0]->getOS());
        self::assertEquals('Firefox', $visits[0]->getBrowser());
        self::assertEquals('10.0.0.1', $visits[0]->getIPAdress());
        self::assertEquals('en', $visits[0]->getLang());
        self::assertNotEmpty($visits[0]->getDateLastActivity());
    }

    /**
     * Tests that getVisitsOnline() orders by date_last_activity DESC.
     */
    public function testGetVisitsOnlineOrderDesc()
    {
        $this->insertOnlineRow([
            'session_id' => 'session-order-a',
            'date_last_activity' => (new Date())->modify('-2 minutes')->format('Y-m-d H:i:s', true),
        ]);
        $this->insertOnlineRow([
            'session_id' => 'session-order-b',
            'date_last_activity' => (new Date())->modify('-1 minutes')->format('Y-m-d H:i:s', true),
        ]);

        $visits = $this->out->getVisitsOnline();

        self::assertCount(2, $visits);
        self::assertEquals('session-order-b', $visits[0]->getSessionId());
        self::assertEquals('session-order-a', $visits[1]->getSessionId());
    }

    /**
     * Tests that getVisitsCountOnline() returns 0 when no recent visits exist.
     */
    public function testGetVisitsCountOnlineEmpty()
    {
        self::assertEquals(0, $this->out->getVisitsCountOnline());
    }

    /**
     * Tests that getVisitsCountOnline() counts recent visits (guest and user rows).
     */
    public function testGetVisitsCountOnline()
    {
        self::assertEquals(0, $this->out->getVisitsCountOnline());

        $this->out->saveVisit($this->guestVisit());

        self::assertEquals(1, $this->out->getVisitsCountOnline());

        // A user row counts as well.
        $this->insertOnlineRow(['session_id' => 'session-count-user', 'user_id' => 5]);

        self::assertEquals(2, $this->out->getVisitsCountOnline());
    }

    // ------------------------------------------------------------------
    // getVisitsHour()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsHour() returns aggregated hour data for a given month.
     */
    public function testGetVisitsHour()
    {
        $result = $this->out->getVisitsHour(2024, 6);

        self::assertNotNull($result);
        self::assertCount(3, $result);
        self::assertInstanceOf(StatisticModel::class, $result[0]);

        // Ordered by date_hour DESC: 14, 10, 9 (one visit each).
        self::assertSame(14, (int)$result[0]->getDate());
        self::assertSame(1, $result[0]->getVisits());

        self::assertSame(10, (int)$result[1]->getDate());
        self::assertSame(1, $result[1]->getVisits());

        self::assertSame(9, (int)$result[2]->getDate());
        self::assertSame(1, $result[2]->getVisits());
    }

    /**
     * Tests that getVisitsHour() with year only aggregates all months of the year.
     */
    public function testGetVisitsHourYearOnly()
    {
        $result = $this->out->getVisitsHour(2024);

        self::assertNotNull($result);
        self::assertCount(3, $result);

        // Hour 9 has two visits (2024-06-20 and 2024-07-01).
        self::assertSame(14, (int)$result[0]->getDate());
        self::assertSame(1, $result[0]->getVisits());

        self::assertSame(10, (int)$result[1]->getDate());
        self::assertSame(1, $result[1]->getVisits());

        self::assertSame(9, (int)$result[2]->getDate());
        self::assertSame(2, $result[2]->getVisits());
    }

    /**
     * Tests that getVisitsHour() returns null when no data for the period.
     */
    public function testGetVisitsHourEmpty()
    {
        self::assertNull($this->out->getVisitsHour(1999, 1));
    }

    // ------------------------------------------------------------------
    // getVisitsDay()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsDay() returns weekday aggregates for a given month.
     */
    public function testGetVisitsDay()
    {
        $result = $this->out->getVisitsDay(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // 2024-06-20 is a Thursday (weekday 3), 2024-06-15 a Saturday (weekday 5).
        // Ordered by date_week ASC: Thursday (1 visit) then Saturday (2 visits).
        self::assertEquals(1, $result[0]->getVisits());
        self::assertEquals(2, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsDay() with year only aggregates all months of the year.
     */
    public function testGetVisitsDayYearOnly()
    {
        $result = $this->out->getVisitsDay(2024);

        self::assertNotNull($result);
        self::assertCount(3, $result);

        // Weekdays ASC: Monday 2024-07-01 (1), Thursday 2024-06-20 (1), Saturday 2024-06-15 (2).
        self::assertEquals(1, $result[0]->getVisits());
        self::assertEquals(1, $result[1]->getVisits());
        self::assertEquals(2, $result[2]->getVisits());
    }

    /**
     * Tests that getVisitsDay() returns null when no data for the period.
     */
    public function testGetVisitsDayEmpty()
    {
        self::assertNull($this->out->getVisitsDay(1999, 12));
    }

    // ------------------------------------------------------------------
    // getVisitsYearMonthDay()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsYearMonthDay() returns daily aggregates for a given month.
     */
    public function testGetVisitsYearMonthDay()
    {
        $result = $this->out->getVisitsYearMonthDay(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by date DESC: 2024-06-20 first, then 2024-06-15.
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
        self::assertNull($this->out->getVisitsYearMonthDay(2000, 1));
    }

    /**
     * Tests that getVisitsYearMonthDay() defaults to the current month (site-local).
     */
    public function testGetVisitsYearMonthDayCurrentMonth()
    {
        // Fixture rows are in 2024, so the current month is empty.
        self::assertNull($this->out->getVisitsYearMonthDay());

        $this->out->saveVisit($this->guestVisit());

        $result = $this->out->getVisitsYearMonthDay();
        self::assertCount(1, $result);
        self::assertSame((new Date())->format('Y-m-d', true), $result[0]->getDate());
        self::assertEquals(1, $result[0]->getVisits());
    }

    // ------------------------------------------------------------------
    // getVisitsYearMonth()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsYearMonth() with year only returns monthly aggregates.
     */
    public function testGetVisitsYearMonth()
    {
        $result = $this->out->getVisitsYearMonth(2024);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by date_month DESC: July (1 visit) first, then June (3 visits).
        self::assertEquals(1, $result[0]->getVisits());
        self::assertEquals(3, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsYearMonth() always returns the per-month series of
     * the year. It is called with 0 args (index: this year) or 1 arg (year
     * view) and never filters to a single month; a second argument is
     * silently ignored, so this pins down the real contract.
     */
    public function testGetVisitsYearMonthIgnoresExtraMonthArgument()
    {
        $result = $this->out->getVisitsYearMonth(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Same series as the 1-arg call: July (1), June (3), month DESC.
        $visits = [];
        foreach ($result as $model) {
            $visits[] = $model->getVisits();
        }
        self::assertSame([1, 3], $visits);
    }

    /**
     * Tests that getVisitsYearMonth() returns null when no data for the year.
     */
    public function testGetVisitsYearMonthEmpty()
    {
        self::assertNull($this->out->getVisitsYearMonth(1999));
    }

    /**
     * Tests that getVisitsYearMonth() defaults to all months of the current year (site-local).
     */
    public function testGetVisitsYearMonthCurrentYear()
    {
        // Fixture rows are in 2024, so the current year is empty (unless run in 2024).
        self::assertNull($this->out->getVisitsYearMonth());

        $this->out->saveVisit($this->guestVisit());

        $result = $this->out->getVisitsYearMonth();
        self::assertCount(1, $result);
        self::assertEquals(1, $result[0]->getVisits());
        self::assertStringStartsWith((new Date())->format('Y', true), $result[0]->getDate());
    }

    // ------------------------------------------------------------------
    // getVisitsYear()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsYear() returns yearly aggregate for a given year.
     */
    public function testGetVisitsYear()
    {
        $result = $this->out->getVisitsYear(2024);

        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertEquals(4, $result[0]->getVisits());
        self::assertSame('2024-01-01', $result[0]->getDate());
    }

    /**
     * Tests that getVisitsYear() returns null when no data for the year.
     */
    public function testGetVisitsYearEmpty()
    {
        self::assertNull($this->out->getVisitsYear(1999));
    }

    /**
     * Tests that getVisitsYear() without argument returns all years, ordered by year DESC.
     */
    public function testGetVisitsYearNoArgs()
    {
        $result = $this->out->getVisitsYear();

        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertEquals(4, $result[0]->getVisits());
        self::assertSame('2024-01-01', $result[0]->getDate());

        $this->out->saveVisit($this->guestVisit());

        $result = $this->out->getVisitsYear();
        self::assertCount(2, $result);
        self::assertSame((new Date())->format('Y', true) . '-01-01', $result[0]->getDate());
        self::assertEquals(1, $result[0]->getVisits());
        self::assertSame('2024-01-01', $result[1]->getDate());
        self::assertEquals(4, $result[1]->getVisits());
    }

    // ------------------------------------------------------------------
    // getVisitsBrowser()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsBrowser() with month and year returns browser aggregates.
     */
    public function testGetVisitsBrowser()
    {
        $result = $this->out->getVisitsBrowser(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: Chrome (2), Firefox (1).
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
     * Tests that getVisitsBrowser() without arguments aggregates all time.
     */
    public function testGetVisitsBrowserNoArgs()
    {
        $result = $this->out->getVisitsBrowser();

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: Chrome (3), Firefox (1).
        self::assertSame('Chrome', $result[0]->getBrowser());
        self::assertEquals(3, $result[0]->getVisits());

        self::assertSame('Firefox', $result[1]->getBrowser());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsBrowser() with year only aggregates all months of the year.
     */
    public function testGetVisitsBrowserYearOnly()
    {
        $result = $this->out->getVisitsBrowser(2024);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        self::assertSame('Chrome', $result[0]->getBrowser());
        self::assertEquals(3, $result[0]->getVisits());

        self::assertSame('Firefox', $result[1]->getBrowser());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsBrowser() returns null for unknown browser/period.
     */
    public function testGetVisitsBrowserEmpty()
    {
        self::assertNull($this->out->getVisitsBrowser(1999, 1, 'Chrome'));
    }

    // ------------------------------------------------------------------
    // getVisitsLanguage()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsLanguage() returns language aggregates for a month.
     */
    public function testGetVisitsLanguage()
    {
        $result = $this->out->getVisitsLanguage(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: en_EN (2), de_DE (1).
        self::assertSame('en_EN', $result[0]->getLang());
        self::assertEquals(2, $result[0]->getVisits());

        self::assertSame('de_DE', $result[1]->getLang());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsLanguage() without arguments aggregates all time.
     */
    public function testGetVisitsLanguageNoArgs()
    {
        $result = $this->out->getVisitsLanguage();

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: en_EN (3), de_DE (1).
        self::assertSame('en_EN', $result[0]->getLang());
        self::assertEquals(3, $result[0]->getVisits());

        self::assertSame('de_DE', $result[1]->getLang());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsLanguage() with year only aggregates all months of the year.
     */
    public function testGetVisitsLanguageYearOnly()
    {
        $result = $this->out->getVisitsLanguage(2024);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        self::assertSame('en_EN', $result[0]->getLang());
        self::assertEquals(3, $result[0]->getVisits());

        self::assertSame('de_DE', $result[1]->getLang());
        self::assertEquals(1, $result[1]->getVisits());
    }

    /**
     * Tests that getVisitsLanguage() returns null when no data.
     */
    public function testGetVisitsLanguageEmpty()
    {
        self::assertNull($this->out->getVisitsLanguage(1999, 1));
    }

    // ------------------------------------------------------------------
    // getVisitsOS()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsOS() returns OS aggregates for a month.
     */
    public function testGetVisitsOS()
    {
        $result = $this->out->getVisitsOS(2024, 6);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        // Ordered by visits DESC: Linux 5.15 (2), Windows 11 (1).
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
     * Tests that getVisitsOS() without arguments aggregates all time.
     */
    public function testGetVisitsOSNoArgs()
    {
        // All time: Linux 5.15 (2) and Windows 11 (2) tie, so compare as unordered pairs.
        $result = $this->out->getVisitsOS();

        self::assertNotNull($result);
        self::assertCount(2, $result);

        $osVisits = [];
        foreach ($result as $model) {
            $osVisits[$model->getOS() . ' ' . $model->getOSVersion()] = $model->getVisits();
        }
        self::assertEquals(['Linux 5.15' => 2, 'Windows 11' => 2], $osVisits);
    }

    /**
     * Tests that getVisitsOS() with year only aggregates all months of the year.
     */
    public function testGetVisitsOSYearOnly()
    {
        $result = $this->out->getVisitsOS(2024);

        self::assertNotNull($result);
        self::assertCount(2, $result);

        $osVisits = [];
        foreach ($result as $model) {
            $osVisits[$model->getOS() . ' ' . $model->getOSVersion()] = $model->getVisits();
        }
        self::assertEquals(['Linux 5.15' => 2, 'Windows 11' => 2], $osVisits);
    }

    /**
     * Tests that getVisitsOS() returns null for unknown OS/period.
     */
    public function testGetVisitsOSEmpty()
    {
        self::assertNull($this->out->getVisitsOS(1999, 1, 'Windows'));
    }

    // ------------------------------------------------------------------
    // getVisitsCount() / getVisitsMonthCount() / getVisitsYearCount()
    // ------------------------------------------------------------------

    /**
     * Tests that getVisitsCount() with explicit date filters by day.
     */
    public function testGetVisitsCountByDate()
    {
        self::assertEquals(2, $this->out->getVisitsCount('2024-06-15'));
    }

    /**
     * Tests that getVisitsCount() with month and year filters by month.
     */
    public function testGetVisitsCountByYearMonth()
    {
        self::assertEquals(3, $this->out->getVisitsCount(null, 2024, 6));
    }

    /**
     * Tests that getVisitsCount() with year only filters by year.
     */
    public function testGetVisitsCountByYear()
    {
        self::assertEquals(4, $this->out->getVisitsCount(null, 2024));
    }

    /**
     * Tests that getVisitsCount() without arguments counts all visits.
     */
    public function testGetVisitsCountNoArgs()
    {
        self::assertEquals(4, $this->out->getVisitsCount());

        $this->out->saveVisit($this->guestVisit());

        self::assertEquals(5, $this->out->getVisitsCount());
    }

    /**
     * Tests that getVisitsCount() returns 0 when no data for the day.
     */
    public function testGetVisitsCountZero()
    {
        self::assertEquals(0, $this->out->getVisitsCount('1999-01-01'));
    }

    /**
     * Tests that getVisitsMonthCount() returns the count for a given month.
     */
    public function testGetVisitsMonthCountWithParams()
    {
        self::assertEquals(3, $this->out->getVisitsMonthCount(2024, 6));
    }

    /**
     * Tests that getVisitsMonthCount() returns 0 when no data for the month.
     */
    public function testGetVisitsMonthCountEmpty()
    {
        self::assertEquals(0, $this->out->getVisitsMonthCount(1999, 1));
    }

    /**
     * Tests that getVisitsMonthCount() defaults to the current month (site-local).
     */
    public function testGetVisitsMonthCountCurrentMonth()
    {
        // Fixture rows are in 2024, so the current month is empty.
        self::assertEquals(0, $this->out->getVisitsMonthCount());

        $this->out->saveVisit($this->guestVisit());

        self::assertEquals(1, $this->out->getVisitsMonthCount());
    }

    /**
     * Tests that getVisitsYearCount() defaults to the current year (site-local).
     */
    public function testGetVisitsYearCount()
    {
        // Fixture rows are in 2024, so the current year is empty.
        self::assertEquals(0, $this->out->getVisitsYearCount());

        $this->out->saveVisit($this->guestVisit());

        self::assertEquals(1, $this->out->getVisitsYearCount());
    }

    // ------------------------------------------------------------------
    // getPercent()
    // ------------------------------------------------------------------

    /**
     * Tests that getPercent() computes and rounds percentages correctly.
     */
    public function testGetPercent()
    {
        self::assertSame(0.0, $this->out->getPercent(0, 100));
        self::assertSame(25.0, $this->out->getPercent(50, 200));
        self::assertSame(33.0, $this->out->getPercent(1, 3));
        self::assertSame(50.0, $this->out->getPercent(1, 2));
        self::assertSame(67.0, $this->out->getPercent(2, 3));
        self::assertSame(100.0, $this->out->getPercent(5, 5));
    }

    // ------------------------------------------------------------------
    // deleteUserOnline() / cleanUpOnline()
    // ------------------------------------------------------------------

    /**
     * Tests that deleteUserOnline() removes the given user's online rows.
     */
    public function testDeleteUserOnline()
    {
        self::assertTrue($this->out->deleteUserOnline(1));

        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 1])
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $count);
    }

    /**
     * Tests that deleteUserOnline() does not affect other users' rows.
     */
    public function testDeleteUserOnlineDoesNotAffectOthers()
    {
        $this->out->deleteUserOnline(1);

        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 0])
            ->execute()
            ->fetchCell();

        self::assertEquals(1, $count);
    }

    /**
     * Tests that cleanUpOnline() removes old guest rows.
     */
    public function testCleanUpOnlineRemovesOldGuests()
    {
        $this->out->cleanUpOnline();

        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 0])
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $count);
    }

    /**
     * Tests that cleanUpOnline() keeps user rows (even old ones).
     */
    public function testCleanUpOnlineKeepsUsers()
    {
        $this->out->cleanUpOnline();

        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->where(['user_id' => 1])
            ->execute()
            ->fetchCell();

        self::assertEquals(1, $count);
    }

    /**
     * Tests that cleanUpOnline(false) removes all old rows, including user rows.
     */
    public function testCleanUpOnlineRemoveAll()
    {
        $this->out->cleanUpOnline(false);

        $count = $this->db->select('COUNT(*)')
            ->from('visits_online')
            ->execute()
            ->fetchCell();

        self::assertEquals(0, $count);
    }

    /**
     * Tests that cleanUpOnline() keeps recent rows, and cleanUpOnline(false)
     * removes old user rows while keeping recent ones.
     */
    public function testCleanUpOnlineKeepsRecentRows()
    {
        // The fixture provides an old guest row (user 0) and an old user row (user 1).
        $this->insertOnlineRow(['session_id' => 'session-recent']);
        $this->insertOnlineRow([
            'session_id' => 'session-old-user',
            'user_id' => 9,
            'date_last_activity' => '2024-01-01 00:00:00',
        ]);

        // Default: old guest rows are removed, recent guest rows and user rows are kept.
        $this->out->cleanUpOnline();

        self::assertEmpty($this->db->select('id')->from('visits_online')->where(['session_id' => 'guest_session_1'])->execute()->fetchCell());
        self::assertEquals(1, $this->db->select('COUNT(*)')->from('visits_online')->where(['session_id' => 'session-recent'])->execute()->fetchCell());
        self::assertEquals(1, $this->db->select('COUNT(*)')->from('visits_online')->where(['user_id' => 1])->execute()->fetchCell());
        self::assertEquals(1, $this->db->select('COUNT(*)')->from('visits_online')->where(['user_id' => 9])->execute()->fetchCell());

        // keepUsers = false: old user rows are removed as well, recent rows are kept.
        $this->out->cleanUpOnline(false);

        self::assertEmpty($this->db->select('id')->from('visits_online')->where(['user_id' => 9])->execute()->fetchCell());
        self::assertEmpty($this->db->select('id')->from('visits_online')->where(['user_id' => 1])->execute()->fetchCell());
        self::assertEquals(1, $this->db->select('COUNT(*)')->from('visits_online')->where(['session_id' => 'session-recent'])->execute()->fetchCell());
    }

    // ------------------------------------------------------------------
    // browserSeenBefore() / osSeenBefore()
    // ------------------------------------------------------------------

    /**
     * Tests that browserSeenBefore() recognizes fixture browsers and
     * newly saved browsers.
     */
    public function testBrowserSeenBefore()
    {
        // Fixture rows already contain Chrome and Firefox.
        self::assertTrue($this->out->browserSeenBefore('Chrome'));
        self::assertTrue($this->out->browserSeenBefore('Firefox'));
        self::assertFalse($this->out->browserSeenBefore('Safari'));

        $this->out->saveVisit($this->guestVisit([
            'browser' => 'Safari',
            'browser_version' => '17.2',
            'session_id' => 'session-safari',
            'ip' => '10.0.0.70',
        ]));

        self::assertTrue($this->out->browserSeenBefore('Safari'));
    }

    /**
     * Tests that osSeenBefore() recognizes fixture OSes and newly saved OSes.
     */
    public function testOsSeenBefore()
    {
        // Fixture rows already contain Windows and Linux.
        self::assertTrue($this->out->osSeenBefore('Windows'));
        self::assertTrue($this->out->osSeenBefore('Linux'));
        self::assertFalse($this->out->osSeenBefore('Android'));

        $this->out->saveVisit($this->guestVisit([
            'os' => 'Android',
            'os_version' => '14.0',
            'session_id' => 'session-android',
            'ip' => '10.0.0.71',
        ]));

        self::assertTrue($this->out->osSeenBefore('Android'));
    }

    // ------------------------------------------------------------------
    // saveVisit()
    // ------------------------------------------------------------------

    /**
     * Tests that saveVisit() inserts a new guest into visits_online and visits_stats.
     */
    public function testSaveVisitNewGuest()
    {
        $this->out->saveVisit($this->guestVisit());

        // visits_online row with the full field set.
        $visits = $this->out->getVisitsOnline();
        self::assertCount(1, $visits);
        self::assertEquals(0, $visits[0]->getUserId());
        self::assertEquals('session-guest-1', $visits[0]->getSessionId());
        self::assertEquals('example.com', $visits[0]->getSite());
        self::assertEquals('Windows', $visits[0]->getOS());
        self::assertEquals('11', $visits[0]->getOSVersion());
        self::assertEquals('Chrome', $visits[0]->getBrowser());
        self::assertEquals('192.168.1.100', $visits[0]->getIPAdress());
        self::assertEquals('en', $visits[0]->getLang());
        self::assertNotEmpty($visits[0]->getDateLastActivity());

        // visits_stats row (including the referer).
        $statsRows = $this->db->select('*')->from('visits_stats')->where(['session_id' => 'session-guest-1'])->execute()->fetchRows();
        self::assertCount(1, $statsRows);
        $statsRow = $statsRows[0];
        self::assertSame('Windows', $statsRow['os']);
        self::assertSame('11', $statsRow['os_version']);
        self::assertSame('Chrome', $statsRow['browser']);
        self::assertSame('192.168.1.100', $statsRow['ip_address']);
        self::assertSame('https://google.com', $statsRow['referer']);

        // 4 fixture rows + 1 new stats row.
        self::assertEquals(5, $this->out->getVisitsCount());
    }

    /**
     * Tests that saving the same session twice does not create duplicate rows.
     */
    public function testSaveVisitNoDuplicate()
    {
        // No clock pinning needed: saveVisit() writes the row and dedupes against the
        // same clock (site-local date via \Ilch\Date), so the same-day premise
        // holds deterministically on any host/CI timezone combination.
        $visit = $this->guestVisit(['session_id' => 'repeat_guest', 'ip' => '10.0.0.50']);

        $this->out->saveVisit($visit);
        $this->out->saveVisit($visit);

        // One online row...
        self::assertCount(1, $this->out->getVisitsOnline());

        // ...and one stats row (updated, not inserted again).
        $count = $this->db->select('COUNT(*)')->from('visits_stats')->where(['session_id' => 'repeat_guest'])->execute()->fetchCell();
        self::assertEquals(1, $count);

        // 4 fixture rows + 1 new stats row.
        self::assertEquals(5, $this->out->getVisitsCount());

        // Writer contract: the stored date is the site-local "today", so this
        // assertion fails if the module ever regresses to writing from the
        // DB server's clock (NOW()/CURDATE()).
        $storedDate = $this->db->select('date')->from('visits_stats')->where(['session_id' => 'repeat_guest'])->execute()->fetchCell();
        self::assertStringStartsWith((new Date())->format('Y-m-d', true), $storedDate);
    }

    /**
     * Tests that a guest session becoming a user updates the online row in place.
     */
    public function testSaveVisitUserAfterGuest()
    {
        // A guest visits...
        $this->out->saveVisit($this->guestVisit(['session_id' => 'session-login']));

        // ...then the same session logs in as a user.
        $this->out->saveVisit($this->guestVisit(['user_id' => 7, 'session_id' => 'session-login']));

        // The guest row is updated to the user in place; no guest row remains.
        $rows = $this->db->select('user_id')->from('visits_online')->where(['session_id' => 'session-login'])->execute()->fetchRows();
        self::assertCount(1, $rows);
        self::assertEquals(7, $rows[0]['user_id']);

        $visits = $this->out->getVisitsOnline();
        self::assertCount(1, $visits);
        self::assertEquals(7, $visits[0]->getUserId());

        // The stats row is updated, not inserted again.
        self::assertEquals(5, $this->out->getVisitsCount());
    }

    /**
     * Tests that a second guest session on the same ip-address updates
     * the first session's stats row instead of inserting a new one.
     */
    public function testSaveVisitSameIpUpdatesStats()
    {
        $this->out->saveVisit($this->guestVisit(['session_id' => 'session-guest-a', 'ip' => '10.0.0.77']));
        $this->out->saveVisit($this->guestVisit(['session_id' => 'session-guest-b', 'ip' => '10.0.0.77']));

        self::assertCount(2, $this->out->getVisitsOnline());

        // 4 fixture rows + 1 shared stats row.
        self::assertEquals(5, $this->out->getVisitsCount());
    }

    // ------------------------------------------------------------------
    // getOS() / getBrowser() (legacy user-agent detection)
    // ------------------------------------------------------------------

    /**
     * Tests that getOS('name') detects the OS name from the user agent.
     */
    public function testGetOSName()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        self::assertSame('Windows', $this->out->getOS('name'));

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Linux; Android 14.0; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.6167.85 Mobile Safari/537.36';
        self::assertSame('Android', $this->out->getOS('name'));

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15';
        self::assertSame('Mac OS X', $this->out->getOS('name'));

        unset($_SERVER['HTTP_USER_AGENT']);
        self::assertSame('', $this->out->getOS('name'));
    }

    /**
     * Tests that getOS(null, 'version') detects the OS version from the user agent.
     */
    public function testGetOSVersion()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        self::assertSame('10', $this->out->getOS(null, 'version'));

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Linux; Android 14.0; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.6167.85 Mobile Safari/537.36';
        self::assertSame('14.0', $this->out->getOS(null, 'version'));

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15';
        self::assertSame('10.15', $this->out->getOS(null, 'version'));

        unset($_SERVER['HTTP_USER_AGENT']);
        self::assertSame('', $this->out->getOS(null, 'version'));
    }

    /**
     * Tests that getBrowser() detects the browser from the user agent.
     */
    public function testGetBrowser()
    {
        // Chrome
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        self::assertSame('120.0', $this->out->getBrowser());
        self::assertSame('Chrome', $this->out->getBrowser('version'));

        // Edge
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.6533.100';
        self::assertSame('120.0', $this->out->getBrowser());
        self::assertSame('Edge', $this->out->getBrowser('version'));

        // Firefox
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (X11; Linux x86_64; rv:121.0) Gecko/20100101 Firefox/121.0';
        self::assertSame('121.0', $this->out->getBrowser());
        self::assertSame('Firefox', $this->out->getBrowser('version'));

        // Safari
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15';
        self::assertSame('17.4', $this->out->getBrowser());
        self::assertSame('Safari', $this->out->getBrowser('version'));

        // Empty user agent.
        unset($_SERVER['HTTP_USER_AGENT']);
        self::assertSame('', $this->out->getBrowser());
        self::assertSame('', $this->out->getBrowser('version'));
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}