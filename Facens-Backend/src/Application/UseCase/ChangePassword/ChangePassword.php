<?php

namespace App\Application\UseCase\ChangePassword;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\Password;
use App\Domain\Exception\{InvalidAuthException, InvalidDataException};
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ChangePassword
{
    private array $repositories;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory
    ) {
        $this->repositories = [
            'driver'   => $repositoryFactory->getDriverRepository(),
            'host'     => $repositoryFactory->getHostRepository(),
            'employee' => $repositoryFactory->getEmployeeRepository(),
            'support'  => $repositoryFactory->getEmployeeRepository(),
        ];
    }

    public function execute(ChangePasswordInput $input): void
    {
        $auth = Auth::getLogged();
        $authType = $auth->getAuthType();
        $newPassword = $input->newPassword;

        $this->validatePassword($newPassword);

        if ($input->targetId !== null) {
            $this->changePasswordForOther($auth, $input);
        } else {
            $this->changeOwnPassword($auth, $input);
        }
    }

    private function changeOwnPassword(Auth $auth, ChangePasswordInput $input): void
    {
        $authType = $auth->getAuthType();

        if (!isset($this->repositories[$authType])) {
            throw new InvalidDataException("Tipo de autenticação não suportado.");
        }

        if (!$input->currentPassword) {
            throw new InvalidDataException("currentPassword é obrigatório ao trocar sua própria senha.");
        }

        $repository = $this->repositories[$authType];

        $getterMap = [
            'driver'   => 'getDriver',
            'host'     => 'getHost',
            'employee' => 'getEmployee',
            'support'  => 'getEmployee',
        ];

        $methodGet = $getterMap[$authType] ?? null;
        if (!$methodGet) {
            throw new InvalidDataException("Não autorizado.");
        }

        $entityId = $auth->$methodGet();
        $entity = $repository->getById($entityId);

        if (!$entity) {
            throw new InvalidDataException("Entidade não encontrada.");
        }

        if (!$entity->passwordVerify($input->currentPassword)) {
            throw new InvalidAuthException();
        }

        $newPassword = new Password(
            rawPassword: $input->newPassword,
            verifyIfIStrong: $authType !== 'driver'
        );

        $entity->setPassword($newPassword->getPasswordHash());
        $repository->update($entity);
    }

    private function changePasswordForOther(Auth $auth, ChangePasswordInput $input): void
    {
        $authType = $auth->getAuthType();

        if ($authType !== 'employee' && $authType !== 'support') {
            throw new InvalidDataException("Apenas funcionários podem trocar senha de terceiros.");
        }

        if (!$input->targetId || !$input->targetEntityType) {
            throw new InvalidDataException("targetId e targetEntityType são obrigatórios para trocar senha de terceiros.");
        }

        if (!in_array($input->targetEntityType, ['driver', 'host'], true)) {
            throw new InvalidDataException("targetEntityType deve ser 'driver' ou 'host'.");
        }

        $repository = $this->repositories[$input->targetEntityType];
        $entity = $repository->getById($input->targetId);

        if (!$entity) {
            throw new InvalidDataException("Entidade alvo não encontrada.");
        }

        $newPassword = new Password(
            rawPassword: $input->newPassword,
            verifyIfIStrong: $input->targetEntityType !== 'driver'
        );

        $entity->setPassword($newPassword->getPasswordHash());
        $repository->update($entity);
    }

    private function validatePassword(string $password): void
    {
        if (strlen($password) > 16) {
            throw new InvalidDataException("Senha não pode ter mais de 16 caracteres.");
        }

        if (strlen($password) < 8) {
            throw new InvalidDataException("Senha deve ter no mínimo 8 caracteres.");
        }
    }
}
