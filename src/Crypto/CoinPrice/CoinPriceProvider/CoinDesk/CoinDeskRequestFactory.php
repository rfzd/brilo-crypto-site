<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Symfony\Component\HttpFoundation\Request;

final class CoinDeskRequestFactory
{
    public function __construct(
        private readonly CoinDeskRouter $coinDeskRouter,
    ) {
    }

    public function createGetCurrentBtcPricesRequest(): GuzzleRequest
    {
        return new GuzzleRequest(
            method: Request::METHOD_GET,
            uri: $this->coinDeskRouter->createGetCurrentBtcPricesUrl(),
            headers: [
                'Accept' => 'application/json',
            ],
        );
    }
}
