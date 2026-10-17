<?php

declare(strict_types=1);

namespace App\Tests\EventListener;

use App\EventListener\CorrelationIdRequestListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class CorrelationIdRequestListenerTest extends TestCase
{
    private const string UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    public function testKeepsTheHeaderValueAsTheCorrelationId(): void
    {
        $request = $this->handleRequest('11111111-2222-4333-8444-555555555555');

        self::assertSame('11111111-2222-4333-8444-555555555555', $request->attributes->get('correlationId'));
    }

    public function testMintsAUuidWhenTheHeaderIsAbsent(): void
    {
        $correlationId = $this->handleRequest(null)->attributes->get('correlationId');

        self::assertIsString($correlationId);
        self::assertMatchesRegularExpression(self::UUID_PATTERN, $correlationId);
    }

    public function testIgnoresSubRequests(): void
    {
        $request = new Request();

        new CorrelationIdRequestListener()->onKernelRequest(
            new RequestEvent(self::createStub(HttpKernelInterface::class), $request, HttpKernelInterface::SUB_REQUEST),
        );

        self::assertFalse($request->attributes->has('correlationId'));
    }

    public function testEchoesTheCorrelationIdOnTheResponse(): void
    {
        $request = $this->handleRequest('11111111-2222-4333-8444-555555555555');
        $response = new Response();

        new CorrelationIdRequestListener()->onKernelResponse(
            new ResponseEvent(self::createStub(HttpKernelInterface::class), $request, HttpKernelInterface::MAIN_REQUEST, $response),
        );

        self::assertSame('11111111-2222-4333-8444-555555555555', $response->headers->get('X-Correlation-Id'));
    }

    private function handleRequest(?string $header): Request
    {
        $request = new Request();
        if (null !== $header) {
            $request->headers->set('X-Correlation-Id', $header);
        }

        new CorrelationIdRequestListener()->onKernelRequest(
            new RequestEvent(self::createStub(HttpKernelInterface::class), $request, HttpKernelInterface::MAIN_REQUEST),
        );

        return $request;
    }
}
