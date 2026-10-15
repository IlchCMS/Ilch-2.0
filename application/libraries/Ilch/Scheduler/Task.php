<?php

namespace Ilch\Scheduler;

/**
 * Data transfer object for one row of `[prefix]_scheduler_tasks`.
 *
 * Mirrors the style of the Ilch models (e.g. Modules\Comment\Model\Comment):
 * typed protected properties, nullable where the schema allows NULL, fluent
 * getters/setters. It performs no database access of its own — all CRUD belongs
 * to \Ilch\Scheduler\Scheduler.
 *
 * Datetime columns are UTC: parsed as \DateTimeImmutable in UTC on read, to be
 * written back as UTC 'Y-m-d H:i:s' strings. `dailyTime` is intentionally a raw
 * H:i:s string: it is a schedule parameter, not a point in time.
 */
final class Task
{
    protected ?int $id = null;
    protected string $name = '';
    protected string $handler = '';
    protected string $type = Scheduler::TYPE_INTERVAL;
    protected ?int $intervalSeconds = null;
    protected ?string $dailyTime = null;
    protected ?string $timezone = null;
    protected ?array $payload = null;
    protected bool $enabled = true;
    protected ?\DateTimeImmutable $nextRun = null;
    protected ?\DateTimeImmutable $lastStarted = null;
    protected ?\DateTimeImmutable $lastFinished = null;
    protected ?string $lastStatus = null;
    protected ?string $lastError = null;
    protected int $failures = 0;
    protected ?string $leaseOwner = null;
    protected ?\DateTimeImmutable $leaseExpires = null;
    protected ?\DateTimeImmutable $created = null;

    /**
     * Build a Task from a raw database row (snake_case columns, UTC datetime strings).
     *
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        $task = new self();

        $task->id              = $row['id'] !== null ? (int)$row['id'] : null;
        $task->name            = (string)($row['name'] ?? '');
        $task->handler         = (string)($row['handler'] ?? '');
        $task->type            = (string)($row['type'] ?? Scheduler::TYPE_INTERVAL);
        $task->intervalSeconds = $row['interval_seconds'] !== null ? (int)$row['interval_seconds'] : null;
        $task->dailyTime       = $row['daily_time'] !== null ? (string)$row['daily_time'] : null;
        $task->timezone        = $row['timezone'] !== null ? (string)$row['timezone'] : null;
        $task->enabled         = (bool)(int)($row['enabled'] ?? 1);
        $task->failures        = (int)($row['failures'] ?? 0);
        $task->lastStatus      = $row['last_status'] !== null ? (string)$row['last_status'] : null;
        $task->lastError       = $row['last_error'] !== null ? (string)$row['last_error'] : null;
        $task->leaseOwner      = $row['lease_owner'] !== null ? (string)$row['lease_owner'] : null;

        $task->nextRun      = self::fromUtcString($row['next_run'] ?? null);
        $task->lastStarted  = self::fromUtcString($row['last_started'] ?? null);
        $task->lastFinished = self::fromUtcString($row['last_finished'] ?? null);
        $task->leaseExpires = self::fromUtcString($row['lease_expires'] ?? null);
        $task->created      = self::fromUtcString($row['created'] ?? null);

        $payload = $row['payload'] ?? null;
        $decoded = is_string($payload) && $payload !== '' ? json_decode($payload, true) : null;
        $task->payload = is_array($decoded) ? $decoded : null;

        return $task;
    }

    // -----------------------------------------------------------------
    // Getters
    // -----------------------------------------------------------------

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function handler(): string
    {
        return $this->handler;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function intervalSeconds(): ?int
    {
        return $this->intervalSeconds;
    }

    public function dailyTime(): ?string
    {
        return $this->dailyTime;
    }

    public function timezone(): ?string
    {
        return $this->timezone;
    }

    /** @return array<string, mixed>|null Decoded payload (null when unset). */
    public function payload(): ?array
    {
        return $this->payload;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function nextRun(): ?\DateTimeImmutable
    {
        return $this->nextRun;
    }

    public function lastStarted(): ?\DateTimeImmutable
    {
        return $this->lastStarted;
    }

    public function lastFinished(): ?\DateTimeImmutable
    {
        return $this->lastFinished;
    }

    public function lastStatus(): ?string
    {
        return $this->lastStatus;
    }

    public function lastError(): ?string
    {
        return $this->lastError;
    }

    public function failures(): int
    {
        return $this->failures;
    }

    public function leaseOwner(): ?string
    {
        return $this->leaseOwner;
    }

    public function leaseExpires(): ?\DateTimeImmutable
    {
        return $this->leaseExpires;
    }

    public function created(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    // -----------------------------------------------------------------
    // Fluent setters
    // -----------------------------------------------------------------

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setHandler(string $handler): self
    {
        $this->handler = $handler;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setIntervalSeconds(?int $intervalSeconds): self
    {
        $this->intervalSeconds = $intervalSeconds;
        return $this;
    }

    public function setDailyTime(?string $dailyTime): self
    {
        $this->dailyTime = $dailyTime;
        return $this;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function setPayload(?array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function setNextRun(?\DateTimeImmutable $nextRun): self
    {
        $this->nextRun = $nextRun;
        return $this;
    }

    public function setLastStarted(?\DateTimeImmutable $lastStarted): self
    {
        $this->lastStarted = $lastStarted;
        return $this;
    }

    public function setLastFinished(?\DateTimeImmutable $lastFinished): self
    {
        $this->lastFinished = $lastFinished;
        return $this;
    }

    public function setLastStatus(?string $lastStatus): self
    {
        $this->lastStatus = $lastStatus;
        return $this;
    }

    public function setLastError(?string $lastError): self
    {
        $this->lastError = $lastError;
        return $this;
    }

    public function setFailures(int $failures): self
    {
        $this->failures = $failures;
        return $this;
    }

    public function setLeaseOwner(?string $leaseOwner): self
    {
        $this->leaseOwner = $leaseOwner;
        return $this;
    }

    public function setLeaseExpires(?\DateTimeImmutable $leaseExpires): self
    {
        $this->leaseExpires = $leaseExpires;
        return $this;
    }

    public function setCreated(?\DateTimeImmutable $created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Parse a UTC 'Y-m-d H:i:s' string from the database.
     */
    private static function fromUtcString(?string $value): ?\DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (new \DateTimeImmutable($value))->setTimezone(new \DateTimeZone('UTC'));
    }
}
