<?php

declare(strict_types=1);

namespace BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory;

use BrioCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use BrioCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDtoCollection;
use DateTimeImmutable;
use DateTimeInterface;
use RuntimeException;

use function sprintf;

final class CoinDeskCoinDtoFactory
{
    /**
     * @param array{
     *          bpi: array{
     *             EUR: array{code: string, description: string, rate: string, rate_float: float, symbol: string},
     *             GBP: array{code: string, description: string, rate: string, rate_float: float, symbol: string},
     *             USD: array{code: string, description: string, rate: string, rate_float: float, symbol: string},
     *          },
     *          chartName: string,
     *          disclaimer: string,
     *          time: array{updated: string, updatedISO: string, updateduk: string},
     *     } $data
     */
    public static function create(
        array $data,
    ): CoinDto {
        $prices = new CoinPriceDtoCollection();
        $prices->add(element: CoinDeskCoinPriceDtoFactory::create(data: $data['bpi']['EUR']));
        $prices->add(element: CoinDeskCoinPriceDtoFactory::create(data: $data['bpi']['GBP']));
        $prices->add(element: CoinDeskCoinPriceDtoFactory::create(data: $data['bpi']['USD']));

        $time = DateTimeImmutable::createFromFormat(
            format: DateTimeInterface::ISO8601_EXPANDED,
            datetime: $data['time']['updatedISO'],
        );
        if ($time === false) {
            throw new RuntimeException(
                message: sprintf(
                    'Given time "%s" cannot be converted to DateTimeImmutable using format "%s".',
                    $data['time']['updatedISO'],
                    DateTimeInterface::ISO8601_EXPANDED,
                ),
            );
        }

        return new CoinDto(
            name: $data['chartName'],
            time: $time,
            coinPrices: $prices,
        );
    }
}
