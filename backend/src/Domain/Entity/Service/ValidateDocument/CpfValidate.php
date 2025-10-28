<?php

namespace App\Domain\Entity\Service\ValidateDocument;

use App\Domain\Enums\DocumentTypes;
use DomainException;

class CpfValidate extends AbstractHandler
{

    public function validate(string $document): DocumentTypes
    {
        $document = preg_replace('/[^0-9]/is', '', $document);
        $cpfSize = 11;
        if (strlen($document) == $cpfSize) {
            if ($this->validateCpf($document)) return DocumentTypes::CPF;
            throw new DomainException('The CPF is invalid');
        } else return parent::validate($document);
    }

    private function validateCpf(string $cpf): bool
    {
        $cpfSize = 11;
        if (strlen($cpf) != $cpfSize) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < $cpfSize; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % $cpfSize) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public function getMaskedDocument(string $document): string
    {
        $document = preg_replace('/[^0-9]/is', '', $document);
        $cpfSize = 11;
        if (strlen($document) == $cpfSize) return substr_replace($document, '.***.***-', 3, -2);
        else return parent::getMaskedDocument($document);
    }
}
