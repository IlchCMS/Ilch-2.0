<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Mappers;

use Ilch\Scheduler\Scheduler;

/**
 * Aggregation engine for the visit statistics.
 *
 * Maintains three aggregate tables built from the raw event table
 * [prefix]_visits_stats:
 *   - [prefix]_visits_stats_daily    one row per (day, dimension quintuple)
 *   - [prefix]_visits_stats_monthly  one row per (month, dimension quintuple)
 *   - [prefix]_visits_stats_yearly   one row per (year, dimension quintuple)
 * plus [prefix]_visits_stats_progress (one row per level: daily, monthly,
 * yearly, raw) which tracks how far each level has been processed.
 *
 * One run() call works six bounded phases in order:
 *   1. daily backlog, 2. daily recent window, 3. monthly backlog,
 *   4. monthly window, 5. yearly (backlog + window), 6. raw retention purge.
 *
 * Safety properties:
 *   - Every period rebuild is idempotent (delete + insert in one transaction),
 *     so catch-up re-runs and lease-expiry double runs are safe.
 *   - A frontier is persisted only after the matching aggregation transaction
 *     has committed, and only monotonically (GREATEST), so a crashed run
 *     resumes exactly where it stopped.
 *   - A period is rolled up only once its source level is fully aggregated.
 *   - Raw rows are purged per day, only after that day is fully aggregated,
 *     and never from inside the recent window.
 *   - Rebuilding a day whose raw data no longer exists is refused (the
 *     "purged-day guard"), so manual or scheduled rebuilds can never wipe
 *     aggregate data that has no raw source left.
 *
 * Raw SQL is used throughout: unquoted [prefix]_ table names (robust under
 * both readings of Mysql::getSqlWithPrefix()), backtick-quoted columns, and
 * machine-generated, format-validated boundary literals.
 *
 * @since 2.2.22
 */
class StatisticAggregate extends \Ilch\Mapper
{
    /** Stop a phase early once less than this many milliseconds of the request remain. */
    private const EARLY_STOP_MS = 250;

    /** Initial frontier value; also excludes zero-date raw rows from candidacy. */
    private const FRONTIER_EPOCH = '1000-01-01';

    /**
     * PHP-side defaults. The task payload keys are overrides only: the
     * scheduler's ensureTask() never updates an existing task, so default
     * changes ship as PHP changes, not re-registrations.
     */
    private const DEFAULT_DAILY_LIMIT        = 14;

    /** Max calendar months rolled up per run (monthly backlog phase). */
    private const DEFAULT_MONTHLY_LIMIT      = 6;

    /** Max calendar years rolled up per run (yearly backlog phase). */
    private const DEFAULT_YEARLY_LIMIT       = 12;

    /** Days of the recent window [cutoff, today] rebuilt every run. */
    private const DEFAULT_RECENT_DAYS        = 7;
    /**
     * ...
     * Shipped as 0 in 2.2.22: the purge is a transitional no-op until the
     * follow-up release, which raises this default to 365 and flips the task
     * payload (frozen at 0 by ensureTask()) to 365 in one shot.
     */
    private const DEFAULT_RAW_RETENTION_DAYS = 0;

    /** Max days with raw events purged per run (retention phase). */
    private const DEFAULT_PURGE_LIMIT        = 30;

