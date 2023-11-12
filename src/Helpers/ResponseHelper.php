<?php

declare(strict_types=1);

namespace BriloCryptoSite\Helpers;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

final class ResponseHelper
{
    public static function isOkStatusCode(
        ResponseInterface $response,
    ): bool {
        if ($response->getStatusCode() < Response::HTTP_OK) {
            return false;
        }

        if ($response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES) {
            return false;
        }

        return true;
    }
}
