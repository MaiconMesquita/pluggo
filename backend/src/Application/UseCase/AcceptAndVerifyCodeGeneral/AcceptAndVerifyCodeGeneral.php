<?php

namespace App\Application\UseCase\AcceptAndVerifyCodeGeneral;

use App\Domain\Entity\Service\ConnectifyService\ConnectifyServiceRequests;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\Service\SmsService\SmsServiceConnectify;
use App\Domain\Entity\ValueObject\SmsType;
use App\Domain\Exception\{
    NotAcceptableException,
    InvalidDataException
};
use App\Domain\RepositoryContract\{
    EmployeeRepositoryContract,
    UserRepositoryContract,
    SmsHistoryRepositoryContract
};
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Infra\Factory\Contract\ServiceFactoryContract;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class AcceptAndVerifyCodeGeneral
{
    private ConnectifyServiceRequests $connectifyServiceRequests;
    private array $repositories;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        private ThirdPartyFactoryContract $thirdPartyFactory,
        private ServiceFactoryContract    $serviceFactory,
    ) {
        $this->repositories = [
            'user'          => $repositoryFactory->getUserRepository(),
            'employee'      => $repositoryFactory->getEmployeeRepository(),
            'establishment' => $repositoryFactory->getEstablishmentRepository(),
            'supplier'      => $repositoryFactory->getSupplierRepository(),
        ];
        $this->connectifyServiceRequests = new ConnectifyServiceRequests($thirdPartyFactory);
    }

    public function execute(AcceptAndVerifyCodeGeneralInput $input): bool
    {
        $type = new SmsType($input->type);
        $allowedTypes = [
            SmsType::DEVICE_ID_CODE_GENERATION,
            SmsType::PASSWORD_RESET_CODE_GENERATION,
            SmsType::REGISTRATION_CODE_GENERATION,
        ];

        if (!in_array($type->getType(), $allowedTypes)) {
            throw new NotAcceptableException('The type of SMS is not allowed on this route.');
        }

        $criteria = [];
        if (!empty($input->phone)) {
            $criteria['phone'] = $input->phone;
        }
        if (!empty($input->email)) {
            $criteria['email'] = $input->email;
        }

        // Define a entidade dinamicamente
        $entityType = $input->entity ?? 'user'; // pode ser user, employee, establishment, supplier
        if (!isset($this->repositories[$entityType])) {
            throw new InvalidDataException("Unsupported entity type: {$entityType}");
        }

        $repository = $this->repositories[$entityType];

        // DeviceId obrigatório para alguns tipos
        if (!in_array($type->getType(), [SmsType::DEVICE_ID_CODE_GENERATION]) && !empty($input->deviceId)) {
            $criteria['deviceId'] = $input->deviceId;
        } elseif (empty($criteria)) {
            throw new InvalidDataException('At least one of phone, email or deviceId must be provided.');
        }

        $entity = $repository->findOneBy($criteria);
        if (!$entity) {
            throw new NotAcceptableException(ucfirst($entityType) . ' not found.');
        }

        $body = [
            'token' => $input->code,
            'email' => $entity->getEmail(),
            'channel' => $input->channel,     
        ];

        $isValid = $this->connectifyServiceRequests->confirmToken($body);

        // Atualiza validação de registro para usuários
        if ($isValid && $entityType == 'user' && $type->getType() == SmsType::REGISTRATION_CODE_GENERATION) {
            $entity->setCodeValidation(true);
            $repository->update($entity);
        }

        return $isValid;
    }
}
