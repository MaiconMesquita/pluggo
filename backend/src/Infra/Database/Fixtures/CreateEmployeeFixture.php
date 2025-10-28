<?php

namespace App\Infra\Database\Fixtures;

use App\Domain\Entity\ValueObject\{Password, Status, UserType};
use App\Infra\Database\EntitiesOrm\{EmployeeEstablishment, Establishment, Employee};
use DateTime;
use Doctrine\ORM\{EntityManagerInterface, EntityRepository};

class CreateEmployeeFixture extends FixtureContract
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {}

    public function getPriority(): int
    {
        return 4;
    }

    public function execute()
    {
        // Carregar os dados dos arquivos JSON
        $employees = json_decode(file_get_contents(__DIR__.'/data/employees.json'));
        $establishments = json_decode(file_get_contents(__DIR__.'/data/employeesEstablishments.json'));
        $employeesECs = json_decode(file_get_contents(__DIR__.'/data/employeesECs.json'));

        $this->entityManager->beginTransaction();
        $password = new Password('Teste123@');

        $polo = null;
        $representatives = [];
        $establishmentOwners = [];

        // 1. Criar Support Employee (primeiro registro em employees)
        $supportEmployee = $this->createEmployee($employees[0], $password);
        $this->entityManager->persist($supportEmployee);
        
        // 2. Criar Polo e seu Estabelecimento 
        // O primeiro registro de employeesECs é o Polo e o primeiro establishment é vinculado a ele
        $poloEstablishment = $this->createEstablishment($establishments[0]);
        $polo = $this->createEmployee($employeesECs[0], $password, $poloEstablishment);
        $this->entityManager->persist($polo);
        $this->entityManager->persist($poloEstablishment);
        
        // Relacionamento do Polo com seu estabelecimento: establishmentOwnerStatus true
        $this->entityManager->persist($this->createRelationship((object)[
            'employeeId' => $polo->id,
            'establishmentId' => $poloEstablishment->id,
            'establishmentOwnerStatus' => true,
            'isSupplierEmployee' => false,
            'initialLimit' => 0,
            'maximumLimit' => 0,
        ]));
        
        // 3. Criar Representatives 
        // Representantes usam o mesmo estabelecimento do Polo, têm superiorId apontando para o Polo 
        // e NÃO são relacionados via EmployeeEstablishment
        foreach ($employees as $index => $employee) {
            if ($index === 0 || $employee->employeeType !== 'representative') {
                continue;
            }

            $representative = $this->createEmployee($employee, $password);
            $representative->superiorId = $polo->id;
            $representative->establishmentId = $poloEstablishment->id;
            $representatives[] = $representative;
            
            $this->entityManager->persist($representative);
        }
        
        // 4. Criar EstablishmentOwners e seus Estabelecimentos
        // Aqui são processados os registros de employeesECs (exceto o Polo e o Supplier)
        foreach ($employeesECs as $index => $employeeEC) {
            // Pular o Polo (primeiro registro) e os que não forem establishmentOwner
            if ($index === 0 || $employeeEC->employeeType !== 'establishmentOwner') {
                continue;
            }

            // Obs.: Considera-se que a posição dos owners no array corresponde à dos establishments
            $establishment = $this->createEstablishment($establishments[$index]);
            $owner = $this->createEmployee($employeeEC, $password, $establishment);
            $owner->establishmentId = $establishment->id;
            $establishmentOwners[] = $owner;

            // Vincular o EstablishmentOwner com seu establishment (relationship com establishmentOwnerStatus true)
            $this->entityManager->persist($this->createRelationship((object)[
                'employeeId' => $owner->id,
                'establishmentId' => $establishment->id,
                'establishmentOwnerStatus' => true,
                'isSupplierEmployee' => false,
                'initialLimit' => 0,
                'maximumLimit' => 0,
            ]));
            
            $this->entityManager->persist($owner);
            $this->entityManager->persist($establishment);
        }

        // 5. Relacionar a cada dois EstablishmentOwners a um Representative
        // Aqui agrupamos os owners em pares: para cada 2 owners, seleciona-se um representante
        $repCount = count($representatives);
        foreach ($establishmentOwners as $index => $owner) {
            // Calcula o índice do representante: cada 2 owners atribuem o mesmo rep, de forma circular
            $representativeIndex = intval(floor($index / 2)) % $repCount;
            $representative = $representatives[$representativeIndex];
            
            $this->entityManager->persist($this->createRelationship((object)[
                'employeeId' => $representative->id,
                'establishmentId' => $owner->establishmentId,
                'establishmentOwnerStatus' => false,
                'isSupplierEmployee' => false,
                'initialLimit' => 0,
                'maximumLimit' => 0,
            ]));
        }

        // 6. Ajustar o Supplier Employee (último registro de employeesECs)
        // O supplier é identificado por supplierStatus=true e deve ser convertido para EstablishmentOwner,
        // vinculado apenas ao seu próprio establishment e sem relacionamento com outros funcionários.
        $supplierIndex = count($employeesECs) - 1;
        $supplierEstablishment = $this->createEstablishment($establishments[$supplierIndex]);
        $supplierEmployee = $this->createEmployee($employeesECs[$supplierIndex], $password, $supplierEstablishment);
        // Converter o tipo de supplier para EstablishmentOwner
        $supplierEmployee->employeeType = 'establishmentOwner';
        
        $this->entityManager->persist($this->createRelationship((object)[
            'employeeId' => $supplierEmployee->id,
            'establishmentId' => $supplierEstablishment->id,
            'establishmentOwnerStatus' => true,
            'isSupplierEmployee' => false,
            'initialLimit' => 0,
            'maximumLimit' => 0,
        ]));
        
        $this->entityManager->persist($supplierEmployee);
        $this->entityManager->persist($supplierEstablishment);

        // Commit dos registros
        $this->entityManager->flush();
        $this->entityManager->commit();
    }

    /**
     * Cria um objeto Establishment a partir de dados.
     */
    private function createEstablishment($data): Establishment
    {
        $establishment = new Establishment;
        $establishment->cnpj = $data->cnpj;
        $establishment->cpf = $data->cpf;
        $establishment->businessName = $data->businessName;
        $establishment->tradeName = $data->tradeName;
        $establishment->email = $data->email;
        $establishment->phone = $data->phone;
        $establishment->street = $data->street;
        $establishment->city = $data->city;
        $establishment->state = $data->state;
        return $establishment;
    }

    /**
     * Cria um objeto Employee a partir de dados.
     */
    private function createEmployee($data, Password $password, $establishment = null): Employee
    {
        $employee = new Employee;
        $employee->name = $data->name;
        $employee->phone = $data->phone;
        $employee->cpf = $data->cpf;
        $employee->email = $data->email;
        $employee->employeeType = $data->employeeType;
        $employee->password = $password->getPasswordHash();
        if ($establishment) {
            $employee->establishment = $establishment;
        }
        return $employee;
    }

    /**
     * Cria uma relação entre Employee e Establishment.
     */
    private function createRelationship($data): EmployeeEstablishment
    {
        $relationship = new EmployeeEstablishment;
        $relationship->employeeId = $data->employeeId;
        $relationship->establishmentOwnerStatus = $data->establishmentOwnerStatus;
        $relationship->isSupplierEmployee = $data->isSupplierEmployee;
        $relationship->establishmentId = $data->establishmentId;
        $relationship->initialLimit = $data->initialLimit;
        $relationship->maximumLimit = $data->maximumLimit;
        return $relationship;
    }
}
