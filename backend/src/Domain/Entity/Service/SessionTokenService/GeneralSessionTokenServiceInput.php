<?php

namespace App\Domain\Entity\Service\SessionTokenService;

class GeneralSessionTokenServiceInput extends SessionTokenServiceInput
{
    public object $entity;       // pode ser User, Employee, Establishment ou Supplier
    public string $entityType;   // 'user', 'employee', 'establishment', 'supplier'
    public string $password;
    public ?bool $leadValidated;

    public function __construct(
        object $entity,
        string $entityType,
        string $password,
        ?bool $leadValidated = true
    ) {
        $this->entity = $entity;
        $this->entityType = $entityType;
        $this->password = $password;
        $this->leadValidated = $leadValidated;
    }

    /**
     * Métodos auxiliares para acessar a entidade de forma tipada se necessário
     */
    public function getUser(): ?\App\Domain\Entity\User
    {
        return $this->entityType === 'user' ? $this->entity : null;
    }

    public function getEmployee(): ?\App\Domain\Entity\Employee
    {
        return $this->entityType === 'employee' ? $this->entity : null;
    }

    public function getEstablishment(): ?\App\Domain\Entity\Establishment
    {
        return $this->entityType === 'establishment' ? $this->entity : null;
    }

    public function getSupplier(): ?\App\Domain\Entity\Supplier
    {
        return $this->entityType === 'supplier' ? $this->entity : null;
    }
}
