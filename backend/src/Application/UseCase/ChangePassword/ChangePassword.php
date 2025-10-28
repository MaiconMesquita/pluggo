<?php

namespace App\Application\UseCase\ChangePassword;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\Password;
use App\Domain\Entity\ValueObject\UserType;
use App\Domain\Exception\{InvalidAuthException, InvalidDataException, NotAcceptableException};
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ChangePassword
{
    private array $repositories;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory
    ) {
        $this->repositories = [
            'driver'          => $repositoryFactory->getDriverRepository(),
            'host'      => $repositoryFactory->getHostRepository(),
        ];
    }

    public function execute(ChangePasswordInput $input): void
    {   
        $authType    = Auth::getLogged()->getAuthType();
        $newPassword = $input->newPassword;

        // validações de tamanho da senha
        if (strlen($newPassword) > 16) {
            throw new InvalidDataException("Error: password length must not exceed 16 characters.");
        }
                
        if (strlen($newPassword) < 8) {
            throw new InvalidDataException("Error: password must be at least 8 characters in length.");
        }

        // garantir que existe repositório para o tipo logado
        if (!isset($this->repositories[$authType])) {
            throw new InvalidDataException("Unauthorized.");
        }

        $repository = $this->repositories[$authType];
        $methodGet  = 'get' . ucfirst($authType); // exemplo: getUser(), getEmployee(), getEstablishment(), getSupplier()
        $entityId   = Auth::getLogged()->$methodGet();
        $entity     = $repository->getById($entityId);

        // caso especial para "user"
        if ($authType === "driver") {

            $newPassword = new Password(rawPassword: $newPassword, verifyIfIStrong: false);

            if (!$entity->passwordVerify($input->currentPassword)) {
                throw new InvalidAuthException();
            }

            $entity->setPassword($newPassword->getPasswordHash());
            $repository->update($entity);
            return;
        }

        if ($authType === "host") {

            $newPassword = new Password($newPassword);

            if (!$entity->passwordVerify($input->currentPassword)) {
                throw new InvalidAuthException();
            }

            $entity->setPassword($newPassword->getPasswordHash());
            $repository->update($entity);
            return;
        }

        // fluxo para employee e supplier
        $newPassword = new Password($newPassword);

        if (!$entity->passwordVerify($input->currentPassword)) {
            throw new InvalidAuthException();
        }

        $entity->setPassword($newPassword->getPasswordHash());
        $repository->update($entity);
    }
}
