<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\CoinPrice\CoinPriceProvider\CoinDesk;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\CoinDeskProvider;
use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\CoinDeskRequestFactory;
use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\CoinDeskRouter;
use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinPriceClient;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDtoCollection;
use DateTimeImmutable;
use Generator;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpFoundation\Response;

final class CoinDeskProviderTest extends TestCase
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
     * @dataProvider getCurrentBtcPricesDataProvider
     */
    public function testGetCurrentBtcPrices(
        CoinDto $expectedCoin,
    ): void {
        $guzzleResponse = new GuzzleResponse(
            status: Response::HTTP_OK,
            body: '
            {
                "bpi": {
                    "EUR": {
                        "code": "EUR",
                        "description": "Euro",
                        "rate": "35,655.4983",
                        "rate_float": 35655.4983,
                        "symbol": "&euro;"
                    },
                    "GBP": {
                        "code": "GBP",
                        "description": "British Pound Sterling",
                        "rate": "30,584.1724",
                        "rate_float": 30584.1724,
                        "symbol": "&pound;"
                    },
                    "USD": {
                        "code": "USD",
                        "description": "United States Dollar",
                        "rate": "36,601.8013",
                        "rate_float": 36601.8013,
                        "symbol": "&#36;"
                    }
                },
                "chartName": "Bitcoin",
                "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
                "time": {
                    "updated": "Nov 10, 2023 19:02:00 UTC",
                    "updatedISO": "2023-11-10T19:02:00+00:00",
                    "updateduk": "Nov 10, 2023 at 19:02 GMT"
                }
            }
            ',
        );

        $clientMock = $this->createMock(originalClassName: ClientInterface::class);
        $clientMock->expects($this->once())
            ->method(constraint: 'sendRequest')
            ->with($this->coinDeskRequestFactory->createGetCurrentBtcPricesRequest())
            ->willReturn(value: $guzzleResponse);

        $coinPriceClient = new CoinPriceClient(client: $clientMock);
        $coinDeskProvider = new CoinDeskProvider(
            coinDeskRequestFactory: $this->coinDeskRequestFactory,
            coinPriceClient: $coinPriceClient,
        );

        $actualCoin = $coinDeskProvider->getCurrentBtcPrices();

        Assert::assertEquals(
            expected: $expectedCoin->getName(),
            actual: $actualCoin->getName(),
        );
        Assert::assertSame(
            expected: $expectedCoin->getTime()->format(format: 'c'),
            actual: $actualCoin->getTime()->format(format: 'c'),
        );

        foreach ($actualCoin->getCoinPrices() as $key => $actualCoinPrice) {
            $expectedCoinPrice = $expectedCoin->getCoinPrices()->offsetGet(offset: $key);

            Assert::assertEquals(
                expected: $expectedCoinPrice->getCode(),
                actual: $actualCoinPrice->getCode(),
            );
            Assert::assertEquals(
                expected: $expectedCoinPrice->getDescription(),
                actual: $actualCoinPrice->getDescription(),
            );
            Assert::assertEquals(
                expected: $expectedCoinPrice->getSymbol(),
                actual: $actualCoinPrice->getSymbol(),
            );
            Assert::assertEquals(
                expected: $expectedCoinPrice->getRate(),
                actual: $actualCoinPrice->getRate(),
            );
        }
    }

    protected function getCurrentBtcPricesDataProvider(): Generator
    {
        yield 'get current btc prices' => [
            'expectedCoin' => new CoinDto(
                name: 'Bitcoin',
                time: new DateTimeImmutable(datetime: '2023-11-10T19:02:00+00:00'),
                coinPrices: new CoinPriceDtoCollection([
                    new CoinPriceDto(
                        code: 'EUR',
                        description: 'Euro',
                        symbol: '&euro;',
                        rate: 35655.4983,
                    ),
                    new CoinPriceDto(
                        code: 'GBP',
                        description: 'British Pound Sterling',
                        symbol: '&pound;',
                        rate: 30584.1724,
                    ),
                    new CoinPriceDto(
                        code: 'USD',
                        description: 'United States Dollar',
                        symbol: '&#36;',
                        rate: 36601.8013,
                    ),
                ]),
            ),
        ];
    }
}
