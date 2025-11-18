<?php

namespace App\Application\UseCase\ListEmployee;

use App\Domain\Entity\Auth;
use App\Domain\Entity\PaginatedEntities;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Exception\NotAcceptableException;
use App\Domain\RepositoryContract\EmployeeEstablishmentRepositoryContract;
use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListEmployee
{
    private EmployeeRepositoryContract $employeeRepository;
    private EmployeeEstablishmentRepositoryContract $employeeEstablishmentRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory
    ) {
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository();
        $this->employeeEstablishmentRepository = $repositoryFactory->getEmployeeEstablishmentRepository();
    }

    public function execute(ListEmployeeInput $input)
    {
        $employeeType = Auth::getLogged()->getEmployeeType();
        $id = Auth::getLogged()->getEmployee();
        $searchEmployeeType = $input->employeeType;
        $searchEmployeeId = $input->employeeId;
        $limit = $input->limit;
        $offset = $input->offset;

        $params = [];

        // Apenas SUPPORT, POLO e REPRESENTATIVE podem acessar        
        switch ($employeeType) {
            case $employeeType == EmployeeType::SUPPORT:
                break;
            case $employeeType == EmployeeType::POLO:
                $searchEmployeeType ??= EmployeeType::REPRESENTATIVE;
                if (!in_array($searchEmployeeType, [EmployeeType::REPRESENTATIVE, EmployeeType::ESTABLISHMENT_OWNER])) {
                    throw new NotAcceptableException('A hub employee can only list representatives or establishment owners.');
                }
                break;
            case $employeeType == EmployeeType::REPRESENTATIVE:
                $searchEmployeeType = EmployeeType::ESTABLISHMENT_OWNER;
                break;
            default:
                throw new NotAcceptableException('The logged-in employee does not have access.');
                break;
        }        

        // Aplicação de filtros opcionais
        if (!empty($input->filter) && !empty($input->field)) {
            $params["filter"] = $input->filter;
            $params["field"] = $input->field;
        }
        
        if(!empty($searchEmployeeId) && ($employeeType == EmployeeType::POLO || $employeeType == EmployeeType::SUPPORT)){
            $employee = $this->employeeRepository->getById($searchEmployeeId);
            if(empty($employee)){
                throw new NotAcceptableException('Employee not found!');
            }
            if($employee->getEmployeeType()->getType() != EmployeeType::REPRESENTATIVE && $employee->getEmployeeType()->getType() != EmployeeType::POLO){
                throw new NotAcceptableException('This position does not have a level below it, so you cannot use the Employee ID filter.');
            }
            
            if($employeeType == EmployeeType::POLO){
                if($employee->getEmployeeType()->getType() != EmployeeType::REPRESENTATIVE)
                throw new NotAcceptableException('This position does not have a level below it, so you cannot use the Employee ID filter.');
                
                if($id != $employee->getSuperiorId())
                throw new NotAcceptableException('This hub does not have access to this representative.');

                return $this->searchEstablishmentsByRepresentativeId(
                    id: $searchEmployeeId,
                    limit: $limit,
                    offset: $offset,
                    params: $params
                );                
            }else{
                if($employee->getEmployeeType()->getType() == EmployeeType::POLO && $searchEmployeeType == EmployeeType::ESTABLISHMENT_OWNER){
                    $params['employeeType'] = $searchEmployeeType;
                    return $this->searchEstablishmentsByHubId(
                        id: $searchEmployeeId,
                        limit: $limit,
                        offset: $offset,
                        params: $params
                    );
                }else{
                    if($employee->getEmployeeType()->getType() == EmployeeType::REPRESENTATIVE){                    
                        return $this->searchEstablishmentsByRepresentativeId(
                            id: $searchEmployeeId,
                            limit: $limit,
                            offset: $offset,
                            params: $params
                        );
                    }else{
                        $params["superiorId"] = $searchEmployeeId;
                        $page = $this->employeeRepository->searchEmployees(
                            $limit,
                            $offset,
                            params: $params,
                        );
                        return $page->toJSON();
                    }
                }
            }
        }else {
            if(!empty($searchEmployeeType))
            $params['employeeType'] = $searchEmployeeType;            

            if ($employeeType == EmployeeType::SUPPORT) {                
                $page = $this->employeeRepository->searchEmployees(
                    $limit,
                    $offset,
                    params: $params,
                );

                return $page->toJSON();
            } elseif ($employeeType === EmployeeType::POLO) {
                if ($searchEmployeeType === EmployeeType::REPRESENTATIVE){ 
                    $params["superiorId"] = $id;
                    $page = $this->employeeRepository->searchEmployees(
                        $limit,
                        $offset,
                        params: $params
                    );
        
                    return $page->toJSON();
                }
                elseif ($searchEmployeeType === EmployeeType::ESTABLISHMENT_OWNER) {
                    return $this->searchEstablishmentsByHubId(
                        id: $id,
                        limit: $limit,
                        offset: $offset,
                        params: $params
                    );
                }
                // Consulta paginada dos funcionários
                
            } elseif ($employeeType === EmployeeType::REPRESENTATIVE) {
                return $this->searchEstablishmentsByRepresentativeId(
                    id: $id,
                    limit: $limit,
                    offset: $offset,
                    params: $params
                );
            }
        }
    }
    private function searchEstablishmentsByRepresentativeId(int $id, int $limit, int $offset, array $params): PaginatedEntities | array {
        $ids = $this->employeeEstablishmentRepository->findEmployeesByEstablishmentOwnerStatus($id);
        if (!empty($ids)) {
            $params["ids"] = $ids;
        } else {
            return ["totalItems" => 0, "data" => []];
        }
        $page = $this->employeeRepository->searchEmployees(
            $limit,
            $offset,
            params: $params
        );
        return $page->toJSON();
    }
    private function searchEstablishmentsByHubId(int $id, int $limit, int $offset, array $params): PaginatedEntities | array {
        // Busca os REPRESENTATIVES do POLO e depois os ESTABLISHMENT_OWNER
        $page = $this->employeeRepository->getAllPaginated(
            limit: null,
            offset: null,
            params: ["superiorId" => $id],
        );

        $ids = [];
        foreach ($page->getItems() as $rep) {
            $ids = array_merge($ids, $this->employeeEstablishmentRepository->findEmployeesByEstablishmentOwnerStatus($rep->id));
        }

        if (!empty($ids)){ 
            $params["ids"] = $ids;
            $page = $this->employeeRepository->searchEmployees(
                $limit,
                $offset,
                params: $params
            );

            return $page->toJSON();
        }
        else 
        return ["totalItems" => 0, "data" => []];
    }
}
