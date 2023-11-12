<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\CoinPrice\Dto;

use DateTimeImmutable;

final class CoinDto
{
    public function __construct(
        private readonly string $name,
        private readonly DateTimeImmutable $time,
        private readonly CoinPriceDtoCollection $coinPrices,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }

    public function getCoinPrices(): CoinPriceDtoCollection
    {
        return $this->coinPrices;
    }
}
