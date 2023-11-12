<?php

declare(strict_types=1);

namespace BrioCryptoSite\Crypto\CoinPrice\Dto;

final class CoinPriceDto
{
    public function __construct(
        private readonly string $code,
        private readonly string $description,
        private readonly string $symbol,
        private readonly float $rate,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
