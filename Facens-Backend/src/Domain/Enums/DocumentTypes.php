<?php

namespace App\Domain\Enums;

enum DocumentTypes: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
    case RG = 'rg';
}
