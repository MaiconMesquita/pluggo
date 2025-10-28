<?php

namespace App\Infra\Partner;

use App\Domain\Entity\BrandedCard;
use App\Domain\Entity\BrandedCardUser;
use App\Domain\PartnerContract\BrandedCardPartnerContract;
use App\Domain\Entity\HttpRequest;
use App\Domain\Entity\User;
use App\Helper\CardServiceHelper;
use App\Infra\Controller\HttpMethods;
use DateTime;

class BrandedCardUserPartner implements BrandedCardPartnerContract
{
    public function __construct(
        private CardServiceHelper $cardServiceHelper,
    ) {}

    public function create(BrandedCardUser $cardUser): BrandedCardUser
    {

        if ($_ENV["ENV"] === "dev") {
            $cardUser->setIssuerId('test-holder-id');
            $card = $cardUser->getCards()[0];
            $card->setIssuerId('test-card-id');
            $card->setEmbossingName($card->getEmbossingName() ?? 'TEST NAME');
            return $cardUser;
        }

        $card = $cardUser->getCards()[0];
        $user = $cardUser->getUser();
        $nameParts = explode(' ', $user->getName());
        $surname = end($nameParts);

        $path = '/owner';
        $payload = [
            'email' => $user->getEmail(),
            'document' => $user->getCpf(),
            'phone' => $user->getPhone(),
            'address' => [
                'city' => $user->getCity(),
                'uf' => $user->getState(),
                'street' => $user->getStreet(),
                'number' => $user->getNumber(),
                'zipCode' => $user->getPostalCode(),
                'country' => '76',
                'neighborhood' => $user->getNeighborhood(),
            ],
            'personalInfo' => [
                'fullName' => $user->getName(),
                'surname' => $surname,
                'birthDate' => CardServiceHelper::formatDateForPartner($user->getBirthDate()),
                'gender' => $user->getGender(),
                'maritalStatus' => $user->getMaritalStatus(),
                'nationality' => $user->getNationality(),
                'rg' => $user->getRg(),
                'rgIssuer' => $user->getIssuingAuthority(),
                'rgIssuerState' => $user->getIssuingState(),
                'fatherName' => $user->getFatherName(),
                'motherName' => $user->getMotherName()
            ],
            'invoiceInfo' => [
                'invoiceDueDateCode' => $cardUser->getInvoiceInfo() ? $cardUser->getInvoiceInfo()->getInvoiceDueDateCode() : "10",
                'invoiceDeliveryType' => $cardUser->getInvoiceInfo() ? $cardUser->getInvoiceInfo()->getInvoiceDeliveryType() : "email",
            ],
            'creditLimit' => (int) $cardUser->getCreditLimit(),
            'cardType' => $card->getCardType(),
            'productType' => $card->getProductType(),
            'embossingName' => $card->getEmbossingName(),
            'generateEmbossing' => $card->getGenerateEmbossing()
        ];

        $httpClient = new HttpRequest(
            $this->cardServiceHelper->getBaseUrl() . $path,
            HttpMethods::POST,
            $payload
        );

        $httpClient = $this->cardServiceHelper->doRequest($httpClient, 'criar portador');

        $response = $httpClient->getResponseBody();
        $cardUser->setIssuerId($response['id'] ?? null);
        $card->setIssuerId($response['cardId'] ?? null);
        $card->setEmbossingName($response['embossingName'] ?? null);

        return $cardUser;
    }

    public function listCards(BrandedCardUser $cardUser): array
    {
        $issuerId = $cardUser->getIssuerId();

        if (!$issuerId) {
            throw new \InvalidArgumentException('BrandedCardUser não possui issuerId definido.');
        }

        $path = "/owner/$issuerId/cards";

        $httpClient = new HttpRequest(
            $this->cardServiceHelper->getBaseUrl() . $path,
            HttpMethods::GET
        );

        $httpClient = $this->cardServiceHelper->doRequest($httpClient, 'listar cartões');

        $response = $httpClient->getResponseBody();

        return $response;
    }

    public function cardLimit(BrandedCard $card): array
    {
         $issuerId = $card->getIssuerId();

        if (!$issuerId) {
            throw new \InvalidArgumentException('BrandedCard não possui issuerId definido.');
        }

        $path = "limit/$issuerId";
        $httpClient = new HttpRequest(
            $this->cardServiceHelper->getBaseUrl() . $path,
            HttpMethods::GET
        );

        $httpClient = $this->cardServiceHelper->doRequest($httpClient, 'consulta limite do cartão');

        $response = $httpClient->getResponseBody();

        return $response;
    }

    public function cardDetails(BrandedCard $card): array
    {
         $issuerId = $card->getIssuerId();

        if (!$issuerId) {
            throw new \InvalidArgumentException('BrandedCard não possui issuerId definido.');
        }

        $path = "/card/$issuerId";

        $httpClient = new HttpRequest(
            $this->cardServiceHelper->getBaseUrl() . $path,
            HttpMethods::GET
        );

        $httpClient = $this->cardServiceHelper->doRequest($httpClient, 'consulta cartão');

        $response = $httpClient->getResponseBody();

        return $response;
    }

    
    public function updateCard(BrandedCard $card, string $status): array
    {
         $issuerId = $card->getIssuerId();

        if (!$issuerId) {
            throw new \InvalidArgumentException('BrandedCard não possui issuerId definido.');
        }

        $path = "card/$issuerId/status";
        $httpClient = new HttpRequest(
            $this->cardServiceHelper->getBaseUrl() . $path,
            HttpMethods::PUT,
            ['status' => $status]
        );

        $httpClient = $this->cardServiceHelper->doRequest($httpClient, 'atualizar status do cartão');

        $response = $httpClient->getResponseBody();

        return $response;
    }

}
