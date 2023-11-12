<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\Endpoint\GetCurrentBtcPrice;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinPriceProviderInterface;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDtoCollection;
use BriloCryptoSite\Crypto\Endpoint\GetCurrentBtcPrice\GetCurrentBtcPricesControllerFacade;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetCurrentBtcPricesControllerFacadeTest extends TestCase
{
    public function testHandleGetCurrentBtcPricesAction(): void
    {
        $expectedCoinPrice = new CoinDto(
            name: 'Bitcoin',
            time: new DateTimeImmutable(datetime: '2023-11-12T18:34:00+00:00'),
            coinPrices: new CoinPriceDtoCollection([
                new CoinPriceDto(
                    code: 'EUR',
                    description: 'Euro',
                    symbol: '&euro;',
                    rate: 36242.5341,
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
        );

        $coinPriceProviderMock = $this->createMock(originalClassName: CoinPriceProviderInterface::class);
        $coinPriceProviderMock->expects($this->once())
            ->method(constraint: 'getCurrentBtcPrices')
            ->willReturn(value: $expectedCoinPrice);

        $getCurrentBtcPricesControllerFacade = new GetCurrentBtcPricesControllerFacade(
            coinPriceProvider: $coinPriceProviderMock,
        );
        $request = Request::create(
            uri: '/api/coin-prices/current/btc',
            method: Request::METHOD_GET,
            parameters: ['currencies' => ['EUR']],
        );

        $actualGetCurrentBtcPriceResponse = $getCurrentBtcPricesControllerFacade->handleGetCurrentBtcPricesAction(
            request: $request,
        );

        $actualJson = $actualGetCurrentBtcPriceResponse->jsonSerialize();

        Assert::assertEquals(
            expected: '{"name":"Bitcoin","time":{"date":"2023-11-12 18:34:00.000000","timezone_type":1,"timezone":"+00:00"},"prices":[{"code":"EUR","description":"Euro","symbol":"&euro;","rate":36242.5341}]}',
            actual: json_encode(value: $actualJson),
        );
    }
}
