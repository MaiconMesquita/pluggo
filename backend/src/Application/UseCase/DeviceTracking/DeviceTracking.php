<?php

namespace App\Application\UseCase\DeviceTracking;

use App\Domain\Entity\Service\ConnectifyService\ConnectifyServiceRequests;
use App\Domain\Entity\Service\ValidateDocument\ValidateDocument;
use App\Domain\Entity\Service\ValidateDocument\ResolveDocumentField;
use App\Domain\Entity\ValueObject\{Document, Email};
use App\Domain\Exception\{InvalidDataException, NotAcceptableException};
use App\Infra\Factory\Contract\{RepositoryFactoryContract, ServiceFactoryContract, ThirdPartyFactoryContract};
use DateTime;

class DeviceTracking
{
    private ValidateDocument $validateDocument;
    private array $repositories;
    private ConnectifyServiceRequests $connectifyServiceRequests;


    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ThirdPartyFactoryContract $thirdPartyFactory,
        ServiceFactoryContract    $serviceFactory,
    ) {
        $this->validateDocument = $serviceFactory->getValidateDocument();
        $this->repositories = [
            'user'          => $repositoryFactory->getUserRepository(),
            'employee'      => $repositoryFactory->getEmployeeRepository(),
            'establishment' => $repositoryFactory->getEstablishmentRepository(),
        ];
        $this->connectifyServiceRequests = new ConnectifyServiceRequests($thirdPartyFactory);
    }
    public function execute(DeviceTrackingInput $input)
    {
        $emailValidation = new Email($input->email);
        $email = $emailValidation->getValue();
        $document = null;
        $documentType = null;

        if (!empty($input->document)) {
            $document = new Document($input->document, $this->validateDocument);
            $documentType = ResolveDocumentField::resolve($document->getValue());
        }
        $entityType = $input->entity;
        $entity     = null;
        $name = null;

        // 1. Buscar a entidade
        if ($entityType && isset($this->repositories[$entityType])) {
            $repo = $this->repositories[$entityType];

            // user busca por documento
            if ($entityType === 'user') {
                if (!$document) {
                    throw new InvalidDataException("Document is required for user.");
                }
                $entity = $repo->findOneBy([$documentType => $document->getValue()]);
            } else { // resto busca por email
                if (!$email) {
                    throw new InvalidDataException("Email is required for {$entityType}.");
                }
                $entity = $repo->findOneBy(['email' => $email]);
            }

            if (!$entity) {
                throw new NotAcceptableException("No {$entityType} found with given credentials.");
            }
        } else {
            // fallback: tenta em todos os repositórios
            foreach ($this->repositories as $type => $repo) {
                if ($type === 'user' && $document) {
                    $entity = $repo->findOneBy([$documentType => $document->getValue()]);
                } elseif ($type !== 'user' && $email) {
                    $entity = $repo->findOneBy(['email' => $email]);
                }

                if ($entity) {
                    $entityType = $type;
                    break;
                }
            }

            if (!$entity) {
                throw new NotAcceptableException("No entity found with given credentials.");
            }
        }

        if ($entityType == 'user' || $entityType == 'employee') {
            $name = $entity->getName();
        } else {
            $name = $entity->getTradeName();
        }

        if ($input->deviceId) {
            $dto = [
                "name"              => $name,
                "email"             => $input->email,
                "phoneNumber"       => $entity->getPhone(),
                "deviceId"          => $input->deviceId,
                "deviceModel"       => $input->deviceModel ?? 'unknown',
                "deviceType"        => $input->deviceType ?? 'unknown',
                "os"                => $input->os ?? 'unknown',
                "onesignalPlayerId" => $input->oneSignalId ?? null,
            ];

            try {
                $this->connectifyServiceRequests->newDevice($dto);
            } catch (\Exception $e) {
                return false;
            }

            return true;
        }
    }
}
