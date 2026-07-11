<?php

declare(strict_types=1);

namespace App\Monolog;

use App\Messenger\CorrelationIdContext;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Attaches the current correlationId — from an in-flight message or an HTTP
 * request — to every log record's `extra`, so a saga can be traced across
 * services with a single grep (PLAN.md §9.9, Phase 5 chaos exercises).
 */
final readonly class CorrelationIdProcessor implements ProcessorInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private CorrelationIdContext $messageContext,
    ) {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $correlationId = $this->messageContext->current()
            ?? $this->requestStack->getCurrentRequest()?->attributes->get('correlationId');

        if ($correlationId !== null) {
            $record->extra['correlationId'] = $correlationId;
        }

        return $record;
    }
}
