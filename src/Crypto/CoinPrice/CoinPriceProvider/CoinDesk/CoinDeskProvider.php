<?php

declare(strict_types=1);

namespace BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk;

use BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\DtoFactory\CoinDeskCoinDtoFactory;
use BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\Validation\CoinDeskCurrentBtcPricesValidator;
use BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinPriceClient;
use BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinPriceProviderInterface;
use BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\Validation\Exception\CoinPriceCallException;
use BrioCryptoSite\Crypto\CoinPrice\Dto\CoinDto;
use JsonException;

use function json_decode;

final class CoinDeskProvider implements CoinPriceProviderInterface
{
    public function __construct(
        private readonly CoinDeskRequestFactory $coinDeskRequestProvider,
        private readonly CoinPriceClient $coinPriceClient,
    ) {
    }

    /**
     * @throws CoinPriceCallException
     * @throws JsonException
     */
    public function getCurrentBtcPrices(): CoinDto
    {
        $request = $this->coinDeskRequestProvider->createGetCurrentBtcPricesRequest();
        $response = $this->coinPriceClient->sendRequest(request: $request);

        $jsonContent = $response->getBody()->getContents();
        CoinDeskCurrentBtcPricesValidator::check(json: $jsonContent);

        /**
         * @var array{
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
        $data = json_decode(json: $jsonContent, associative: true, depth: 10, flags: JSON_THROW_ON_ERROR);
        assert(assertion: is_array($data) === true);

        return CoinDeskCoinDtoFactory::create(data: $data);
    }
}
