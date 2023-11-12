<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory;

use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;

final class CoinDeskCoinPriceDtoFactory
{
    /**
     * @param array{code: string, description: string, rate: string, rate_float: float, symbol: string} $data
     */
    public static function create(
        array $data,
    ): CoinPriceDto {
        return new CoinPriceDto(
            code: $data['code'],
            description: $data['description'],
            symbol: $data['symbol'],
            rate: $data['rate_float'],
        );
    }
}
