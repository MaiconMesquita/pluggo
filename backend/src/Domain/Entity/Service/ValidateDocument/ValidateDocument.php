<?php

namespace App\Domain\Entity\Service\ValidateDocument;

use App\Domain\Enums\DocumentTypes;

interface ValidateDocument
{
    public function setNext(ValidateDocument $next);
    public function validate(string $document): DocumentTypes;
    public function getMaskedDocument(string $document): string;
}
