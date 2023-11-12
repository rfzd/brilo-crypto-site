<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\Endpoint\GetCurrentBtcPrice\Model;

use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;
use DateTimeImmutable;
use JsonSerializable;

final class GetCurrentBtcPriceResponse implements JsonSerializable
{
    public function __construct(
        private readonly CoinDto $coinDto,
    ) {
    }

    /**
     * @return array{
     *     name: string,
     *     time: DateTimeImmutable,
     *     prices: array<array{code: string, description: string, symbol: string, rate: float}>
     *   }
     */
    public function jsonSerialize(): array
    {
        $coinPriceTransformCallback = static function (CoinPriceDto $coinPriceDto): array {
            return [
                'code' => $coinPriceDto->getCode(),
                'description' => $coinPriceDto->getDescription(),
                'symbol' => $coinPriceDto->getSymbol(),
                'rate' => $coinPriceDto->getRate(),
            ];
        };
        $prices = array_map(
            callback: $coinPriceTransformCallback,
            array: $this->coinDto->getCoinPrices()->toArray(),
        );

        return [
            'name' => $this->coinDto->getName(),
            'time' => $this->coinDto->getTime(),
            'prices' => $prices,
        ];
    }
}
