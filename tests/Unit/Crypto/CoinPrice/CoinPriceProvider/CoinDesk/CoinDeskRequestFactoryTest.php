<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\CoinPrice\CoinPriceProvider\CoinDesk;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\CoinDeskRequestFactory;
use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\CoinDeskRouter;
use Generator;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class CoinDeskRequestFactoryTest extends TestCase
{
    private CoinDeskRequestFactory $coinDeskRequestFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $coinDeskRouter = new CoinDeskRouter(
            baseUrl: 'https://api.coindesk.test/',
            apiVersion: 'v1',
            btcCurrentPriceRoute: '/bpi/currentprice.json',
        );
        $this->coinDeskRequestFactory = new CoinDeskRequestFactory(coinDeskRouter: $coinDeskRouter);
    }

    /**
     * @dataProvider createGetCurrentBtcPricesRequestDataProvider
     */
    public function testCreateGetCurrentBtcPricesRequest(
        GuzzleRequest $expectedRequest,
    ): void {
        $actualRequest = $this->coinDeskRequestFactory->createGetCurrentBtcPricesRequest();

        Assert::assertSame(
            expected: $expectedRequest->getMethod(),
            actual: $actualRequest->getMethod(),
        );
        Assert::assertEquals(
            expected: $expectedRequest->getUri(),
            actual: $actualRequest->getUri(),
        );
        Assert::assertSame(
            expected: $expectedRequest->getHeaders(),
            actual: $actualRequest->getHeaders(),
        );
        Assert::assertSame(
            expected: $expectedRequest->getBody()->__toString(),
            actual: $actualRequest->getBody()->__toString(),
        );
    }

    protected function createGetCurrentBtcPricesRequestDataProvider(): Generator
    {
        yield 'expected GetCurrentBtcPricesRequest' => [
            'expectedRequest' => new GuzzleRequest(
                method: Request::METHOD_GET,
                uri: 'https://api.coindesk.test/v1/bpi/currentprice.json',
                headers: [
                    'Accept' => 'application/json',
                ],
            ),
        ];
    }
}