    /**
     * Run all six aggregation phases.
     *
     * @param array<string, mixed> $payload Task payload overrides: daily_limit,
     *   monthly_limit, yearly_limit, recent_days, raw_retention_days, purge_limit.
     * @param Scheduler|null $scheduler When null (unit tests), no time budget is applied.
     * @return array{daily: int, monthly: int, yearly: int, raw_purged: int}
     */
    public function run(array $payload, ?Scheduler $scheduler = null): array
    {
        $dailyLimit    = (int)($payload['daily_limit'] ?? self::DEFAULT_DAILY_LIMIT);
        $monthlyLimit  = (int)($payload['monthly_limit'] ?? self::DEFAULT_MONTHLY_LIMIT);
        $yearlyLimit   = (int)($payload['yearly_limit'] ?? self::DEFAULT_YEARLY_LIMIT);
        $recentDays    = (int)($payload['recent_days'] ?? self::DEFAULT_RECENT_DAYS);
        $retentionDays = (int)($payload['raw_retention_days'] ?? self::DEFAULT_RAW_RETENTION_DAYS);
        $purgeLimit    = (int)($payload['purge_limit'] ?? self::DEFAULT_PURGE_LIMIT);

        $today  = $this->siteToday();
        $cutoff = $this->addDays($today, 1 - $recentDays); // first day of the recent window
        $floor  = $retentionDays > 0 ? $this->addDays($today, -$retentionDays) : null;

        $summary = ['daily' => 0, 'monthly' => 0, 'yearly' => 0, 'raw_purged' => 0];

        // 1) Daily backlog (bounded by dailyLimit).
        $summary['daily'] += $this->runDailyBacklog($this->getFrontier('daily'), $cutoff, $dailyLimit, $scheduler);
        // 2) Daily recent window [cutoff, today].
        $summary['daily'] += $this->runDailyWindow($cutoff, $today, $scheduler);
        $dailyFrontier = $this->getFrontier('daily'); // final value for this run

        // 3) Monthly backlog, 4) monthly window.
        $summary['monthly'] += $this->runMonthlyBacklog($this->getFrontier('monthly'), $dailyFrontier, $monthlyLimit, $scheduler);
        $summary['monthly'] += $this->runMonthlyWindow($cutoff, $today, $scheduler);
        $monthlyFrontier = $this->getFrontier('monthly');

        // 5) Yearly backlog, then yearly window.
        $summary['yearly'] += $this->runYearlyBacklog($this->getFrontier('yearly'), $monthlyFrontier, $yearlyLimit, $scheduler);
        $summary['yearly'] += $this->runYearlyWindow($cutoff, $today, $scheduler);

        // 6) Raw retention purge (skipped entirely when retention is disabled,
        //    preserving the legacy never-delete behavior).
        if ($floor !== null) {
            $summary['raw_purged'] = $this->purgeOldDays($floor, $purgeLimit, $scheduler);
        }

        return $summary;
    }

    /**
     * Phase 1: rebuild raw days that have events and are not fully aggregated yet.
     * Walks only days that contain events (indexed MIN dive); empty days are
     * skipped for free.
     *
     * @param string $frontier 'Y-m-d'; all raw events with date < frontier are aggregated
     * @param string $cutoff 'Y-m-d', exclusive upper bound (first day of the recent window)
     * @param int $limit Maximum number of days rebuilt in this run
     * @return int Number of days rebuilt
     */
    private function runDailyBacklog(string $frontier, string $cutoff, int $limit, ?Scheduler $scheduler): int
    {
        $rebuilt = 0;

        for ($i = 0; $i < $limit; $i++) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $day = $this->nextRawDateAfter($frontier, $cutoff);
            if ($day === null) {
                // Caught up: pin the frontier to the cutoff (monotonic no-op if already there).
                $this->saveFrontier('daily', $cutoff);
                break;
            }

            $rebuilt += $this->rebuildDaily($day);

            // Persist the next raw day with events (skipping empty days for free),
            // or the cutoff when none remain. Never exceeds $cutoff.
            $frontier = $this->nextRawDateAfter($this->addDays($day, 1), $cutoff) ?? $cutoff;
            $this->saveFrontier('daily', $frontier);
        }

