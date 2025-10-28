<?php

namespace App\Application\UseCase\SignupHalfway;

use App\Domain\Entity\Service\RegistrationValidation\RegistrationValidation;
use App\Domain\Entity\Service\UploadFile\UploadFile;
use App\Domain\Entity\Service\ValidateAndUploadFile\ValidateAndUploadFile;
use App\Domain\Entity\UserDocument;
use App\Domain\Entity\ValueObject\{UserType, DocumentType};
use App\Domain\Exception\{DeviceNotFoundException, NotAcceptableException, InternalException, InvalidDataException};
use App\Domain\RepositoryContract\UserRepositoryContract;
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
    ServiceFactoryContract,
    ThirdPartyFactoryContract,
};
use App\Infra\ThirdParty\Storage\Storage;
use DateTime;

class SignupHalfway
{
    private UserRepositoryContract $userRepository;
    private RegistrationValidation $validationService;
    private UploadFile $uploadFile;
    private ValidateAndUploadFile $validateAndUploadFile;
    private Storage $storage;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
        ServiceFactoryContract $serviceFactory,
        ThirdPartyFactoryContract $thirdPartyFactory
    ) {
        $this->userRepository = $repositoryFactory->getUserRepository();

        $this->storage = $thirdPartyFactory->getStorage();
        $this->uploadFile = $serviceFactory->getUploadFile(
            $this->storage
        );
        $this->validateAndUploadFile = new ValidateAndUploadFile($this->uploadFile);
        $this->validationService = new RegistrationValidation();
    }

    public function execute(SignupHalfwayInput $input): void
    {
        $user = $this->userRepository->findOneBy(["deviceId" => $input->deviceId], loadRelationships: true);

        if (!$user) {
            throw new DeviceNotFoundException();
        }

        // Validação completa incluindo documentos
        $missingFields = $this->validationService->validate(
            entityType: 'user',
            entity: $user,
            throwException: false,
            includeDocuments: true
        );

        if ($user->getUserType()->getType() !== UserType::LEAD && count($missingFields) === 0) {
            throw new NotAcceptableException('The user has completed the registration process.');
        }

        // Verifica se o usuário é LEAD
        $isLead = $user->getUserType()->getType() === UserType::LEAD;

        // Upload de selfie
        if (($isLead || in_array('selfiePhoto', $missingFields)) && isset($input->selfiePhoto)) {
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

        // Upload de documento frente
        if (($isLead || in_array('documentFront', $missingFields)) && isset($input->documentFront)) {
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

        // Upload de documento verso
        if (($isLead || in_array('documentBack', $missingFields)) && isset($input->documentBack)) {
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

        // Atualiza campos, dependendo se o usuário é LEAD ou não
        if (($isLead || in_array('street', $missingFields)) && isset($input->street)) {
            $user->setStreet($input->street);
        }
        if (($isLead || in_array('postalCode', $missingFields)) && isset($input->postalCode)) {
            $postalCode = (string) $input->postalCode;
            $user->setPostalCode($postalCode);
        }
        if (($isLead || in_array('neighborhood', $missingFields)) && isset($input->neighborhood)) {
            $user->setNeighborhood($input->neighborhood);
        }
        if (($isLead || in_array('number', $missingFields)) && isset($input->number)) {
            $user->setNumber($input->number);
        }
        if (($isLead || in_array('complement', $missingFields)) && isset($input->complement)) {
            $user->setComplement($input->complement);
        }
        if (($isLead || in_array('city', $missingFields)) && isset($input->city)) {
            $user->setCity($input->city);
        }
        if (($isLead || in_array('state', $missingFields)) && isset($input->state)) {
            $user->setState($input->state);
        }
        if (($isLead || in_array('acceptedCardTerms', $missingFields)) && isset($input->acceptedCardTerms)) {
            $user->setAcceptedCardTerms($input->acceptedCardTerms);
        }
        if (($isLead || in_array('acceptedTermsOfUse', $missingFields)) && isset($input->acceptedTermsOfUse)) {
            $user->setAcceptedTermsOfUse($input->acceptedTermsOfUse);
        }
        if (($isLead || in_array('latitude', $missingFields)) && isset($input->latitude)) {
            $user->setLatitude($input->latitude);
        }
        if (($isLead || in_array('longitude', $missingFields)) && isset($input->longitude)) {
            $user->setLongitude($input->longitude);
        }
        if (($isLead || in_array('birthDate', $missingFields)) && isset($input->birthDate)) {
            $birthDate = new DateTime($input->birthDate);
            $user->setBirthDate($birthDate);
        }

        if (($isLead || in_array('maritalStatus', $missingFields)) && isset($input->maritalStatus)) {
            if (strlen($input->maritalStatus) === 1) {
                $user->setMaritalStatus($input->maritalStatus);
            } else {
                throw new InvalidDataException("The 'maritalStatus' field must contain exactly 1 character.");
            }
        }

        if (($isLead || in_array('gender', $missingFields)) && isset($input->gender)) {
            if (strlen($input->gender) === 1) {
                $user->setGender($input->gender);
            } else {
                throw new InvalidDataException("The 'gender' field must contain exactly 1 character.");
            }
        }

        if (($isLead || in_array('issuingState', $missingFields)) && isset($input->issuingState)) {
            $user->setIssuingState($input->issuingState);
        }

        if (($isLead || in_array('issuingAuthority', $missingFields)) && isset($input->issuingAuthority)) {
            $user->setIssuingAuthority($input->issuingAuthority);
        }

        if (($isLead || in_array('fatherName', $missingFields)) && isset($input->fatherName)) {
            $user->setFatherName($input->fatherName);
        }

        if (($isLead || in_array('rg', $missingFields)) && isset($input->rg)) {
            $user->setRg($input->rg);
        }

        if (($isLead || in_array('motherName', $missingFields)) && isset($input->motherName)) {
            $user->setMotherName($input->motherName);
        }
        if (($isLead || in_array('nationality', $missingFields)) && isset($input->nationality)) {
            $user->setNationality($input->nationality);
        }

        try {
            $this->userRepository->update($user);
        } catch (InternalException) {
            throw new InternalException("Error updating user.");
        }
    }

}