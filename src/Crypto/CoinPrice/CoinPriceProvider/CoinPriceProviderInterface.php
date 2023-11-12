<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider;

use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinDto;

interface CoinPriceProviderInterface
{
    public function getCurrentBtcPrices(): CoinDto;
}
