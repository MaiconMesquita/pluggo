<?php

namespace App\Application\UseCase\ListSmsHistoryGeneral;

use App\Domain\Entity\Auth;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Infra\Factory\Contract\{RepositoryFactoryContract, ServiceFactoryContract};
use App\Domain\Exception\{InternalException, InvalidDataException, NotAcceptableException};
use App\Domain\Entity\ValueObject\{
    EmployeeType,
    SmsType,
    UserType
};
use App\Domain\RepositoryContract\{
    EmployeeRepositoryContract,
    EstablishmentRepositoryContract,
    SmsHistoryRepositoryContract,
    UserRepositoryContract
};
use DateTime;

class ListSmsHistoryGeneral
{
    private SmsHistoryRepositoryContract $smsHistoryRepository;
    private UserRepositoryContract $userRepository;
    private EstablishmentRepositoryContract $establishmentRepository;
    private EmployeeRepositoryContract $employeeRepository;
    private array $repositories;


    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        private ServiceFactoryContract    $serviceFactory,
    ) {
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->smsHistoryRepository = $repositoryFactory->getSmsHistoryRepository();
        $this->establishmentRepository = $repositoryFactory->getEstablishmentRepository();
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository();
        $this->repositories = [
            'user'          => $repositoryFactory->getUserRepository(),
            'employee'      => $repositoryFactory->getEmployeeRepository(),
            'establishment' => $repositoryFactory->getEstablishmentRepository(),
            'supplier' => $repositoryFactory->getSupplierRepository(),
        ];
    }

    public function execute(ListSmsHistoryGeneralInput $input)
    {
        $authType = Auth::getLogged()->getAuthType();
        $params = [];
        $types = [];
        if ((bool) $input->passwordReset) array_push($types, "passwordReset");
        if ((bool) $input->firstPassword) array_push($types, "firstPassword");
        if ((bool) $input->codeGeneration) array_push($types, "codeGeneration");
        if ((bool) $input->invitation) array_push($types, "invitation");
        if ((bool) $input->billingTransaction) array_push($types, "billingTransaction");
        if ((bool) $input->withdrawalNotification) array_push($types, "withdrawalNotification");
        if ((bool) $input->cardRequestConfirmation) array_push($types, "cardRequestConfirmation");

        if (!empty($types))
            $params["types"] = $types;

        $userId = null;
        $employeeId = null;
        $establishmentId = null;
        if ($authType === "user") {
            $userType = Auth::getLogged()->getUserType();
            if ($userType === UserType::LEAD)
                throw new NotAcceptableException('The informed user did not complete the registration.');

            $userId = Auth::getLogged()->getUser();

            // Prepara o array de parâmetros para a consulta
            $params = [
                'userId' => $userId,
            ];
        } else if ($authType === "employee") {
            $employeeId = Auth::getLogged()->getEmployee();
            $employeeType = Auth::getLogged()->getEmployeeType();
            $employee = $this->employeeRepository->getById($employeeId);

            if ($input->employeePersonalSms) {
                // Sempre vê só o dele
                $params = ['employeeId' => $employeeId];
            } else {
                switch ($employeeType) {
                    case EmployeeType::SUPPORT:
                        // Suporte pode ver qualquer userId ou establishmentId que vier no input
                        if (!empty($input->userId)) {
                            $user = $this->userRepository->getById($input->userId);
                            if ($user && $user->getUserType() !== UserType::LEAD) {
                                $params['userId'] = $user->getId();
                            } else {
                                throw new InvalidDataException('The informed user needs to complete the registration.');
                            }
                        } elseif (!empty($input->establishmentId)) {
                            $establishment = $this->establishmentRepository->getById($input->establishmentId);
                            if ($establishment) {
                                $params['establishmentId'] = $establishment->getId();
                            } else {
                                throw new InvalidDataException('The establishment does not exist.');
                            }
                        } else {
                            throw new InvalidDataException('Support must provide either a userId or establishmentId.');
                        }

                        break;

                    case EmployeeType::ESTABLISHMENT_OWNER:
                        if (empty($input->establishmentId)) {
                            throw new InvalidDataException('You must provide the establishmentId.');
                        }

                        $establishmentId = $input->establishmentId;
                        $establishment = $this->establishmentRepository->getById($establishmentId);

                        if (!$establishment) {
                            throw new InvalidDataException('The establishment does not exist.');
                        }

                        // Verifica se esse estabelecimento realmente pertence ao dono logado
                        if ($establishment->getOwnerId() != $employeeId) {
                            $ownerId = $establishment->getOwnerId();
                            throw new NotAcceptableException('You are not the owner of this establishment.');
                        }

                        $params['establishmentId'] = $establishmentId;
                        break;
                }
            }
        } else if ($authType === "establishment") {
            $establishment = Auth::getLogged()->getEstablishment();
            // Prepara o array de parâmetros para a consulta
            $params = [
                'establishmentId' => $establishment,
            ];
        } else if ($authType === "supplier") {
            $supplier = Auth::getLogged()->getSupplier();
            // Prepara o array de parâmetros para a consulta
            $params = [
                'supplierId' => $supplier,
            ];
        }

    
        $page = $this->smsHistoryRepository->getAllPaginated(
            $input->limit,
            $input->offset,
            $params
        );

        $currentDate = DateTimeOffset::getAdjustedDateTime();

        foreach ($page->getItems() as $sms) {
            // Certifica-se de que `expirationDate` seja um objeto DateTime
            $expirationDate = $sms->expirationDate instanceof DateTime
                ? $sms->expirationDate
                : new DateTime($sms->expirationDate);

            // Verifica se o tipo é "invitation"
            if ($sms->type == SmsType::INVITATION) {
                // Verifica se a data de expiração já passou
                if ($expirationDate < $currentDate) {
                    // Remove o SMS do repositório
                    $this->smsHistoryRepository->removeById($sms->id);
                }
            }
            // Verifica se o tipo é "billingTransaction" e se o status está ativo
            elseif ($sms->type == SmsType::BILLING_TRANSACTION && $sms->status) {
                // Verifica se a data de expiração já passou
                if ($expirationDate < $currentDate) {
                    $sms = $this->smsHistoryRepository->getById($sms->id);
                    if ($sms) {
                        $sms->setStatus(false);
                        // Atualiza o SMS no repositório
                        $this->smsHistoryRepository->update($sms);
                    }
                }
            }
        }

        return $page->toJSON();
    }
}
