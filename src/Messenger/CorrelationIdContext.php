<?php

declare(strict_types=1);

namespace App\Messenger;

/**
 * Holds the correlationId of the message currently being handled by a worker.
 *
 * Framework-coupled, so it is copied per service rather than shared via the
 * kernel (the shared kernel explicitly excludes framework-coupled code).
 */
final class CorrelationIdContext
{
    private ?string $current = null;

    public function current(): ?string
    {
        return $this->current;
    }

    public function set(?string $correlationId): void
    {
        $this->current = $correlationId;
    }
}
