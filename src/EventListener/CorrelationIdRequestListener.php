<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

/**
 * Reads correlationId from the X-Correlation-Id request header, or mints one
 * if absent, and stores it as a request attribute so CorrelationIdProcessor
 * can attach it to every log line for the request. Echoes it back on the
 * response so a caller can tie a call to its logs.
 */
#[AsEventListener(event: KernelEvents::REQUEST, method: 'onKernelRequest', priority: 1024)]
#[AsEventListener(event: KernelEvents::RESPONSE, method: 'onKernelResponse')]
final class CorrelationIdRequestListener
{
    private const string HEADER = 'X-Correlation-Id';

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $correlationId = $request->headers->get(self::HEADER) ?? Uuid::v4()->toRfc4122();
        $request->attributes->set('correlationId', $correlationId);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $correlationId = $event->getRequest()->attributes->get('correlationId');
        if ($correlationId !== null) {
            $event->getResponse()->headers->set(self::HEADER, $correlationId);
        }
    }
}
