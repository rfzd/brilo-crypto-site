<?php

declare(strict_types=1);

namespace BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk;

use function sprintf;

final class CoinDeskRouter
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiVersion,
        private readonly string $btcCurrentPriceRoute,
    ) {
    }

    public function createGetCurrentBtcPricesUrl(): string
    {
        return sprintf('%s%s%s', $this->baseUrl, $this->apiVersion, $this->btcCurrentPriceRoute);
    }
}
