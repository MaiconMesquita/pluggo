<?php

namespace App\Infra\Partner;

use App\Domain\PartnerContract\PixPartnerContract;
use App\Domain\Entity\HttpRequest;
use App\Domain\Entity\PixQrCode;
use App\Domain\Entity\PixQrCodePayload;
use App\Helper\BaasHelper;
use App\Infra\Controller\HttpMethods;
use DateTime;

class PixPartner implements PixPartnerContract
{
    public function __construct(
        private BaasHelper $baasHelper,
    ) {
    }

    public function generateDynamicQRCode(PixQrCodePayload $pixQrCodePayload): PixQrCode
    {
    
        $path = '/pix/qrcode/dynamic';
        $payload = [
            'amount' => $pixQrCodePayload->getAmount(),
            'description' => $pixQrCodePayload->getDescription(),
            'expiration' => $pixQrCodePayload->getExpiration(),
            'payer' => [
                'name' => $pixQrCodePayload->getPayer()->getName(),
                'document' => $pixQrCodePayload->getPayer()->getDocument(),
                'address' => [
                    'streetName' => $pixQrCodePayload->getPayer()->getAddress()->getStreetName(),
                    'streetNumber' => $pixQrCodePayload->getPayer()->getAddress()->getStreetNumber(),
                    'complement' => $pixQrCodePayload->getPayer()->getAddress()->getComplement(),
                    'neighborhood' => $pixQrCodePayload->getPayer()->getAddress()->getNeighborhood(),
                    'zipcode' => $pixQrCodePayload->getPayer()->getAddress()->getZipcode(),
                    'city' => $pixQrCodePayload->getPayer()->getAddress()->getCity(),
                    'uf' => $pixQrCodePayload->getPayer()->getAddress()->getUf()
                ]
            ]
        ];
        
        $httpClient = new HttpRequest(
            $this->baasHelper->getBaseUrl() . $path,
            HttpMethods::POST,
            $payload
        );
        
        $httpClient = $this->baasHelper->doRequest($httpClient, 'gerar PIX dinâmico');
        
        $response = $httpClient->getResponseBody();
        $pixQRCode = new PixQrCode;
        $pixQRCode->setId($response['id']);
        $pixQRCode->setCode($response['code']);
        $pixQRCode->setExpirationDate(new DateTime($response['expirationDate']));
        $pixQRCode->setStatus($response['status']);
    
        return $pixQRCode;
    }
    
}
