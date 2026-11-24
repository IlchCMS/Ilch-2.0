<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\scheduler;

use Ilch\Search\Indexers;
use Ilch\Search\Store;
use Ilch\Scheduler\Scheduler;

/**
 * Handler for the scheduled task "search.backfill".
 *
 * This file is not autoloaded: \Ilch\Scheduler\Scheduler::ensureHandlerLoaded()
 * requires it once when the task first comes due. The handler is registered at
 * the bottom of the file, right after the class definition.
 *
 * Frontier-based initial/bulk indexing (statistic's visits_stats_progress
 * idiom): each module advances its own persisted frontier, so the task is a
 * no-op once every module is caught up. The importer's final search step
 * reuses the same frontiers (in larger batches).
 *
 * Context notes:
 * - The handler context has no translator available; keep any error messages
 *   short and plain (they are stored in the task row's last_error).
 * - Exceptions thrown here are caught by the scheduler, which stores a short
 *   error message and backs the task off; the next run resumes at the last
 *   persisted frontier, so failed runs are retried, not skipped.
 */
final class Backfill
{
    /**
     * @param array<string, mixed>|null $payload Decoded task payload; keys are
     *   optional overrides: batch.
     * @param Scheduler $scheduler
     * @return void
     */
    public static function handle(?array $payload, Scheduler $scheduler): void
    {
        $payload = $payload ?? [];
        $batch   = max(1, (int)($payload['batch'] ?? 100));

        $store = new Store();

        foreach (array_keys(Indexers::modules()) as $module) {
            // Stop early: respect the request budget.
            if ($scheduler->timeLeftMs() < 500) {
                break;
            }

            $frontier = $store->getFrontier($module);
            $indexer  = Indexers::get($module);
            if ($indexer === null) {
                continue;
            }

            $start = microtime(true);
            $frontier = $indexer->backfillBatch($frontier, $batch);
            $store->setFrontier($module, $frontier);

            // Slow batch (large forum scope): yield until the next interval.
            if ((microtime(true) - $start) > 4.0) {
                break;
            }
        }
    }
}

Scheduler::registerHandler('search.backfill', [Backfill::class, 'handle']);
