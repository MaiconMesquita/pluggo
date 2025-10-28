<?php

namespace App\Application\UseCase\Signup;

use App\Domain\Entity\Driver;
use App\Domain\Entity\Host;
use App\Domain\Entity\Service\BrevoService\BrevoEmailInput;
use App\Domain\Entity\Service\BrevoService\BrevoRequests;
use App\Domain\Entity\User;
use App\Domain\Entity\Service\ValidateDocument\ValidateDocument;
use App\Domain\Entity\ValueObject\{UserType, Document, Email, EntityType, GenerateRandomPassword, Password, PhoneNumber};
use App\Domain\Exception\{NotAcceptableException, InternalException, InvalidDataException};
use App\Domain\RepositoryContract\DriverRepositoryContract;
use App\Domain\RepositoryContract\HostRepositoryContract;
use App\Domain\RepositoryContract\UserRepositoryContract;
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
    ServiceFactoryContract,
    ThirdPartyFactoryContract
};

class Signup
{
    private DriverRepositoryContract         $driverRepository;
    private HostRepositoryContract          $hostRepository;
    private BrevoRequests $brevoService;

    public function __construct(
        private RepositoryFactoryContract $repositoryFactory,
        private ThirdPartyFactoryContract $thirdPartyFactory,


    ) {
        $this->driverRepository = $repositoryFactory->getDriverRepository();
        $this->hostRepository = $repositoryFactory->getHostRepository();
        $this->brevoService = new BrevoRequests($thirdPartyFactory);
    }


    public function execute(SignupInput $input): void
    {

        $generateRandomPassword = new GenerateRandomPassword();
        $secret = $generateRandomPassword->getValue();
        $passwordValidated = new Password($secret);
        $passwordHash = $passwordValidated->getPasswordHash();

        if ($input->userType == EntityType::DRIVER) {
            $existingPhone = $this->driverRepository->findOneBy(["phone" => $input->phone]);
            $phoneNumber = new PhoneNumber($input->phone);
            $phone = $phoneNumber->getFullPhoneNumber();

            if ($existingPhone || empty($phone))
                throw new NotAcceptableException('Phone already used.');

            $existingEmail = $this->driverRepository->findOneBy(["email" => $input->email]);
            $emailValidation = new Email($input->email);
            $email = $emailValidation->getValue();

            if ($existingEmail || empty($email))
                throw new NotAcceptableException('Email already used.');
            /*
            if (!empty($input->oneSignalId)) {
                $userOneSignalId = $this->driverRepository->findOneBy(["oneSignalId" => $input->driverRepository]);
                if (!empty($userOneSignalId))
                    throw new InvalidDataException("Onesignal id is already in use.");
            }
                */

            $emailInput = new BrevoEmailInput(
                toName: $input->name,
                toEmail: $input->email,
                subject: 'Primeira senha',
                htmlContent: "<p>Olá <b>{$input->name}</b>!<br>Sua senha é <b>{$secret}</b></p>",
            );

            try {
                $this->driverRepository->create(
                    Driver::create(
                        name: $input->name,
                        phone: $phone,
                        email: $email,
                        password: $passwordHash,
                    )
                );
                $this->brevoService->sendEmail($emailInput);
            } catch (InternalException) {
                throw new InternalException("Error creating driver.");
            }
        } else {

            $existingPhone = $this->hostRepository->findOneBy(["phone" => $input->phone]);
            $phoneNumber = new PhoneNumber($input->phone);
            $phone = $phoneNumber->getFullPhoneNumber();

            if ($existingPhone || empty($phone))
                throw new NotAcceptableException('Phone already used.');

            $existingEmail = $this->hostRepository->findOneBy(["email" => $input->email]);
            $emailValidation = new Email($input->email);
            $email = $emailValidation->getValue();

            if ($existingEmail || empty($email))
                throw new NotAcceptableException('Email already used.');
            /*
            if (!empty($input->oneSignalId)) {
                $userOneSignalId = $this->hostRepository->findOneBy(["oneSignalId" => $input->oneSignalId]);
                if (!empty($userOneSignalId))
                    throw new InvalidDataException("Onesignal id is already in use.");
            }
                */
            $emailInput = new BrevoEmailInput(
                toName: $input->name,
                toEmail: $input->email,
                subject: 'Primeira senha',
                htmlContent: "<p>Olá <b>{$input->name}</b>!<br>Sua senha é <b>{$secret}</b></p>",

            );

            try {
                $host = $this->hostRepository->create(
                    Host::create(
                        name: $input->name,
                        phone: $phone,
                        email: $email,
                        password: $passwordHash
                    )
                );

                $this->brevoService->sendEmail($emailInput);
            } catch (InternalException) {
                throw new InternalException("Error creating host.");
            }
        }
    }
}
