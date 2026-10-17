<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\scheduler;

use Ilch\Scheduler\Scheduler;
use Modules\Statistic\Mappers\StatisticAggregate;

/**
 * Handler for the scheduled task "statistic.aggregateVisits".
 *
 * This file is not autoloaded: \Ilch\Scheduler\Scheduler::ensureHandlerLoaded()
 * requires it once when the task first comes due. The handler is registered at
 * the bottom of the file, right after the class definition.
 *
 * Context notes:
 * - The handler context has no translator available; keep any error messages
 *   short and plain (they are stored in the task row's last_error).
 * - Exceptions thrown here are caught by the scheduler, which stores a short
 *   error message and backs the task off; the next run resumes at the last
 *   persisted frontier, so failed runs are retried, not skipped.
 */
final class AggregateVisits
{
    /**
     * @param array<string, mixed>|null $payload Decoded task payload; keys are
     *   optional overrides: daily_limit, monthly_limit, yearly_limit,
     *   recent_days, raw_retention_days, purge_limit.
     * @param Scheduler $scheduler
     * @return void
     */
    public static function handle(?array $payload, Scheduler $scheduler): void
    {
        (new StatisticAggregate())->run($payload ?? [], $scheduler);
    }
}

Scheduler::registerHandler('statistic.aggregateVisits', [AggregateVisits::class, 'handle']);
