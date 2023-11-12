<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\Endpoint\GetCurrentBtcPrice;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinPriceProviderInterface;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDto;
use BriloCryptoSite\Crypto\CoinPrice\Dto\CoinPriceDtoCollection;
use BriloCryptoSite\Crypto\Endpoint\GetCurrentBtcPrice\Model\GetCurrentBtcPriceResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

use function array_map;
use function is_array;
use function sprintf;

final class GetCurrentBtcPricesControllerFacade
{
    public function __construct(
        private readonly CoinPriceProviderInterface $coinPriceProvider,
    ) {
    }

    public function handleGetCurrentBtcPricesAction(
        Request $request,
    ): GetCurrentBtcPriceResponse {
        $coinDto = $this->coinPriceProvider->getCurrentBtcPrices();

        /** @var array<int, string>|float|int|bool|string|null $requestedCurrencies */
        $requestedCurrencies = $request->query->get(key: 'currencies', default: null);
        if ($requestedCurrencies === null) {
            return new GetCurrentBtcPriceResponse(coinDto: $coinDto);
        }

        assert(
            assertion: is_array(value: $requestedCurrencies) === true,
            description: new InvalidArgumentException(message: 'Requested currencies must be of type array.'),
        );

        $filteredCoinPrices = $this->filterCoinPricesByCurrencies(
            coinPrices: $coinDto->getCoinPrices(),
            currencies: $requestedCurrencies,
        );

        $filteredCoinDto = new CoinDto(
            name: $coinDto->getName(),
            time: $coinDto->getTime(),
            coinPrices: $filteredCoinPrices,
        );

        return new GetCurrentBtcPriceResponse(coinDto: $filteredCoinDto);
    }

    /**
     * @param array<int, string> $currencies
     */
    private function filterCoinPricesByCurrencies(
        CoinPriceDtoCollection $coinPrices,
        array $currencies,
    ): CoinPriceDtoCollection {
        $filteredCoinPrices = array_map(
            callback: static function (string $currencyCode) use ($coinPrices): CoinPriceDto {
                $foundCoinPrices = $coinPrices->filter(
                    callback: static function (CoinPriceDto $coinPriceDto) use ($currencyCode): bool {
                        return $currencyCode === $coinPriceDto->getCode();
                    },
                );

                if ($foundCoinPrices->count() === 0) {
                    throw new InvalidArgumentException(
                        message: sprintf('Coin price with requested currency "%s" was not found.', $currencyCode),
                    );
                }

                if ($foundCoinPrices->count() > 1) {
                    throw new InvalidArgumentException(
                        message: sprintf(
                            'Multiple coin prices with requested currency "%s" was found.',
                            $currencyCode,
                        ),
                    );
                }

                return $foundCoinPrices->first();
            },
            array: $currencies,
        );

        return new CoinPriceDtoCollection(data: $filteredCoinPrices);
    }
}
