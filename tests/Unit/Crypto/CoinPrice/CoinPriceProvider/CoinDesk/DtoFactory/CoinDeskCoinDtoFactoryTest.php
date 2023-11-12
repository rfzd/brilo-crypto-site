<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory\CoinDeskCoinDtoFactory;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDtoCollection;
use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class CoinDeskCoinDtoFactoryTest extends TestCase
{
    /**
     * @dataProvider coinDeskCoinDtoFactoryDataProvider
     *
     * @param array{
     *          bpi: array{
     *             EUR: array{code: string, description: string, rate: string, rate_float: float, symbol: string},
     *             GBP: array{code: string, description: string, rate: string, rate_float: float, symbol: string},
     *             USD: array{code: string, description: string, rate: string, rate_float: float, symbol: string},
     *          },
     *          chartName: string,
     *          disclaimer: string,
     *          time: array{updated: string, updatedISO: string, updateduk: string},
     *     } $validCoin
     */
    public function testCoinDeskCoinDtoFactory(
        array $validCoin,
        CoinDto $expectedCoin,
    ): void {
        $actualCoin = CoinDeskCoinDtoFactory::create(data: $validCoin);

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

    protected function coinDeskCoinDtoFactoryDataProvider(): Generator
    {
        yield 'create coin dto' => [
            'validCoin' =>  [
                'bpi' => [
                    'EUR' => [
                        'code' => 'EUR',
                        'description' => 'Euro',
                        'rate' => '35,655.4983',
                        'rate_float' => 35655.4983,
                        'symbol' => '&euro;',
                    ],
                    'GBP' => [
                        'code' => 'GBP',
                        'description' => 'British Pound Sterling',
                        'rate' => '30,584.1724',
                        'rate_float' => 30584.1724,
                        'symbol' => '&pound;',
                    ],
                    'USD' => [
                        'code' => 'USD',
                        'description' => 'United States Dollar',
                        'rate' => '36,601.8013',
                        'rate_float' => 36601.8013,
                        'symbol' => '&#36;',
                    ],
                ],
                'chartName' => 'Bitcoin',
                'disclaimer' => 'This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org',
                'time' => [
                    'updated' => 'Nov 10, 2023 19:02:00 UTC',
                    'updatedISO' => '2023-11-10T19:02:00+00:00',
                    'updateduk' => 'Nov 10, 2023 at 19:02 GMT',
                ],
            ],
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
