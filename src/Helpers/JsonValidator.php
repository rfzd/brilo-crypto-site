<?php

declare(strict_types=1);

namespace BriloCryptoSite\Helpers;

use JsonException;
use JsonSchema\Validator;

use function json_decode;

final class JsonValidator
{
    /**
     * @param non-empty-array<string, mixed> $schema
     * @throws JsonException
     */
    public static function check(
        string $json,
        array $schema,
    ): void {
        $data = json_decode(json: $json, associative: null, depth: 10, flags: JSON_THROW_ON_ERROR);

        $validator = new Validator();
        $validator->check(value: $data, schema: $schema);

        if ($validator->isValid() === false) {
            throw new JsonException('Given JSON is not compatible with given schema.');
        }
    }
}
