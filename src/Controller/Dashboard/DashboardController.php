<?php

declare(strict_types=1);

namespace BriloCryptoSite\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(
    path: '/',
    name: 'root.dashboard',
    methods: [Request::METHOD_GET],
)]
final class DashboardController extends AbstractController
{
    public function __invoke(): Response
    {
        $btcPricesUrl = $this->generateUrl(
            route: 'crypto.coin_price.get_current_btc_prices',
            parameters: [
                'currencies' => ['EUR', 'USD'],
            ],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return $this->render(
            view: 'dashboard/dashboard.html.twig',
            parameters: [
                'btcPricesUrl' => $btcPricesUrl,
            ],
        );
    }
}
