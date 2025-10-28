<?php

namespace App\Domain\Entity\Service\HierarchyService;

use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\EstablishmentRepositoryContract;
use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class HierarchyValidator
{
    private EstablishmentRepositoryContract $establishmentRepository;
    private EmployeeRepositoryContract $employeeRepository;
    public function __construct(

        RepositoryFactoryContract $repositoryFactory,

    ) {
        $this->establishmentRepository = $repositoryFactory->getEstablishmentRepository();
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository();
    }

    /**
     * Retorna o establishmentId real que o usuário pode acessar
     */
    public function validateAccess(object $loggedEmployee, string $type, ?int $inputEstablishmentId, ?int $inputOwnerId): int
    {
        switch ($type) {
            case EmployeeType::ESTABLISHMENT_OWNER:
                $ownerId = $this->getOwnerId($inputEstablishmentId);
                if (!$inputEstablishmentId || $loggedEmployee->getEmployee() != $ownerId) {
                    throw new InvalidDataException("Owner mismatch or establishmentId missing.");
                }
                return $inputEstablishmentId;

            case EmployeeType::REPRESENTATIVE:
                if (!$inputEstablishmentId || !$inputOwnerId) {
                    throw new InvalidDataException("EstablishmentId and OwnerId required.");
                }
                if (!$this->validateOwnerAndSuperior($inputEstablishmentId, $inputOwnerId, $loggedEmployee->getEmployee())) {
                    throw new InvalidDataException("Representative not allowed.");
                }
                return $inputEstablishmentId;

            case EmployeeType::POLO:
                if (!$inputEstablishmentId || !$inputOwnerId) {
                    throw new InvalidDataException("EstablishmentId and OwnerId required.");
                }
                if (!$this->validatePoloHierarchy($inputEstablishmentId, $inputOwnerId, $loggedEmployee->getEmployee())) {
                    throw new InvalidDataException("Polo not allowed.");
                }
                return $inputEstablishmentId;

            case EmployeeType::SUPPORT:
                if (!$inputEstablishmentId) {
                    throw new InvalidDataException("EstablishmentId required.");
                }
                return $inputEstablishmentId;

            default:
                throw new InvalidDataException("Unauthorized employee type.");
        }
    }

    private function getOwnerId(int $establishmentId): int
    {
        $establishment = $this->establishmentRepository->getById($establishmentId);
        if (!$establishment) {
            throw new InvalidDataException("Establishment not found.");
        }
        return $establishment->getOwnerId();
    }

    private function validateOwnerAndSuperior(int $establishmentId, int $ownerId, int $employeeId): bool
    {
        $establishment = $this->establishmentRepository->getById($establishmentId);
        if (!$establishment || $establishment->getOwnerId() !== $ownerId) {
            return false;
        }

        $owner = $this->employeeRepository->getById($ownerId);
        return $owner && $owner->getSuperiorId() === $employeeId;
    }

    private function validatePoloHierarchy(int $establishmentId, int $ownerId, int $poloId): bool
    {
        $owner = $this->employeeRepository->getById($ownerId);
        if (!$owner || $owner->getSuperiorId() !== $poloId) {
            return false;
        }

        // Pega todos os representantes subordinados ao polo
        $representativesPaginated = $this->employeeRepository->searchEmployees(
            null,
            null,
            ['superiorId' => $poloId]
        );

        $representatives = $representativesPaginated->getItems(); // pega array de Employee

        foreach ($representatives as $rep) {
            if (!$this->validateOwnerAndSuperior($establishmentId, $ownerId, $rep->getId())) {
                return false;
            }
        }

        return true;
    }
}
