<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\CoinPrice\Dto;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<CoinPriceDto>
 */
final class CoinPriceDtoCollection extends AbstractCollection
{
    public function getType(): string
    {
        return CoinPriceDto::class;
    }
}
