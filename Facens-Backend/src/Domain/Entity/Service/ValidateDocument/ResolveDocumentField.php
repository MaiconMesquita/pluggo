<?php

namespace App\Domain\Entity\Service\ValidateDocument;

class ResolveDocumentField
{
    public static function resolve(string $document): string
    {
        // remove caracteres não numéricos
        $doc = preg_replace('/\D/', '', $document);

        return strlen($doc) <= 11 ? 'cpf' : 'cnpj';
    }
}
