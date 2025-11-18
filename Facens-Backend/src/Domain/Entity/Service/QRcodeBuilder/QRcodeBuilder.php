<?php

namespace App\Domain\Entity\Service\QRcodeBuilder;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class QRcodeBuilder
{
    /**
     * Gera um QR code em Base64 a partir de qualquer dado.
     *
     * @param mixed $data Pode ser array ou string
     * @param int $size Tamanho do QR code em pixels
     * @return string Base64 do QR code PNG
     */
    public function generateQrCodeBase64(mixed $data, int $size = 300): string
    {
        // Se for array ou objeto, converte para JSON
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->size($size)
            ->build();

        return base64_encode($result->getString());
    }
}
