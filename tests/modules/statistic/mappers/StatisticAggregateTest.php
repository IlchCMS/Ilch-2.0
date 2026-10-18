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
use Modules\Statistic\Mappers\StatisticAggregate as StatisticAggregateMapper;

class StatisticAggregateTest extends DatabaseTestCase
{
    /**
     * @var StatisticAggregateMapper
     */
    protected StatisticAggregate $out;

    // Own fixture so the existing StatisticTest fixture (2024 rows) stays untouched.
    // All fixture days are in 2022/2023 (strictly in the past), which keeps every
    // run() assertion deterministic independent of the test run date.
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database_aggregate.yml'];

    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();

        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);

        $this->out = new StatisticAggregateMapper();
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Inserts a raw visit row (defaults to "today", overridable).
     */
    private function insertRawRow(array $overrides = []): void
    {
        $this->db->insert('visits_stats')
            ->values(array_merge([
                'user_id' => 0,
                'session_id' => 'sess_dynamic',
                'os' => 'Windows',
                'os_version' => '11',
                'browser' => 'Edge',
                'browser_version' => '126.0',
                'ip_address' => '10.0.0.5',
                'referer' => 'https://example.com',
                'lang' => 'en_EN',
                'date' => (new Date())->format('Y-m-d H:i:s', true),
            ], $overrides))
            ->execute();
    }

    /**
     * Mirrors the MD5(CONCAT(..., CHAR(31), ...)) expression used by the mapper.
     */
    private function dimHash(string $browser, string $browserVersion, string $os, string $osVersion, string $lang): string
    {
        return md5(implode(chr(31), [$browser, $browserVersion, $os, $osVersion, $lang]));
    }

    /**
     * @param array[] $rows
     * @return array[]
     */
    private function rowsByDimHash(array $rows): array
    {
        $byHash = [];
        foreach ($rows as $row) {
            $byHash[$row['dim_hash']] = $row;
        }

        return $byHash;
    }

    private function dailyRowsFor(string $day): array
    {
        return $this->db->select('*')->from('visits_stats_daily')->where(['date' => $day])->execute()->fetchRows();
    }

    private function monthlyRowsFor(string $monthStart): array
    {
        return $this->db->select('*')->from('visits_stats_monthly')->where(['month' => $monthStart])->execute()->fetchRows();
    }

    private function yearlyRowsFor(int $year): array
    {
        return $this->db->select('*')->from('visits_stats_yearly')->where(['year' => $year])->execute()->fetchRows();
    }

    private function rawCountFor(string $day): int
    {
        return (int)$this->db->select('COUNT(*)')
            ->from('visits_stats')
            ->where(['date >=' => $day . ' 00:00:00', 'date <' => $this->out->addDays($day, 1) . ' 00:00:00'])
            ->execute()
            ->fetchCell();
    }

    private function countIn(string $table): int
    {
        return (int)$this->db->select('COUNT(*)')->from($table)->execute()->fetchCell();
    }

    private function progressRow(string $level): ?array
    {
        $rows = $this->db->select('*')->from('visits_stats_progress')->where(['level' => $level])->execute()->fetchRows();

        return $rows[0] ?? null;
    }

    // ------------------------------------------------------------------
    // run()
    // ------------------------------------------------------------------

    /**
     * Tests the full pipeline with the default payload:
     * daily backlog + window, monthly backlog + window, yearly backlog + window,
     * no purge (raw_retention_days defaults to 0).
     */
    public function testRunFullPipelineWithDefaults()
    {
        $summary = $this->out->run([]);

        // Daily rows created: 2022-01-15 (2 groups), 2022-12-20 (1), 2023-06-15 (2).
        // Monthly rows: 2022-01 (2), 2022-12 (1), 2023-06 (2).
        // Yearly: only 2022 is closed by the monthly frontier (2 rows).
        self::assertSame(['daily' => 5, 'monthly' => 5, 'yearly' => 2, 'raw_purged' => 0], $summary);

        // Daily aggregates per day, grouped by dimension quintuple.
        $rows = array_merge(
            $this->dailyRowsFor('2022-01-15'),
            $this->dailyRowsFor('2022-12-20'),
            $this->dailyRowsFor('2023-06-15')
        );
        self::assertCount(5, $rows);
        $byHash = $this->rowsByDimHash($rows);

        $g1 = $byHash[$this->dimHash('Chrome', '125.0', 'Windows', '11', 'en_EN')];
        self::assertSame('Chrome', $g1['browser']);
        self::assertSame('125.0', $g1['browser_version']);
        self::assertSame('Windows', $g1['os']);
        self::assertSame('11', $g1['os_version']);
        self::assertSame('en_EN', $g1['lang']);
        self::assertEquals(1, $g1['visits']);

        $g2 = $byHash[$this->dimHash('Chrome', '124.0', 'Linux', '5.15', 'de_DE')];
        self::assertEquals(1, $g2['visits']);

        $g3 = $byHash[$this->dimHash('Firefox', '121.0', 'Linux', '5.15', 'en_EN')];
        self::assertEquals(1, $g3['visits']);

        // Monthly aggregates.
        self::assertCount(2, $this->monthlyRowsFor('2022-01-01'));
        self::assertCount(1, $this->monthlyRowsFor('2022-12-01'));
        self::assertCount(2, $this->monthlyRowsFor('2023-06-01'));
        self::assertSame(5, $this->countIn('visits_stats_monthly'));

        // Yearly aggregates: G1 spans Jan + Dec 2022 (2 visits), G2 (1 visit).
        $yearly = $this->yearlyRowsFor(2022);
        self::assertCount(2, $yearly);
        $yearlyByHash = $this->rowsByDimHash($yearly);
        self::assertEquals(2, $yearlyByHash[$this->dimHash('Chrome', '125.0', 'Windows', '11', 'en_EN')]['visits']);
        self::assertEquals(1, $yearlyByHash[$this->dimHash('Chrome', '124.0', 'Linux', '5.15', 'de_DE')]['visits']);

        // 2023 is not rolled up yet: its last data month (2023-06) has no
        // following data month, so the monthly frontier stops at 2023-07-01
        // and never reaches 2024-01-01.
        self::assertCount(0, $this->yearlyRowsFor(2023));

        // Frontiers after the run.
        $today = $this->out->siteToday();
        self::assertSame($this->out->addDays($today, -6), $this->out->getFrontier('daily'));
        self::assertSame('2023-07-01', $this->out->getFrontier('monthly'));
        self::assertSame('2023-01-01', $this->out->getFrontier('yearly'));
        self::assertSame('1000-01-01', $this->out->getFrontier('raw'));
    }

    /**
     * Tests that a second run is a no-op: all rebuilds are idempotent
     * (delete + insert in one transaction) and no data is duplicated.
     */
    public function testRunIsIdempotent()
    {
        $this->out->run([]);

        $summary = $this->out->run([]);
        self::assertSame(['daily' => 0, 'monthly' => 0, 'yearly' => 0, 'raw_purged' => 0], $summary);

        self::assertSame(5, $this->countIn('visits_stats_daily'));
        self::assertSame(5, $this->countIn('visits_stats_monthly'));
        self::assertSame(2, $this->countIn('visits_stats_yearly'));
        self::assertSame(5, $this->countIn('visits_stats'));
    }

    /**
     * Tests that the payload registered by Config::createScheduledTasks()
     * behaves like the PHP defaults (raw_retention_days frozen at 0: no purge).
     */
    public function testRunWithSchedulerPayloadFromConfig()
    {
        $summary = $this->out->run([
            'daily_limit' => 14,
            'monthly_limit' => 6,
            'yearly_limit' => 12,
            'recent_days' => 7,
            'raw_retention_days' => 0,
            'purge_limit' => 30,
        ]);

        self::assertSame(['daily' => 5, 'monthly' => 5, 'yearly' => 2, 'raw_purged' => 0], $summary);
        self::assertSame(5, $this->countIn('visits_stats'));
    }

    /**
     * Tests that run() purges fully aggregated raw days beyond the retention
     * floor while keeping every aggregate row.
     */
    public function testRunPurgesRawDataBeyondRetention()
    {
        $summary = $this->out->run(['raw_retention_days' => 400]);

        self::assertSame(['daily' => 5, 'monthly' => 5, 'yearly' => 2, 'raw_purged' => 3], $summary);

        // All three fixture days (2 + 1 + 2 rows) are older than the floor and
        // fully aggregated, so all 5 raw rows are purged.
        self::assertSame(0, $this->countIn('visits_stats'));

        // Aggregate data survives the purge.
        self::assertSame(5, $this->countIn('visits_stats_daily'));
        self::assertSame(5, $this->countIn('visits_stats_monthly'));
        self::assertSame(2, $this->countIn('visits_stats_yearly'));

        // The raw frontier is pinned to the retention floor once caught up.
        $floor = $this->out->addDays($this->out->siteToday(), -400);
        self::assertSame($floor, $this->out->getFrontier('raw'));
    }

    /**
     * Tests that raw rows inside the recent window are rebuilt (late/corrected
     * rows) but never purged, even with a large retention window.
     */
    public function testRunNeverPurgesRecentWindow()
    {
        $yesterday = (new Date())->modify('-1 day')->format('Y-m-d', true);
        $this->insertRawRow(['date' => $yesterday . ' 09:30:00', 'session_id' => 'sess_yesterday']);

        $summary = $this->out->run(['raw_retention_days' => 400]);

        // Daily: 5 fixture rows + 1 window row. Monthly: 5 backlog rows + 1 window row.
        // Yearly: 2 rows for 2022 + 1 row for the current year (yesterday's month).
        // Purged: the three fixture days (2 + 1 + 2 = 5 rows, 3 days).
        self::assertSame(['daily' => 6, 'monthly' => 6, 'yearly' => 3, 'raw_purged' => 3], $summary);

        // The recent-window raw row survives; all old raw rows are gone.
        self::assertSame(1, $this->rawCountFor($yesterday));
        self::assertSame(0, $this->rawCountFor('2022-01-15'));
        self::assertSame(0, $this->rawCountFor('2022-12-20'));
        self::assertSame(0, $this->rawCountFor('2023-06-15'));
        self::assertSame(1, $this->countIn('visits_stats'));

        // The window rebuild produced a daily row for yesterday.
        $yesterdayRows = $this->dailyRowsFor($yesterday);
        self::assertCount(1, $yesterdayRows);
        self::assertSame('Edge', $yesterdayRows[0]['browser']);
        self::assertEquals(1, $yesterdayRows[0]['visits']);
    }

    /**
     * Tests that a bounded daily_limit makes one day of progress per run and
     * that consecutive runs resume where the frontier left off, converging to
     * the same final state as one unbounded run.
     */
    public function testRunRespectsDailyLimitAndResumes()
    {
        // Run 1: only 2022-01-15 is rebuilt (2 rows).
        $summary1 = $this->out->run(['daily_limit' => 1]);
        self::assertSame(['daily' => 2, 'monthly' => 2, 'yearly' => 0, 'raw_purged' => 0], $summary1);
        self::assertSame('2022-12-20', $this->out->getFrontier('daily'));

        // Run 2: 2022-12-20 (1 row); 2022 is now closed by the monthly frontier.
        $summary2 = $this->out->run(['daily_limit' => 1]);
        self::assertSame(['daily' => 1, 'monthly' => 1, 'yearly' => 2, 'raw_purged' => 0], $summary2);
        self::assertSame('2023-06-15', $this->out->getFrontier('daily'));

        // Run 3: 2023-06-15 (2 rows), then caught up to the cutoff.
        $summary3 = $this->out->run(['daily_limit' => 1]);
        self::assertSame(['daily' => 2, 'monthly' => 2, 'yearly' => 0, 'raw_purged' => 0], $summary3);

        // Final state equals the single unbounded run.
        self::assertSame(5, $this->countIn('visits_stats_daily'));
        self::assertSame(5, $this->countIn('visits_stats_monthly'));
        self::assertSame(2, $this->countIn('visits_stats_yearly'));

        $today = $this->out->siteToday();
        self::assertSame($this->out->addDays($today, -6), $this->out->getFrontier('daily'));
        self::assertSame('2023-07-01', $this->out->getFrontier('monthly'));
        self::assertSame('2023-01-01', $this->out->getFrontier('yearly'));
    }

    // ------------------------------------------------------------------
    // rebuildDaily()
    // ------------------------------------------------------------------

    /**
     * Tests that rebuildDaily() creates one aggregate row per dimension group
     * and is idempotent (delete + insert).
     */
    public function testRebuildDaily()
    {
        self::assertSame(2, $this->out->rebuildDaily('2022-01-15'));

        $rows = $this->dailyRowsFor('2022-01-15');
        self::assertCount(2, $rows);
        $byHash = $this->rowsByDimHash($rows);

        $g1 = $byHash[$this->dimHash('Chrome', '125.0', 'Windows', '11', 'en_EN')];
        self::assertSame('Chrome', $g1['browser']);
        self::assertSame('125.0', $g1['browser_version']);
        self::assertSame('Windows', $g1['os']);
        self::assertSame('11', $g1['os_version']);
        self::assertSame('en_EN', $g1['lang']);
        self::assertEquals(1, $g1['visits']);

        $g2 = $byHash[$this->dimHash('Chrome', '124.0', 'Linux', '5.15', 'de_DE')];
        self::assertEquals(1, $g2['visits']);

        // Rebuild again: the day is replaced, not appended.
        self::assertSame(2, $this->out->rebuildDaily('2022-01-15'));
        self::assertCount(2, $this->dailyRowsFor('2022-01-15'));
    }

    /**
     * Tests that rebuildDaily() overwrites stale aggregate rows for the day.
     */
    public function testRebuildDailyReplacesStaleAggregates()
    {
        $staleHash = md5('stale');
        $this->db->insert('visits_stats_daily')
            ->values([
                'date' => '2022-01-15',
                'browser' => 'Stale',
                'browser_version' => '',
                'os' => '',
                'os_version' => '',
                'lang' => '',
                'dim_hash' => $staleHash,
                'visits' => 99,
            ])
            ->execute();

        self::assertSame(2, $this->out->rebuildDaily('2022-01-15'));

        $rows = $this->dailyRowsFor('2022-01-15');
        self::assertCount(2, $rows);
        foreach ($rows as $row) {
            self::assertNotSame($staleHash, $row['dim_hash']);
        }
    }

    /**
     * Tests that rebuildDaily() is a no-op for a day that never had events.
     */
    public function testRebuildDailyWithoutRawDataIsNoOp()
    {
        self::assertSame(0, $this->out->rebuildDaily('2022-03-01'));
        self::assertCount(0, $this->dailyRowsFor('2022-03-01'));
    }

    /**
     * Tests the purged-day guard: a day whose raw data no longer exists must
     * not be rebuilt, and its existing aggregates must survive.
     */
    public function testRebuildDailyRefusesDayWithoutRawData()
    {
        $this->db->insert('visits_stats_daily')
            ->values([
                'date' => '2022-05-05',
                'browser' => 'Chrome',
                'browser_version' => '125.0',
                'os' => 'Windows',
                'os_version' => '11',
                'lang' => 'en_EN',
                'dim_hash' => $this->dimHash('Chrome', '125.0', 'Windows', '11', 'en_EN'),
                'visits' => 3,
            ])
            ->execute();

        try {
            $this->out->rebuildDaily('2022-05-05');
            self::fail('Expected RuntimeException was not thrown.');
        } catch (\RuntimeException $e) {
            self::assertSame('Refusing to rebuild day 2022-05-05: its raw data no longer exists.', $e->getMessage());
        }

        // The refused rebuild must not have touched the existing row.
        self::assertCount(1, $this->dailyRowsFor('2022-05-05'));
    }

    // ------------------------------------------------------------------
    // rebuildMonthly()
    // ------------------------------------------------------------------

    /**
     * Tests that rebuildMonthly() rolls the daily table up, normalizes the
     * month argument and sums multiple days of one month.
     */
    public function testRebuildMonthly()
    {
        $this->out->rebuildDaily('2022-01-15');
        $this->out->rebuildDaily('2022-12-20');

        // A mid-month date is normalized to the month start.
        self::assertSame(2, $this->out->rebuildMonthly('2022-01-15'));
        self::assertCount(2, $this->monthlyRowsFor('2022-01-01'));

        // A second raw day of the same month must be summed into the month row.
        $this->insertRawRow([
            'date' => '2022-01-20 08:00:00',
            'session_id' => 'sess_2022_01_extra',
            'browser' => 'Chrome',
            'browser_version' => '125.0',
        ]);
        $this->out->rebuildDaily('2022-01-20');

        self::assertSame(2, $this->out->rebuildMonthly('2022-01-01'));
        $jan = $this->monthlyRowsFor('2022-01-01');
        self::assertCount(2, $jan);
        $byHash = $this->rowsByDimHash($jan);

        // Chrome 125/Windows 11 now covers two days: 2 visits.
        self::assertEquals(2, $byHash[$this->dimHash('Chrome', '125.0', 'Windows', '11', 'en_EN')]['visits']);
        self::assertEquals(1, $byHash[$this->dimHash('Chrome', '124.0', 'Linux', '5.15', 'de_DE')]['visits']);

        self::assertSame(1, $this->out->rebuildMonthly('2022-12-01'));
        self::assertSame(3, $this->countIn('visits_stats_monthly'));
    }

    /**
     * Tests that rebuildMonthly() creates nothing for a month without daily rows.
     */
    public function testRebuildMonthlyWithoutDailyRows()
    {
        self::assertSame(0, $this->out->rebuildMonthly('2022-03-01'));
        self::assertCount(0, $this->monthlyRowsFor('2022-03-01'));
    }

    // ------------------------------------------------------------------
    // rebuildYearly()
    // ------------------------------------------------------------------

    /**
     * Tests that rebuildYearly() rolls the monthly table up across months and
     * is idempotent.
     */
    public function testRebuildYearly()
    {
        $this->out->rebuildDaily('2022-01-15');
        $this->out->rebuildDaily('2022-12-20');
        $this->out->rebuildDaily('2023-06-15');
        $this->out->rebuildMonthly('2022-01-01');
        $this->out->rebuildMonthly('2022-12-01');

        self::assertSame(2, $this->out->rebuildYearly(2022));

        $yearly = $this->yearlyRowsFor(2022);
        self::assertCount(2, $yearly);
        $byHash = $this->rowsByDimHash($yearly);

        // G1 spans January and December 2022: 2 visits.
        self::assertEquals(2, $byHash[$this->dimHash('Chrome', '125.0', 'Windows', '11', 'en_EN')]['visits']);
        self::assertEquals(1, $byHash[$this->dimHash('Chrome', '124.0', 'Linux', '5.15', 'de_DE')]['visits']);

        // Idempotent: replaced, not appended.
        self::assertSame(2, $this->out->rebuildYearly(2022));
        self::assertCount(2, $this->yearlyRowsFor(2022));
    }

    /**
     * Tests that rebuildYearly() creates nothing for a year without monthly rows.
     */
    public function testRebuildYearlyWithoutMonthlyRows()
    {
        // 2023 has raw days but no monthly rows in this test.
        self::assertSame(0, $this->out->rebuildYearly(2023));
        self::assertCount(0, $this->yearlyRowsFor(2023));
    }

    // ------------------------------------------------------------------
    // getFrontier() / saveFrontier()
    // ------------------------------------------------------------------

    /**
     * Tests that all frontiers default to the epoch when no progress row exists.
     */
    public function testGetFrontierReturnsEpochWhenUnset()
    {
        foreach (['daily', 'monthly', 'yearly', 'raw'] as $level) {
            self::assertSame('1000-01-01', $this->out->getFrontier($level));
        }
    }

    /**
     * Tests that saveFrontier() inserts a progress row and updates it in place.
     */
    public function testSaveFrontier()
    {
        $this->out->saveFrontier('daily', '2022-06-01');
        self::assertSame('2022-06-01', $this->out->getFrontier('daily'));

        $row = $this->progressRow('daily');
        self::assertNotNull($row);
        self::assertSame('daily', $row['level']);
        self::assertSame('2022-06-01', $row['frontier']);
        self::assertNotEmpty($row['updated']);

        $this->out->saveFrontier('daily', '2022-06-15');
        self::assertSame('2022-06-15', $this->out->getFrontier('daily'));
        self::assertSame(1, $this->countIn('visits_stats_progress'));
    }

    /**
     * Tests that frontiers only move forward (GREATEST): a smaller value never
     * regresses a persisted frontier (lease-expiry double runs).
     */
    public function testSaveFrontierNeverRegresses()
    {
        $this->out->saveFrontier('daily', '2022-06-15');
        $this->out->saveFrontier('daily', '2022-01-01');

        self::assertSame('2022-06-15', $this->out->getFrontier('daily'));
    }

    /**
     * Tests that getFrontier() rejects unknown levels.
     */
    public function testGetFrontierUnknownLevelThrows()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Unknown progress level: weekly');
        $this->out->getFrontier('weekly');
    }

    /**
     * Tests that saveFrontier() rejects unknown levels.
     */
    public function testSaveFrontierUnknownLevelThrows()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Unknown progress level: weekly');
        $this->out->saveFrontier('weekly', '2022-01-01');
    }

    /**
     * Tests that malformed date literals are rejected before reaching the database.
     */
    public function testInvalidDateLiteralThrows()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid Y-m-d date literal: 2022-1-1');
        $this->out->nextRawDateAfter('2022-1-1', '2023-01-01');
    }

    /**
     * Tests that rebuildDaily() rejects a date with a time component.
     */
    public function testRebuildDailyRejectsNonDateLiteral()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid Y-m-d date literal: 2022-05-05 10:00:00');
        $this->out->rebuildDaily('2022-05-05 10:00:00');
    }

    // ------------------------------------------------------------------
    // Candidate walkers
    // ------------------------------------------------------------------

    /**
     * Tests nextRawDateAfter(): inclusive lower bound, exclusive upper bound,
     * empty days skipped, null when caught up.
     */
    public function testNextRawDateAfter()
    {
        self::assertSame('2022-01-15', $this->out->nextRawDateAfter('1000-01-01', '2022-01-16'));
        self::assertSame(null, $this->out->nextRawDateAfter('1000-01-01', '2022-01-15')); // upper bound exclusive
        self::assertSame('2022-01-15', $this->out->nextRawDateAfter('2022-01-15', '2023-01-01')); // lower bound inclusive
        self::assertSame('2022-12-20', $this->out->nextRawDateAfter('2022-01-16', '2023-01-01')); // empty days skipped
        self::assertSame(null, $this->out->nextRawDateAfter('2022-12-21', '2023-06-15'));
        self::assertSame('2023-06-15', $this->out->nextRawDateAfter('2022-12-21', '2024-01-01'));
        self::assertSame(null, $this->out->nextRawDateAfter('2024-01-01', '2025-01-01')); // caught up
    }

    /**
     * Tests nextCandidateMonth(): the month of the first daily row inside
     * [$frontier, $dailyFrontier), normalized to the month start.
     */
    public function testNextCandidateMonth()
    {
        $this->out->rebuildDaily('2022-01-15');
        $this->out->rebuildDaily('2022-12-20');
        $this->out->rebuildDaily('2023-06-15');

        self::assertSame('2022-01-01', $this->out->nextCandidateMonth('1000-01-01', '2024-01-01'));
        self::assertSame('2022-12-01', $this->out->nextCandidateMonth('2022-02-01', '2024-01-01'));
        self::assertSame('2022-01-01', $this->out->nextCandidateMonth('2022-01-01', '2022-02-01'));
        self::assertSame(null, $this->out->nextCandidateMonth('1000-01-01', '2022-01-15')); // upper bound exclusive
        self::assertSame(null, $this->out->nextCandidateMonth('2023-07-01', '2024-01-01'));
    }

    /**
     * Tests nextCandidateYear(): the year of the first monthly row inside
     * [$frontier, $monthlyFrontier).
     */
    public function testNextCandidateYear()
    {
        $this->out->rebuildDaily('2022-01-15');
        $this->out->rebuildDaily('2022-12-20');
        $this->out->rebuildDaily('2023-06-15');
        $this->out->rebuildMonthly('2022-01-01');
        $this->out->rebuildMonthly('2022-12-01');
        $this->out->rebuildMonthly('2023-06-01');

        self::assertSame(2022, $this->out->nextCandidateYear('1000-01-01', '2024-01-01'));
        self::assertSame(2023, $this->out->nextCandidateYear('2023-01-01', '2024-01-01'));
        self::assertSame(null, $this->out->nextCandidateYear('2023-07-01', '2024-01-01'));
        self::assertSame(null, $this->out->nextCandidateYear('1000-01-01', '2022-01-01')); // upper bound exclusive
    }

    // ------------------------------------------------------------------
    // Date helpers
    // ------------------------------------------------------------------

    /**
     * Tests that siteToday() returns the site-local calendar date.
     */
    public function testSiteToday()
    {
        self::assertSame((new Date())->format('Y-m-d', true), $this->out->siteToday());
    }

    /**
     * Tests that firstOfMonth() normalizes any date to its month start.
     */
    public function testFirstOfMonth()
    {
        self::assertSame('2022-03-01', $this->out->firstOfMonth('2022-03-15'));
        self::assertSame('2022-03-01', $this->out->firstOfMonth('2022-03-01'));
        self::assertSame('2022-12-01', $this->out->firstOfMonth('2022-12-31'));
    }

    /**
     * Tests addDays() across month and year boundaries, including leap years.
     */
    public function testAddDays()
    {
        self::assertSame('2022-01-31', $this->out->addDays('2022-02-01', -1)); // month end
        self::assertSame('2022-03-01', $this->out->addDays('2022-02-28', 1)); // non-leap year
        self::assertSame('2024-02-29', $this->out->addDays('2024-02-28', 1)); // leap year
        self::assertSame('2024-03-01', $this->out->addDays('2024-02-29', 1));
        self::assertSame('2023-01-01', $this->out->addDays('2022-12-31', 1)); // year rollover
        self::assertSame('2021-12-31', $this->out->addDays('2022-01-01', -1)); // year rollover backwards
        self::assertSame('2022-03-13', $this->out->addDays('2022-03-01', 12));

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid Y-m-d date literal: 2022-3-1');
        $this->out->addDays('2022-3-1', 1);
    }

    /**
     * Tests addMonths() including normalization to the month start and
     * negative offsets.
     */
    public function testAddMonths()
    {
        self::assertSame('2022-02-01', $this->out->addMonths('2022-01-31', 1)); // normalized to month start first
        self::assertSame('2023-01-01', $this->out->addMonths('2022-12-01', 1)); // year rollover
        self::assertSame('2022-02-01', $this->out->addMonths('2022-03-01', -1));
        self::assertSame('2021-12-01', $this->out->addMonths('2022-01-01', -1));
        self::assertSame('2022-07-01', $this->out->addMonths('2022-01-01', 6));

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid Y-m-d date literal: 2022-1-01');
        $this->out->addMonths('2022-1-01', 1);
    }

    // ------------------------------------------------------------------
    // purgeOldDays()
    // ------------------------------------------------------------------

    /**
     * Tests that purgeOldDays() respects the per-run limit, resumes from the
     * persisted raw frontier and pins it to the retention floor once caught up.
     */
    public function testPurgeOldDaysRespectsLimitAndResumes()
    {
        // Mark all fixture days as fully aggregated.
        $this->out->saveFrontier('daily', '2024-01-01');

        // Limit 1: only the oldest day (2 raw rows) is purged.
        self::assertSame(1, $this->out->purgeOldDays('2023-01-01', 1));
        self::assertSame('2022-01-16', $this->out->getFrontier('raw'));
        self::assertSame(0, $this->rawCountFor('2022-01-15'));
        self::assertSame(1, $this->rawCountFor('2022-12-20'));
        self::assertSame(2, $this->rawCountFor('2023-06-15'));

        // Resuming continues from the persisted frontier. Only 2022-12-20 lies
        // below the floor, so exactly one more day is purged; 2023-06-15 is
        // newer than the floor and protected. Once the walker is exhausted
        // below it, the raw frontier is pinned to the floor.
        self::assertSame(1, $this->out->purgeOldDays('2023-01-01', 10));
        self::assertSame(0, $this->rawCountFor('2022-12-20'));
        self::assertSame(2, $this->rawCountFor('2023-06-15'));
        self::assertSame(2, $this->countIn('visits_stats'));
        self::assertSame('2023-01-01', $this->out->getFrontier('raw'));
    }

    /**
     * Tests the guard: days inside the recent window (>= daily frontier) are
     * never purged.
     */
    public function testPurgeOldDaysStopsAtDailyFrontier()
    {
        // Without a daily frontier (epoch) no day is fully aggregated: nothing is purged.
        self::assertSame(0, $this->out->purgeOldDays('2023-01-01', 10));
        self::assertSame(5, $this->countIn('visits_stats'));
        self::assertSame('1000-01-01', $this->out->getFrontier('raw'));

        // With a frontier between two fixture days, purging stops exactly at the frontier.
        $this->out->saveFrontier('daily', '2022-12-20');
        self::assertSame(1, $this->out->purgeOldDays('2023-01-01', 10));
        self::assertSame(0, $this->rawCountFor('2022-01-15'));
        self::assertSame(1, $this->rawCountFor('2022-12-20')); // >= frontier: untouched
        self::assertSame(2, $this->rawCountFor('2023-06-15'));
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
