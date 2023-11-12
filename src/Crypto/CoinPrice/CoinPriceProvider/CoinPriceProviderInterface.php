<?php

declare(strict_types=1);

namespace BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider;

use BrioCryptoSite\Crypto\CoinPrice\Dto\CoinDto;

interface CoinPriceProviderInterface
{
    public function getCurrentBtcPrices(): CoinDto;
}
