<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * Makes the envelope's CorrelationIdStamp, if any, available to
 * CorrelationIdProcessor for the duration of message handling.
 */
final readonly class CorrelationIdMiddleware implements MiddlewareInterface
{
    public function __construct(private CorrelationIdContext $context)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $stamp = $envelope->last(CorrelationIdStamp::class);
        $this->context->set($stamp?->correlationId);

        try {
            return $stack->next()->handle($envelope, $stack);
        } finally {
            $this->context->set(null);
        }
    }
}
