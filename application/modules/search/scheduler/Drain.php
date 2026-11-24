<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\scheduler;

use Ilch\Search\Queue;
use Ilch\Search\Store;
use Ilch\Scheduler\Scheduler;

/**
 * Handler for the scheduled task "search.drain".
 *
 * This file is not autoloaded: \Ilch\Scheduler\Scheduler::ensureHandlerLoaded()
 * requires it once when the task first comes due. The handler is registered at
 * the bottom of the file, right after the class definition.
 *
 * Context notes:
 * - The handler context has no translator available; keep any error messages
 *   short and plain (they are stored in the task row's last_error).
 * - Exceptions thrown here are caught by the scheduler, which stores a short
 *   error message and backs the task off; unprocessed queue rows stay queued
 *   and are picked up on the next run.
 */
final class Drain
{
    /**
     * @param array<string, mixed>|null $payload Decoded task payload; keys are
     *   optional overrides: batch, time_limit_ms.
     * @param Scheduler $scheduler
     * @return void
     */
    public static function handle(?array $payload, Scheduler $scheduler): void
    {
        $payload = $payload ?? [];

        // Cheap cached no-op once the index exists; this is the only place the
        // one-off FULLTEXT ALTER may run (Queue::flush() with a scheduler).
        (new Store())->ensureFulltextIndex();

        $queue = new Queue();
        $queue->flush(
            (int)($payload['batch'] ?? 50),
            (int)($payload['time_limit_ms'] ?? 3000),
            $scheduler
        );
    }
}

Scheduler::registerHandler('search.drain', [Drain::class, 'handle']);
