<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\CoinPrice\CoinPriceProvider\CoinDesk;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\CoinDeskRouter;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class CoinDeskRouterTest extends TestCase
{
    private CoinDeskRouter $coinDeskRouter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coinDeskRouter = new CoinDeskRouter(
            baseUrl: 'https://api.coindesk.test/',
            apiVersion: 'v1',
            btcCurrentPriceRoute: '/bpi/currentprice.json',
        );
    }

    public function testCreateGetCurrentBtcPricesUrl(): void
    {
        Assert::assertEquals(
            expected: 'https://api.coindesk.test/v1/bpi/currentprice.json',
            actual: $this->coinDeskRouter->createGetCurrentBtcPricesUrl(),
        );
    }
}
