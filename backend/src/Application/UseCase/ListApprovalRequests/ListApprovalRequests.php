<?php

namespace App\Application\UseCase\ListApprovalRequests;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Exception\InvalidDataException;
use App\Domain\RepositoryContract\ApprovalRequestRepositoryContract;
use App\Domain\RepositoryContract\CardRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListApprovalRequests
{
    private ApprovalRequestRepositoryContract $approvalRequestRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->approvalRequestRepository = $repositoryFactory->getApprovalRequestRepository();
    }

    public function execute(ListApprovalRequestsInput $input)
    {
        $id               = Auth::getLogged()->getEmployee();
        $employeeType     = Auth::getLogged()->getEmployeeType();

        if ($employeeType != EmployeeType::SUPPORT) {
            throw new InvalidDataException('Only suport must access this route');
        }

        $page = $this->approvalRequestRepository->getAllPaginated(
            limit: $input->limit,
            offset: $input->offset,
        );

        return $page->toJSON();
    }
}
