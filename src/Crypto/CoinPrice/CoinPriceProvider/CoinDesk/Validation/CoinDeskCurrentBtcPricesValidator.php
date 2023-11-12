<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\CoinPrice\CoinPriceProvider\CoinDesk\Validation;

use BriloCryptoSite\Helpers\JsonValidator;
use JsonException;

final class CoinDeskCurrentBtcPricesValidator
{
    /**
     * @throws JsonException
     */
    public static function check(
        string $json,
    ): void {
        JsonValidator::check(json: $json, schema: self::getSchema());
    }

    /**
     * @return non-empty-array<string, mixed> $schema
     */
    private static function getSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'bpi' => [
                    'type' => 'object',
                    'properties' => [
                        'EUR' => [
                            self::getPriceSchema(),
                        ],
                        'GBP' => [
                            self::getPriceSchema(),
                        ],
                        'USD' => [
                            self::getPriceSchema(),
                        ],
                    ],
                    'required' => [
                        'EUR',
                        'GBP',
                        'USD',
                    ],
                ],
                'chartName' => ['type' => 'string'],
                'disclaimer' => ['type' => 'string'],
                'time' => [
                    self::getTimeSchema(),
                ],
            ],
            'required' => [
                'bpi',
                'chartName',
                'disclaimer',
                'time',
            ],
        ];
    }

    /**
     * @return non-empty-array<string, mixed> $schema
     */
    private static function getPriceSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'code' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'rate' => ['type' => 'string'],
                'rate_float' => ['type' => 'float'],
                'symbol' => ['type' => 'string'],
            ],
            'required' => [
                'code',
                'description',
                'rate',
                'rate_float',
                'symbol',
            ],
        ];
    }

    /**
     * @return non-empty-array<string, mixed> $schema
     */
    private static function getTimeSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'updated' => [
                    'type' => 'string',
                    'format' => 'date',
                ],
                'updatedISO' => [
                    'type' => 'string',
                    'format' => 'date',
                ],
                'updateduk' => [
                    'type' => 'string',
                    'format' => 'date',
                ],
            ],
            'required' => [
                'updated',
                'updatedISO',
                'updateduk',
            ],
        ];
    }
}
