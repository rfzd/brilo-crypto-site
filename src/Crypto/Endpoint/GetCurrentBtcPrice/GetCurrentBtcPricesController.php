<?php

declare(strict_types=1);

namespace BriloCryptoSite\Crypto\Endpoint\GetCurrentBtcPrice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/coin-prices/current/btc',
    name: 'crypto.coin_price.get_current_btc_prices',
    methods: [Request::METHOD_GET],
)]
final class GetCurrentBtcPricesController extends AbstractController
{
    public function __construct(
        private readonly GetCurrentBtcPricesControllerFacade $getCurrentBtcPriceControllerFacade,
    ) {
    }

    public function __invoke(
        Request $request,
    ): JsonResponse {
        $responseSerializable = $this->getCurrentBtcPriceControllerFacade->handleGetCurrentBtcPricesAction(
            request: $request,
        );

        return new JsonResponse(
            data: $responseSerializable,
            status: Response::HTTP_OK,
            json: false,
        );
    }
}
