<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Helpers;

use BriloCryptoSite\Helpers\ResponseHelper;
use Generator;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class ResponseHelperTest extends TestCase
{
    /**
     * @dataProvider isOkStatusCodeDataProvider
     */
    public static function testIsOkStatusCode(
        int $status,
    ): void {
        $response = new GuzzleResponse(status: $status);
        Assert::assertTrue(condition: ResponseHelper::isOkStatusCode(response: $response));
    }

    protected function isOkStatusCodeDataProvider(): Generator
    {
        yield 'HTTP_OK' => [Response::HTTP_OK];
        yield 'HTTP_ACCEPTED' => [Response::HTTP_ACCEPTED];
        yield 'HTTP_NO_CONTENT' => [Response::HTTP_NO_CONTENT];
        yield 'HTTP_IM_USED' => [Response::HTTP_IM_USED];
        yield '299' => [299];
    }

    /**
     * @dataProvider isNotOkStatusCodeDataProvider
     */
    public static function testIsNotOkStatusCode(
        int $status,
    ): void {
        $response = new GuzzleResponse(status: $status);
        Assert::assertFalse(condition: ResponseHelper::isOkStatusCode(response: $response));
    }

    protected function isNotOkStatusCodeDataProvider(): Generator
    {
        yield 'HTTP_BAD_REQUEST' => [Response::HTTP_BAD_REQUEST];
        yield 'HTTP_INTERNAL_SERVER_ERROR' => [Response::HTTP_INTERNAL_SERVER_ERROR];
        yield '199' => [199];
        yield '300' => [300];
    }
}
