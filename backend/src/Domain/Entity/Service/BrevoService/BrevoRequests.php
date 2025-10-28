<?php

namespace App\Domain\Entity\Service\BrevoService;

use App\Domain\Entity\HttpRequest;
use App\Domain\Exception\ThirdException;
use App\Helper\BrevoHelper;
use App\Infra\Controller\HttpMethods;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class BrevoRequests
{
    private BrevoHelper $brevoHelper;

    public function __construct(
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
        $this->brevoHelper = $thirdPartyFactory->getBrevoHelper();
    }

    /**
     * Envia um e-mail pelo Brevo
     */
    public function sendEmail(BrevoEmailInput $body): bool
    {
        $url = $this->brevoHelper->buildUrl("/smtp/email");

        $httpRequest = new HttpRequest(
            $url,
            HttpMethods::POST,
            $body->toArray()
        );

        $response = $this->brevoHelper->doRequest($httpRequest, "send_email");

        // Brevo retorna 201 em caso de sucesso
        return $response->getStatusCode() === 201;
    }
}
