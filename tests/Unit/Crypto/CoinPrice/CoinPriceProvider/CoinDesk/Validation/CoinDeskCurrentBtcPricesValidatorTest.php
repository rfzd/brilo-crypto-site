<?php

declare(strict_types=1);

namespace BriloCryptoSite\Tests\Unit\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\Validation;

use BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\Validation\CoinDeskCurrentBtcPricesValidator;
use Generator;
use JsonException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class CoinDeskCurrentBtcPricesValidatorTest extends TestCase
{
    /**
     * @dataProvider currentBtcPricesValidJsonDataProvider
     */
    public function testCurrentBtcPricesValidJson(
        string $validJson,
    ): void {
        CoinDeskCurrentBtcPricesValidator::check(json: $validJson);
        Assert::assertTrue(condition: true);
    }

    protected function currentBtcPricesValidJsonDataProvider(): Generator
    {
        yield 'valid json with current btc prices' => [
            'validJson' => '
                {
                    "bpi": {
                        "EUR": {
                            "code": "EUR",
                            "description": "Euro",
                            "rate": "35,655.4983",
                            "rate_float": 35655.4983,
                            "symbol": "&euro;"
                        },
                        "GBP": {
                            "code": "GBP",
                            "description": "British Pound Sterling",
                            "rate": "30,584.1724",
                            "rate_float": 30584.1724,
                            "symbol": "&pound;"
                        },
                        "USD": {
                            "code": "USD",
                            "description": "United States Dollar",
                            "rate": "36,601.8013",
                            "rate_float": 36601.8013,
                            "symbol": "&#36;"
                        }
                    },
                    "chartName": "Bitcoin",
                    "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
                    "time": {
                        "updated": "Nov 10, 2023 19:02:00 UTC",
                        "updatedISO": "2023-11-10T19:02:00+00:00",
                        "updateduk": "Nov 10, 2023 at 19:02 GMT"
                    }
                }
            ',
        ];
    }

    /**
     * @dataProvider currentBtcPricesInvalidJsonDataProvider
     */
    public function testCurrentBtcPricesInvalidJson(
        string $invalidJson,
    ): void {
        $this->expectException(JsonException::class);

        CoinDeskCurrentBtcPricesValidator::check(json: $invalidJson);
    }

    protected function currentBtcPricesInvalidJsonDataProvider(): Generator
    {
        yield 'empty string' => [
            'invalidJson' => '',
        ];
        yield 'null' => [
            'invalidJson' => 'null',
        ];
        yield 'list of empty strings' => [
            'invalidJson' => '["", ""]',
        ];
        yield 'empty list' => [
            'invalidJson' => '[]',
        ];
        yield 'missing bpi' => [
            'invalidJson' => '
                {
                    "missing_bpi": {
                        "EUR": {
                            "code": "EUR",
                            "description": "Euro",
                            "rate": "35,655.4983",
                            "rate_float": 35655.4983,
                            "symbol": "&euro;"
                        },
                        "GBP": {
                            "code": "GBP",
                            "description": "British Pound Sterling",
                            "rate": "30,584.1724",
                            "rate_float": 30584.1724,
                            "symbol": "&pound;"
                        },
                        "USD": {
                            "code": "USD",
                            "description": "United States Dollar",
                            "rate": "36,601.8013",
                            "rate_float": 36601.8013,
                            "symbol": "&#36;"
                        }
                    },
                    "chartName": "Bitcoin",
                    "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
                    "time": {
                        "updated": "Nov 10, 2023 19:02:00 UTC",
                        "updatedISO": "2023-11-10T19:02:00+00:00",
                        "updateduk": "Nov 10, 2023 at 19:02 GMT"
                    }
                }
            ',
        ];
        yield 'missing EUR' => [
            'invalidJson' => '
                {
                    "bpi": {
                        "GBP": {
                            "code": "GBP",
                            "description": "British Pound Sterling",
                            "rate": "30,584.1724",
                            "rate_float": 30584.1724,
                            "symbol": "&pound;"
                        },
                        "USD": {
                            "code": "USD",
                            "description": "United States Dollar",
                            "rate": "36,601.8013",
                            "rate_float": 36601.8013,
                            "symbol": "&#36;"
                        }
                    },
                    "chartName": "Bitcoin",
                    "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
                    "time": {
                        "updated": "Nov 10, 2023 19:02:00 UTC",
                        "updatedISO": "2023-11-10T19:02:00+00:00",
                        "updateduk": "Nov 10, 2023 at 19:02 GMT"
                    }
                }
            ',
        ];
        yield 'missing time' => [
            'invalidJson' => '
                {
                    "bpi": {
                        "EUR": {
                            "code": "EUR",
                            "description": "Euro",
                            "rate": "35,655.4983",
                            "rate_float": 35655.4983,
                            "symbol": "&euro;"
                        },
                        "GBP": {
                            "code": "GBP",
                            "description": "British Pound Sterling",
                            "rate": "30,584.1724",
                            "rate_float": 30584.1724,
                            "symbol": "&pound;"
                        },
                        "USD": {
                            "code": "USD",
                            "description": "United States Dollar",
                            "rate": "36,601.8013",
                            "rate_float": 36601.8013,
                            "symbol": "&#36;"
                        }
                    },
                    "chartName": "Bitcoin",
                    "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
                }
            ',
        ];
        yield 'rate_float is not a number' => [
            'invalidJson' => '
                {
                    "xyz": {
                        "EUR": {
                            "code": "EUR",
                            "description": "Euro",
                            "rate": "35,655.4983",
                            "rate_float": "not a number",
                            "symbol": "&euro;"
                        },
                        "GBP": {
                            "code": "GBP",
                            "description": "British Pound Sterling",
                            "rate": "30,584.1724",
                            "rate_float": 30584.1724,
                            "symbol": "&pound;"
                        },
                        "USD": {
                            "code": "USD",
                            "description": "United States Dollar",
                            "rate": "36,601.8013",
                            "rate_float": 36601.8013,
                            "symbol": "&#36;"
                        }
                    },
                    "chartName": "Bitcoin",
                    "disclaimer": "This data was produced from the CoinDesk Bitcoin Price Index (USD). Non-USD currency data converted using hourly conversion rate from openexchangerates.org",
                    "time": {
                        "updated": "Nov 10, 2023 19:02:00 UTC",
                        "updatedISO": "2023-11-10T19:02:00+00:00",
                        "updateduk": "Nov 10, 2023 at 19:02 GMT"
                    }
                }
            ',
        ];
    }
}
