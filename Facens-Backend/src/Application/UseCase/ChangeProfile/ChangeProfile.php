<?php

namespace App\Application\UseCase\ChangeProfile;

use App\Domain\Entity\Auth;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\Service\ValidateAndUploadFile\ValidateAndUploadFile;
use App\Domain\Entity\Service\ValidateDocument\ValidateDocument;
use App\Domain\Entity\UserDocument;
use App\Domain\Entity\ValueObject\{
    Document,
    PhoneNumber,
    Email,
    EmployeeType,
    Password,
    UserType,
    DocumentType,
};
use App\Domain\Exception\{
    NotAcceptableException,
    InternalException,
    InvalidDataException
};
use App\Domain\RepositoryContract\{
    EmployeeRepositoryContract,
    EstablishmentRepositoryContract,
    UserRepositoryContract
};
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
    ServiceFactoryContract,
    ThirdPartyFactoryContract,
};
use App\Infra\ThirdParty\Storage\Storage;
use DateTime;

class ChangeProfile
{
    private EstablishmentRepositoryContract $establishmentRepository;
    private UserRepositoryContract $userRepository;
    private EmployeeRepositoryContract $employeeRepository;
    private ValidateAndUploadFile $validateAndUploadFile;
    private Storage $storage;
    private ValidateDocument $validateDocument;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ServiceFactoryContract $serviceFactory,
        ThirdPartyFactoryContract $thirdPartyFactory
    ) {
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository();
        $this->establishmentRepository = $repositoryFactory->getEstablishmentRepository();
        $uploadFileService = $serviceFactory->getUploadFile(
            $thirdPartyFactory->getStorage()
        );
        $this->validateAndUploadFile = new ValidateAndUploadFile($uploadFileService);
        $this->storage = $thirdPartyFactory->getStorage();
        $this->validateDocument = $serviceFactory->getValidateDocument();
    }

    public function execute(ChangeProfileInput $input)
    {
        $authType = Auth::getLogged()->getAuthType();

        if ($authType === "employee") {
            $employeeType = Auth::getLogged()->getEmployeeType();
            $currentEmployeeId = Auth::getLogged()->getEmployee();

            if ($employeeType !== EmployeeType::SUPPORT || $input->id === null) {
                $employee = $this->employeeRepository->getById($currentEmployeeId);
                if (isset($input->name))
                    $employee->setName($input->name);
            } elseif ($input->isUser) {
                $user = $this->userRepository->getById($input->id);

                if (!$user)
                    throw new NotAcceptableException('There is no user with the specified id.');

                if (isset($input->selfiePhoto)) {
                    try {
                        $file = $this->validateAndUploadFile->validateAndUpload($input->selfiePhoto, "selfie Photo");

                        $userDocument = UserDocument::withParams(
                            status: false,
                            documentType: new DocumentType(DocumentType::SELFIE),
                            file: $file,
                            user: $user
                        );

                        $this->userRepository->saveUserDocument($user->getId(), $userDocument);
                    } catch (InternalException) {
                        throw new InternalException("Error saving selfie photo.");
                    }
                }

                if (isset($input->documentFront)) {
                    try {
                        $file = $this->validateAndUploadFile->validateAndUpload($input->documentFront, "document front");

                        $userDocument = UserDocument::withParams(
                            status: false,
                            documentType: new DocumentType(DocumentType::DOCUMENT_FRONT),
                            file: $file,
                            user: $user
                        );

                        $this->userRepository->saveUserDocument($user->getId(), $userDocument);
                    } catch (InternalException) {
                        throw new InternalException("Error saving document front.");
                    }
                }

                if (isset($input->documentBack)) {
                    try {
                        $file = $this->validateAndUploadFile->validateAndUpload($input->documentBack, "document back");

                        $userDocument = UserDocument::withParams(
                            status: false,
                            documentType: new DocumentType(DocumentType::DOCUMENT_BACK),
                            file: $file,
                            user: $user
                        );

                        $this->userRepository->saveUserDocument($user->getId(), $userDocument);
                    } catch (InternalException) {
                        throw new InternalException("Error saving document back.");
                    }
                }

                if (isset($input->name))
                    $user->setName($input->name);
                if (isset($input->rg))
                    $user->setRg($input->rg);
                if (isset($input->birthDate)) {
                    // Validação de campos exclusivos de usuário
                    if ($input->birthDate instanceof DateTime) {
                        throw new InvalidDataException('Birth date must be a valid DateTime object.');
                    }
                    $user->setBirthDate($input->birthDate);
                }
                if (isset($input->gender))
                    $user->setGender($input->gender);
                if (isset($input->fatherName))
                    $user->setFatherName($input->fatherName);
                if (isset($input->motherName))
                    $user->setMotherName($input->motherName);
                if (isset($input->street))
                    $user->setStreet($input->street);
                if (isset($input->number))
                    $user->setNumber($input->number);
                if (isset($input->complement))
                    $user->setComplement($input->complement);
                if (isset($input->neighborhood))
                    $user->setNeighborhood($input->neighborhood);
                if (isset($input->city))
                    $user->setCity($input->city);
                if (isset($input->state))
                    $user->setState($input->state);
                if (isset($input->postalCode))
                    $user->setPostalCode((string) $input->postalCode);

                if (isset($input->deviceId)) {
                    $deviceId = $input->deviceId;
                    $existingDeviceId = $this->userRepository->findOneBy(["deviceId" => $deviceId]);

                    if (!$existingDeviceId && !empty($deviceId))
                        $user->setDeviceId($deviceId);
                    else
                        throw new NotAcceptableException('DeviceId already used.');
                }

                if (isset($input->phone)) {
                    $phoneNumber = new PhoneNumber($input->phone);
                    $phone = $phoneNumber->getFullPhoneNumber();
                    $existingPhone = $this->userRepository->findOneBy(["phone" => $phone]);

                    if (!$existingPhone && !empty($phone))
                        $user->setPhone($phone);
                    else
                        throw new NotAcceptableException('Phone already used.');
                }

                if (isset($input->email)) {
                    $emailValidation = new Email($input->email);
                    $email = $emailValidation->getValue();
                    $existingEmail = $this->userRepository->findOneBy(["email" => $email]);

                    if (!$existingEmail && !empty($email))
                        $user->setEmail($email);
                    else
                        throw new NotAcceptableException('Email already used.');
                }
                if (isset($input->cpf)) {
                    $document = new Document($input->cpf, $this->validateDocument);
                    $cpf = $document->getValue();
                    $existingCpf = $this->userRepository->findOneBy(["cpf" => $cpf]);

                    if (!$existingCpf && !empty($cpf))
                        $user->setCpf($cpf);
                    else
                        throw new NotAcceptableException('Cpf already used.');
                }

                if (isset($input->currentBalance)) {
                    if ($input->currentBalance < 0) {
                        throw new InvalidDataException('Current balance must be a positive value.');
                    }
                    $user->setCurrentBalance($input->currentBalance);
                }

                if (isset($input->status)) {
                    if (!is_bool($input->status)) {
                        throw new InvalidDataException('Status must be a boolean value.');
                    }
                    $user->setStatus($input->status);
                    $input->status ?? $user->setDeactivationDate(DateTimeOffset::getAdjustedDateTime());
                }

                if (isset($input->password)) {
                    $passwordValidated = new Password($input->password);
                    $passwordHash = $passwordValidated->getPasswordHash();
                    $user->getChangePassword() ?? $user->setChangePassword(true);
                    $user->setPassword($passwordHash);
                }

                if (isset($input->codeValidation)) {
                    if (!is_bool($input->codeValidation)) {
                        throw new InvalidDataException('codeValidation must be a boolean value.');
                    }
                    $user->setCodeValidation($input->codeValidation);
                }

                try {
                    $user = $this->userRepository->update($user);
                    return ["user" => $user->toJSON()];
                } catch (InternalException) {
                    throw new InternalException("Error updating user.");
                }
            } else {
                $employee = $this->employeeRepository->getById($input->id);

                if (isset($input->phone)) {
                    $phoneNumber = new PhoneNumber($input->phone);
                    $phone = $phoneNumber->getFullPhoneNumber();
                    $existingPhone = $this->employeeRepository->findOneBy(["phone" => $phone]);

                    if (!$existingPhone && !empty($phone))
                        $employee->setPhone($phone);
                    else
                        throw new NotAcceptableException('Phone already used.');
                }

                if (isset($input->email)) {
                    $emailValidation = new Email($input->email);
                    $email = $emailValidation->getValue();
                    $existingEmail = $this->employeeRepository->findOneBy(["email" => $email]);

                    if (!$existingEmail && !empty($email))
                        $employee->setEmail($email);
                    else
                        throw new NotAcceptableException('Email already used.');
                }
                if (isset($input->cpf)) {
                    $document = new Document($input->cpf, $this->validateDocument);
                    $cpf = $document->getValue();
                    $existingCpf = $this->employeeRepository->findOneBy(["cpf" => $cpf]);

                    if (!$existingCpf && !empty($cpf))
                        $employee->setCpf($cpf);
                    else
                        throw new NotAcceptableException('Cpf already used.');
                }

                if (isset($input->currentBalance)) {
                    if ($input->currentBalance < 0) {
                        throw new InvalidDataException('Current balance must be a positive value.');
                    }
                    $employee->setCurrentBalance($input->currentBalance);
                }

                if (isset($input->establishmentId)) {
                    $establishment = $this->establishmentRepository->getById($input->establishmentId);
                    if (!$establishment) {
                        throw new InvalidDataException('Establishment not found for the given ID.');
                    }
                    $employee->setEstablishmentId($input->establishmentId);
                }

                if (isset($input->amountToReceive)) {
                    if ($input->amountToReceive < 0) {
                        throw new InvalidDataException('Amount to receive must be a positive value.');
                    }
                    $employee->setAmountToReceive($input->amountToReceive);
                }

                if (isset($input->status)) {
                    if (!is_bool($input->status)) {
                        throw new InvalidDataException('Status must be a boolean value.');
                    }
                    $employee->setStatus($input->status);
                    $input->status ?? $employee->setDeactivationDate(DateTimeOffset::getAdjustedDateTime());
                }

                if (isset($input->password)) {
                    $passwordValidated = new Password($input->password);
                    $passwordHash = $passwordValidated->getPasswordHash();
                    $employee->getChangePassword() ?? $employee->setChangePassword(true);
                    $employee->setPassword($passwordHash);
                }
                try {
                    $employee = $this->employeeRepository->update($employee);
                    return ["employee" => $employee->toJSON()];
                } catch (InternalException) {
                    throw new InternalException("Error updating employee.");
                }
            }
        } else if ($authType === "user") {

            $currentUserId = Auth::getLogged()->getUser();

            $user = $this->userRepository->getById($currentUserId);

            if (!$user)
                throw new NotAcceptableException('There is no user with the specified id.');

            if ($user->getUserType()->getType() === UserType::LEAD)
                throw new NotAcceptableException('The informed user did not complete the registration.');

            if (isset($input->selfiePhoto)) {
                $file = $this->validateAndUploadFile->validateAndUpload($input->selfiePhoto, "selfie Photo");

                $userDocument = UserDocument::withParams(
                    status: false,
                    documentType: new DocumentType(DocumentType::SELFIE),
                    file: $file,
                    user: $user
                );

                try {
                    $this->userRepository->saveUserDocument($user->getId(), $userDocument);
                } catch (InternalException) {
                    throw new InternalException("Error saving selfie photo.");
                }
            }

            if (isset($input->documentFront)) {
                $file = $this->validateAndUploadFile->validateAndUpload($input->documentFront, "document front");

                $userDocument = UserDocument::withParams(
                    status: false,
                    documentType: new DocumentType(DocumentType::DOCUMENT_FRONT),
                    file: $file,
                    user: $user
                );

                try {
                    $this->userRepository->saveUserDocument($user->getId(), $userDocument);
                } catch (InternalException) {
                    throw new InternalException("Error saving document front.");
                }
            }

            if (isset($input->documentBack)) {
                $file = $this->validateAndUploadFile->validateAndUpload($input->documentBack, "document back");

                $userDocument = UserDocument::withParams(
                    status: false,
                    documentType: new DocumentType(DocumentType::DOCUMENT_BACK),
                    file: $file,
                    user: $user
                );

                try {
                    $this->userRepository->saveUserDocument($user->getId(), $userDocument);
                } catch (InternalException) {
                    throw new InternalException("Error saving document back.");
                }
            }
            if (isset($input->name))
                $user->setName($input->name);
            if (isset($input->rg))
                $user->setRg($input->rg);

            if ($input->isPushNotificationEnabled !== null)
                $user->setIsPushNotificationEnabled($input->isPushNotificationEnabled);
            if ($input->isPromotionalNotificationEnabled !== null)
                $user->setIsPromotionalNotificationEnabled($input->isPromotionalNotificationEnabled);
            if (isset($input->birthDate)) {
                // Validação de campos exclusivos de usuário
                if ($input->birthDate instanceof DateTime) {
                    throw new InvalidDataException('Birth date must be a valid DateTime object.');
                }
                $user->setBirthDate($input->birthDate);
            }
            if (isset($input->gender))
                $user->setGender($input->gender);
            if (isset($input->fatherName))
                $user->setFatherName($input->fatherName);
            if (isset($input->motherName))
                $user->setMotherName($input->motherName);
            if (isset($input->street))
                $user->setStreet($input->street);
            if (isset($input->number))
                $user->setNumber($input->number);
            if (isset($input->complement))
                $user->setComplement($input->complement);
            if (isset($input->neighborhood))
                $user->setNeighborhood($input->neighborhood);
            if (isset($input->city))
                $user->setCity($input->city);
            if (isset($input->state))
                $user->setState($input->state);
            if (isset($input->postalCode))
                $user->setPostalCode((string) $input->postalCode);

            try {
                $user = $this->userRepository->update($user);
                return ["user" => $user->toJSON()];
            } catch (InternalException) {
                throw new InternalException("Error updating user.");
            }
        }
    }
}