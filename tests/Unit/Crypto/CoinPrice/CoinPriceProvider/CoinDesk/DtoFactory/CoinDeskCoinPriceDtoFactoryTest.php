<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory\CoinDeskCoinPriceDtoFactory;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;
use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class CoinDeskCoinPriceDtoFactoryTest extends TestCase
{
    /**
     * @dataProvider coinDeskCoinPriceDtoFactoryDataProvider
     *
     * @param array{code: string, description: string, rate: string, rate_float: float, symbol: string} $validCoinPrice
     */
    public function testCoinDeskCoinPriceDtoFactory(
        array $validCoinPrice,
        CoinPriceDto $expectedCoinPrice,
    ): void {
        $actualCoinPrice = CoinDeskCoinPriceDtoFactory::create(data: $validCoinPrice);

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

    protected function coinDeskCoinPriceDtoFactoryDataProvider(): Generator
    {
        yield 'create coin price dto' => [
            'validCoinPrice' => [
                'code' => 'CZK',
                'description' => 'Česká koruna',
                'rate' => '100,5',
                'rate_float' => 100.5,
                'symbol' => 'Kč',
            ],
            'expectedCoinPrice' => new CoinPriceDto(
                code: 'CZK',
                description: 'Česká koruna',
                symbol: 'Kč',
                rate: 100.5,
            ),
        ];
    }
}
