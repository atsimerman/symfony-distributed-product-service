<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Carries the saga's correlationId alongside a message through the bus.
 *
 * The custom envelope serializer (Phase 1, ADR 0002) attaches this stamp from
 * the envelope's `correlationId` field when decoding an incoming message.
 */
final readonly class CorrelationIdStamp implements StampInterface
{
    public function __construct(public string $correlationId)
    {
    }
}
