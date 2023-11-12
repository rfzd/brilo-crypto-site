<?php

declare(strict_types=1);

namespace BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider;

use BrioCryptoSite\Crypto\CoinPrice\CoinPriceProvider\Validation\Exception\CoinPriceCallException;
use BrioCryptoSite\Helpers\ResponseHelper;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class CoinPriceClient
{
    public function __construct(
        private readonly ClientInterface $client,
    ) {
    }

    /**
     * @throws CoinPriceCallException
     */
    public function sendRequest(
        GuzzleRequest $request,
    ): ResponseInterface {
        try {
            $response = $this->client->sendRequest(request: $request);
        } catch (BadResponseException) {
            throw new CoinPriceCallException(message: 'Client coin price request ended with error.');
        }

        if (ResponseHelper::isOkStatusCode(response: $response) === false) {
            throw new CoinPriceCallException(message: 'Client coin price responded with non 2xx response.');
        }

        return $response;
    }
}