        return $rebuilt;
    }

    /**
     * Phase 2: rebuild every calendar day of the recent window [cutoff, today],
     * covering late/corrected raw rows. No frontier changes.
     *
     * @return int Number of day rebuilds performed
     */
    private function runDailyWindow(string $cutoff, string $today, ?Scheduler $scheduler): int
    {
        $rebuilt = 0;

        for ($day = $cutoff; $day <= $today; $day = $this->addDays($day, 1)) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $rebuilt += $this->rebuildDaily($day);
        }

        return $rebuilt;
    }

    /**
     * Phase 3: roll up months that are fully aggregated at the daily level
     * (the month's end is not before the current daily frontier).
     *
     * @param string $frontier 'Y-m-d' month start; all months starting before it are fully rolled up
     * @param string $dailyFrontier Final daily frontier of this run
     * @param int $limit Maximum number of months rolled up in this run
     * @return int Number of months rebuilt
     */
    private function runMonthlyBacklog(string $frontier, string $dailyFrontier, int $limit, ?Scheduler $scheduler): int
    {
        $rebuilt = 0;

        for ($i = 0; $i < $limit; $i++) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $month = $this->nextCandidateMonth($frontier, $dailyFrontier);
            if ($month === null) {
                break;
            }

            $monthEnd = $this->addMonths($month, 1);
            if ($monthEnd > $dailyFrontier) {
                // The month still intersects the unprocessed daily range:
                // wait for the daily catch-up (later months cannot be eligible yet).
                break;
            }

            $rebuilt += $this->rebuildMonthly($month);
            $frontier = $monthEnd;
            $this->saveFrontier('monthly', $frontier);
        }

        return $rebuilt;
    }

    /**
     * Phase 4: rebuild every month that intersects the recent window
     * [cutoff, today] (1-3 months). No frontier changes.
     *
     * @return int Number of month rebuilds performed
     */
    private function runMonthlyWindow(string $cutoff, string $today, ?Scheduler $scheduler): int
    {
        $rebuilt   = 0;
        $windowEnd = $this->firstOfMonth($today);

        for ($month = $this->firstOfMonth($cutoff); $month <= $windowEnd; $month = $this->addMonths($month, 1)) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $rebuilt += $this->rebuildMonthly($month);
        }

        return $rebuilt;
    }

    /**
     * Phase 5a: roll up years that are fully rolled up at the monthly level
     * (Dec 31 of the year is before the current monthly frontier).
     *
     * @param string $frontier 'Y-m-d' (YYYY-01-01); all years starting before it are fully rolled up
     * @param string $monthlyFrontier Final monthly frontier of this run
     * @param int $limit Maximum number of years rolled up in this run
     * @return int Number of years rebuilt
     */
    private function runYearlyBacklog(string $frontier, string $monthlyFrontier, int $limit, ?Scheduler $scheduler): int
    {
        $rebuilt = 0;

        for ($i = 0; $i < $limit; $i++) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $year = $this->nextCandidateYear($frontier, $monthlyFrontier);
            if ($year === null) {
                break;
            }

            $yearEnd = (string)($year + 1) . '-01-01';
            if ($yearEnd > $monthlyFrontier) {
                // The year still intersects the unprocessed monthly range:
                // wait for the monthly catch-up.
                break;
            }

            $rebuilt += $this->rebuildYearly($year);
            $frontier = $yearEnd;
            $this->saveFrontier('yearly', $frontier);
        }

        return $rebuilt;
    }

    /**
     * Phase 5b: rebuild every year that intersects the recent window
     * [cutoff, today] (1-2 years). No frontier changes.
     *
     * @return int Number of year rebuilds performed
     */
    private function runYearlyWindow(string $cutoff, string $today, ?Scheduler $scheduler): int
    {
        $rebuilt   = 0;
        $startYear = (int)substr($cutoff, 0, 4);
        $endYear   = (int)substr($today, 0, 4);

        for ($year = $startYear; $year <= $endYear; $year++) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $rebuilt += $this->rebuildYearly($year);
        }

        return $rebuilt;
    }

    /**
     * Phase 6: purge raw rows older than the retention floor, one day at a time.
     * A day is purged only once it is fully aggregated (day < daily frontier),
     * which is always true for correct configuration; the check is the guard
     * against a misconfigured small retention period.
     *
     * @param string $retentionFloor 'Y-m-d'; raw rows with date < floor are candidates
     * @param int $limit Maximum number of days (with events) purged in this run
     * @param Scheduler|null $scheduler
     * @return int Number of days purged
     */
    public function purgeOldDays(string $retentionFloor, int $limit, ?Scheduler $scheduler = null): int
    {
        $retentionFloor = $this->assertDate($retentionFloor);

        // Runs last, so the daily frontier is final for this run.
        $dailyFrontier = $this->getFrontier('daily');
        $frontier      = $this->getFrontier('raw');
        $purged        = 0;

        for ($i = 0; $i < $limit; $i++) {
            if ($this->shouldStop($scheduler)) {
                break;
            }

            $day = $this->nextRawDateAfter($frontier, $retentionFloor);
            if ($day === null) {
                // Nothing left to purge; pin the frontier to the floor
                // (monotonic no-op if it is already there).
                $this->saveFrontier('raw', $retentionFloor);
                break;
            }

            if ($day >= $dailyFrontier) {
                // Not fully aggregated yet: nothing purged, frontier unchanged.
                break;
            }

            $this->purgeDay($day);
            $frontier = $this->addDays($day, 1);
            $this->saveFrontier('raw', $frontier);
            $purged++;
        }

        return $purged;
    }

    /**
     * Rebuild the daily aggregates for one day (the retry-safe unit).
     *
     * Guard: a day whose raw data no longer exists must not be rebuilt — an
     * empty rebuild would wipe the day's aggregates and corrupt the monthly
     * and yearly rollups built from them. Days that never had events are a no-op.
     *
     * @param string $date 'Y-m-d'
     * @return int Number of aggregate rows (re)created
     * @throws \RuntimeException If the day has aggregates but no raw data left
     * @throws \Ilch\Database\Exception On database failure (after rollback)
     */
    public function rebuildDaily(string $date): int
    {
        $date = $this->assertDate($date);
        $next = $this->addDays($date, 1);

        $rawMin = $this->db()->queryCell(
            "SELECT MIN(`date`) FROM [prefix]_visits_stats
             WHERE `date` >= \"$date 00:00:00\" AND `date` < \"$next 00:00:00\""
        );
        if ($rawMin === null) {
            $aggregateMin = $this->db()->queryCell(
                "SELECT MIN(`date`) FROM [prefix]_visits_stats_daily WHERE `date` = \"$date\""
            );
            if ($aggregateMin !== null) {
                throw new \RuntimeException('Refusing to rebuild day ' . $date . ': its raw data no longer exists.');
            }

            return 0; // Day never had events.
        }

        $db = $this->db();
        $db->query('START TRANSACTION');
        try {
            $db->query("DELETE FROM [prefix]_visits_stats_daily WHERE `date` = \"$date\"");
            $db->query("INSERT INTO [prefix]_visits_stats_daily
                (`date`, `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`, `visits`)
                SELECT \"$date\",
                       IFNULL(`browser`,''), IFNULL(`browser_version`,''), IFNULL(`os`,''),
                       IFNULL(`os_version`,''), IFNULL(`lang`,''),
                       MD5(CONCAT(IFNULL(`browser`,''), CHAR(31), IFNULL(`browser_version`,''), CHAR(31),
                                 IFNULL(`os`,''), CHAR(31), IFNULL(`os_version`,''), CHAR(31),
                                 IFNULL(`lang`,''))),
                       COUNT(*)
                FROM [prefix]_visits_stats
                WHERE `date` >= \"$date 00:00:00\" AND `date` < \"$next 00:00:00\"
                GROUP BY IFNULL(`browser`,''), IFNULL(`browser_version`,''), IFNULL(`os`,''),
                        IFNULL(`os_version`,''), IFNULL(`lang`,'')");
            $inserted = $db->getAffectedRows();
            $db->query('COMMIT');

            return $inserted;
        } catch (\Throwable $e) {
            try {
                $db->query('ROLLBACK');
            } catch (\Throwable $rollbackError) {
                // Ignore rollback failure; rethrow the original error.
            }

            throw $e;
        }
    }

    /**
     * Rebuild the monthly aggregates for one month from the daily table.
     *
     * @param string $month 'Y-m-d'; only the first two components are used
     * @return int Number of aggregate rows (re)created
     * @throws \Ilch\Database\Exception On database failure (after rollback)
     */
    public function rebuildMonthly(string $month): int
    {
        $month    = $this->firstOfMonth($this->assertDate($month));
        $monthEnd = $this->addMonths($month, 1);

        $db = $this->db();
        $db->query('START TRANSACTION');
        try {
            $db->query("DELETE FROM [prefix]_visits_stats_monthly WHERE `month` = \"$month\"");
            $db->query("INSERT INTO [prefix]_visits_stats_monthly
                (`month`, `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`, `visits`)
                SELECT \"$month\", `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`, SUM(`visits`)
                FROM [prefix]_visits_stats_daily
                WHERE `date` >= \"$month\" AND `date` < \"$monthEnd\"
                GROUP BY `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`");
            $inserted = $db->getAffectedRows();
            $db->query('COMMIT');

            return $inserted;
        } catch (\Throwable $e) {
            try {
                $db->query('ROLLBACK');
            } catch (\Throwable $rollbackError) {
            }

            throw $e;
        }
    }

    /**
     * Rebuild the yearly aggregates for one year from the monthly table.
     *
     * @param int $year
     * @return int Number of aggregate rows (re)created
     * @throws \Ilch\Database\Exception On database failure (after rollback)
     */
    public function rebuildYearly(int $year): int
    {
        $yearStart = $year . '-01-01';
        $yearEnd   = ($year + 1) . '-01-01';

        $db = $this->db();
        $db->query('START TRANSACTION');
        try {
            $db->query("DELETE FROM [prefix]_visits_stats_yearly WHERE `year` = $year");
            $db->query("INSERT INTO [prefix]_visits_stats_yearly
                (`year`, `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`, `visits`)
                SELECT $year, `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`, SUM(`visits`)
                FROM [prefix]_visits_stats_monthly
                WHERE `month` >= \"$yearStart\" AND `month` < \"$yearEnd\"
                GROUP BY `browser`, `browser_version`, `os`, `os_version`, `lang`, `dim_hash`");
            $inserted = $db->getAffectedRows();
            $db->query('COMMIT');

            return $inserted;
        } catch (\Throwable $e) {
            try {
                $db->query('ROLLBACK');
            } catch (\Throwable $rollbackError) {
            }

            throw $e;
        }
    }

    /**
     * @param string $level One of 'daily', 'monthly', 'yearly', 'raw'
     * @return string 'Y-m-d' frontier (FRONTIER_EPOCH when no row exists yet)
     */
    public function getFrontier(string $level): string
    {
        $this->assertLevel($level);

        $value = $this->db()->queryCell(
            "SELECT `frontier` FROM [prefix]_visits_stats_progress WHERE `level` = \"$level\""
        );

        return $value !== null ? $this->assertDate((string)$value) : self::FRONTIER_EPOCH;
    }

    /**
     * Persist a frontier monotonically.
     *
     * GREATEST() keeps the larger value, so overlapping runs (lease expiry)
     * can never regress a frontier.
     *
     * @param string $level One of 'daily', 'monthly', 'yearly', 'raw'
     * @param string $frontier 'Y-m-d'
     */
    public function saveFrontier(string $level, string $frontier): void
    {
        $this->assertLevel($level);
        $frontier = $this->assertDate($frontier);

        $now = (new \Ilch\Date())->format('Y-m-d H:i:s', true);
        $this->db()->query(
            "INSERT INTO [prefix]_visits_stats_progress (`level`, `frontier`, `updated`)
             VALUES (\"$level\", \"$frontier\", \"$now\")
             ON DUPLICATE KEY UPDATE
                 `frontier` = GREATEST(`frontier`, VALUES(`frontier`)),
                 `updated` = VALUES(`updated`)"
        );
    }

    /**
     * Next raw day with events in [$from, $stopExclusive) (both 'Y-m-d').
     * Single indexed MIN dive on idx_date; empty days cost nothing.
     *
     * @return string|null 'Y-m-d', or null when no such day exists
     */
    public function nextRawDateAfter(string $from, string $stopExclusive): ?string
    {
        $from          = $this->assertDate($from);
        $stopExclusive = $this->assertDate($stopExclusive);

        $value = $this->db()->queryCell(
            "SELECT MIN(`date`) FROM [prefix]_visits_stats
             WHERE `date` >= \"$from 00:00:00\" AND `date` < \"$stopExclusive 00:00:00\""
        );

        return $value === null ? null : $this->assertDate(substr((string)$value, 0, 10));
    }

    /**
     * Next candidate month: the month of the first daily row in
     * [$monthStart, $dailyFrontier).
     *
     * @param string $monthStart 'Y-m-d' (a month start, or the epoch)
     * @param string $dailyFrontier 'Y-m-d'
     * @return string|null 'Y-m-01' month start, or null when no candidate exists
     */
    public function nextCandidateMonth(string $monthStart, string $dailyFrontier): ?string
    {
        $monthStart    = $this->assertDate($monthStart);
        $dailyFrontier = $this->assertDate($dailyFrontier);

        $value = $this->db()->queryCell(
            "SELECT MIN(`date`) FROM [prefix]_visits_stats_daily
             WHERE `date` >= \"$monthStart\" AND `date` < \"$dailyFrontier\""
        );

        return $value === null ? null : $this->firstOfMonth((string)$value);
    }

    /**
     * Next candidate year: the year of the first monthly row in
     * [$yearStart, $monthlyFrontier).
     *
     * @param string $yearStart 'Y-m-d' (a year start, or the epoch)
     * @param string $monthlyFrontier 'Y-m-d'
     * @return int|null Year, or null when no candidate exists
     */
    public function nextCandidateYear(string $yearStart, string $monthlyFrontier): ?int
    {
        $yearStart       = $this->assertDate($yearStart);
        $monthlyFrontier = $this->assertDate($monthlyFrontier);

        $value = $this->db()->queryCell(
            "SELECT MIN(`month`) FROM [prefix]_visits_stats_monthly
             WHERE `month` >= \"$yearStart\" AND `month` < \"$monthlyFrontier\""
        );

        return $value === null ? null : (int)substr((string)$value, 0, 4);
    }

    /**
     * Site-local "today" — the same calendar the raw rows are written with
     * (see \Modules\Statistic\Mappers\Statistic).
     */
    public function siteToday(): string
    {
        return (new \Ilch\Date())->format('Y-m-d', true);
    }

    /**
     * First day of the month of $date.
     *
     * @param string $date 'Y-m-d'
     * @return string 'Y-m-01'
     */
    public function firstOfMonth(string $date): string
    {
        return substr($this->assertDate($date), 0, 7) . '-01';
    }

    /**
     * Calendar arithmetic on a 'Y-m-d' date (timezone-independent).
     *
     * @param string $date 'Y-m-d'
     * @param int $days Positive or negative number of days
     * @return string 'Y-m-d'
     */
    public function addDays(string $date, int $days): string
    {
        $date = $this->assertDate($date);

        return (new \DateTimeImmutable($date))
            ->modify(($days < 0 ? '-' : '') . abs($days) . ' days')
            ->format('Y-m-d');
    }

    /**
     * Add whole months to a month start ('Y-m-01').
     *
     * @param string $monthStart 'Y-m-d'
     * @param int $months Positive or negative number of months
     * @return string 'Y-m-01'
     */
    public function addMonths(string $monthStart, int $months): string
    {
        $monthStart = $this->firstOfMonth($monthStart);

        return (new \DateTimeImmutable($monthStart))
                ->modify(($months < 0 ? '-' : '') . abs($months) . ' months')
                ->format('Y-m') . '-01';
    }

    /**
     * Stop the current phase early when the request time budget is nearly gone.
     */
    private function shouldStop(?Scheduler $scheduler): bool
    {
        return $scheduler !== null && $scheduler->timeLeftMs() < self::EARLY_STOP_MS;
    }

    /**
     * Delete one raw day: a single atomic statement (no transaction wrapper).
     *
     * @param string $day 'Y-m-d'
     * @return int Number of deleted rows
     */
    private function purgeDay(string $day): int
    {
        $day  = $this->assertDate($day);
        $next = $this->addDays($day, 1);

        $this->db()->query(
            "DELETE FROM [prefix]_visits_stats
             WHERE `date` >= \"$day 00:00:00\" AND `date` < \"$next 00:00:00\""
        );

        return $this->db()->getAffectedRows();
    }

    /**
     * @param string $level
     * @throws \InvalidArgumentException For levels outside daily/monthly/yearly/raw
     */
    private function assertLevel(string $level): void
    {
        if (!in_array($level, ['daily', 'monthly', 'yearly', 'raw'], true)) {
            throw new \InvalidArgumentException('Unknown progress level: ' . $level);
        }
    }

    /**
     * Validate a machine-generated boundary literal before it is interpolated.
     *
     * @param string $value
     * @return string
     * @throws \InvalidArgumentException
     */
    private function assertDate(string $value): string
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            throw new \InvalidArgumentException('Invalid Y-m-d date literal: ' . $value);
        }

        return $value;
    }
}
