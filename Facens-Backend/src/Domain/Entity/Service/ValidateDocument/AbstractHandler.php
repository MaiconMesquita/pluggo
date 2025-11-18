<?php

namespace App\Domain\Entity\Service\ValidateDocument;

use App\Domain\Enums\DocumentTypes;
use DomainException;

abstract class AbstractHandler implements ValidateDocument
{
    private $nextHandler;

    public function setNext(ValidateDocument $handler): ValidateDocument
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function validate(string $document): DocumentTypes
    {
        if ($this->nextHandler) {
            return $this->nextHandler->validate($document);
        }
        throw new DomainException("The document is invalid");
    }

    public function getMaskedDocument(string $document): string
    {
        if ($this->nextHandler) {
            return $this->nextHandler->getMaskedDocument($document);
        }
        throw new DomainException("An error has occurred");
    }
}
