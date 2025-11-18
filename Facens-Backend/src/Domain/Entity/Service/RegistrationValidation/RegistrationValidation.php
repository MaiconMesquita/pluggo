<?php

namespace App\Domain\Entity\Service\RegistrationValidation;

use App\Domain\Entity\Service\ValidationFields\RequiredFieldValidator;
use App\Domain\Exception\IncompleteRegistrationException;
use InvalidArgumentException;

class RegistrationValidation
{
    private array $defaultUserFields = [
        'name',
        'cpf',
        'rg',
        'phone',
        'email',
        'street',
        'postalCode',
        'neighborhood',
        'number',
        'city',
        'state',
        'latitude',
        'longitude',
        'codeValidation',
        'acceptedTermsOfUse',
        'acceptedCardTerms',
    ];

    private array $defaultEmployeeFields = [
        'name',
        'phone',
        'email',
    ];

    private array $defaultEstablishmentFields = [
        'businessName',
        'email',
        'phone',
    ];

    private array $defaultSupplierFields = [
        'businessName',
        'email',
        'phone',
    ];

    public function validate(
        string $entityType,
        object $entity,
        ?array $customFields = null,
        bool $throwException = true,
        bool $includeDocuments = false
    ): array {
        $requiredFields = [];

        // Define os campos padrão com base no tipo de entidade.
        switch ($entityType) {
            case 'user':
                $requiredFields = $this->defaultUserFields;
                break;

            case 'employee':
                $requiredFields = $this->defaultEmployeeFields;
                break;

            case 'establishment':
                $requiredFields = $this->defaultEstablishmentFields;
                break;

            case 'supplier':
                $requiredFields = $this->defaultSupplierFields;
                break;

            default:
                throw new InvalidArgumentException("Invalid entity type: $entityType");
        }

        // Sobrescreve os campos padrão se campos customizados forem informados.
        if ($customFields !== null) {
            $requiredFields = $customFields;
        }

        // Valida os campos obrigatórios.
        $missingFields = RequiredFieldValidator::validate($entity, $requiredFields);

        // Valida documentos se solicitado e for um User
        if ($includeDocuments && $entityType === 'user' && method_exists($entity, 'getDocuments')) {
            $documentMissingFields = $this->validateDocuments($entity);
            $missingFields = array_merge($missingFields, $documentMissingFields);
        }

        if ($throwException && !empty($missingFields)) {
            throw new IncompleteRegistrationException(
                "The following fields are required: " . implode(', ', $missingFields)
            );
        }

        return $missingFields;
    }

    /**
     * Valida os documentos de uma entidade (genérico, mas implementado especificamente para User)
     */
    private function validateDocuments(object $entity): array
    {
        $missingFields = [];

        // Mapeamento dos tipos de documentos para os nomes dos campos
        $requiredDocuments = [
            'selfiePhoto' => 'selfiePhoto',
            'documentFront' => 'documentFront',
            'documentBack' => 'documentBack',
        ];

        $documents = $entity->getDocuments();

        foreach ($requiredDocuments as $documentType => $fieldName) {
            $found = false;
            $approved = false;

            foreach ($documents as $document) {
                if ($document->getDocumentType() === $documentType) {
                    $found = true;
                    if ($document->getStatus() === 'approved') {
                        $approved = true;
                    }
                    break;
                }
            }

            // Adiciona aos campos faltantes se não encontrado ou não aprovado
            if (!$found || !$approved) {
                $missingFields[] = $fieldName;
            }
        }

        return $missingFields;
    }
}
