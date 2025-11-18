<?php

namespace App\Domain\Entity\Service\ValidateDocument;

use App\Domain\Enums\DocumentTypes;
use DomainException;

class CnpjValidate extends AbstractHandler
{

    public function validate(string $document): DocumentTypes
    {
        $cnpjSize = 14;
        $document = preg_replace('/[^0-9]/', '', (string) $document);
        if (strlen($document) == $cnpjSize) {
            if ($this->validateCnpj($document)) return DocumentTypes::CNPJ;
            throw new DomainException('The CNPJ is invalid');
        } else return parent::validate($document);
    }

    private function validateCnpj(string $cnpj): bool
    {
        $cnpjSize = 14;
        if (strlen($cnpj) != $cnpjSize) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto) ? true : false;
    }

    public function getMaskedDocument(string $document): string
    {
        $cnpjSize = 14;
        $document = preg_replace('/[^0-9]/', '', (string) $document);
        if (strlen($document) == $cnpjSize) return substr_replace($document, '.***.***/****-', 2, -2);
        else return parent::getMaskedDocument($document);
    }
}
