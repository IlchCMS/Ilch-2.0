<?php

namespace Modules\Admin\scheduler;

use Ilch\Scheduler\Scheduler;
use Modules\User\Mappers\AuthToken as AuthTokenMapper;

/**
 * Deletes expired remember-me auth tokens.
 *
 * Runs as the daily scheduler task 'admin.authTokens.cleanup'; replaces the
 * cleanup that previously only ran when the admin center was opened.
 */
final class AuthTokensCleanup
{
    public static function handle(?array $payload, Scheduler $scheduler): void
    {
        // One fast DELETE — idempotent, no budget management needed.
        (new AuthTokenMapper())->deleteExpiredAuthTokens();
    }
}

// Self-registration: this file is lazy-included by
// \Ilch\Scheduler\Scheduler::ensureHandlerLoaded() only when a due
// 'admin.' task needs its handlers.
Scheduler::registerHandler('admin.authTokens.cleanup', [AuthTokensCleanup::class, 'handle']);
