<?php

namespace App\Application\UseCase\SignupValidate;

use App\Domain\Entity\Service\RegistrationValidation\RegistrationValidation;
use App\Domain\Entity\Service\SessionTokenService\{
    SessionTokenService, 
    SessionTokenServiceInput, 
    SessionTokenServiceOutput};

use App\Domain\Entity\ValueObject\{
    Document,
    EmployeeType,
    // PersonalDocumentNameType,
    UserType};
use App\Domain\Exception\{
    NotAcceptableException, 
    InternalException, InvalidDataException};
use App\Domain\RepositoryContract\{
    UserRepositoryContract,
    EmployeeRepositoryContract, 
    UserEstablishmentRepositoryContract};
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract, 
    ThirdPartyFactoryContract,
    ServiceFactoryContract};
use App\Domain\Entity\Service\ValidateDocument\ValidateDocument;
use App\Domain\Entity\UserEstablishment;

class SignupValidate
{
    private UserRepositoryContract $userRepository;
    private EmployeeRepositoryContract $employeeRepository;
    private SessionTokenService $sessionTokenService;
    private RegistrationValidation $validationService;
    private UserEstablishmentRepositoryContract $userEstablishmentRepository;
    private ValidateDocument          $validateDocument;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,        
        ServiceFactoryContract    $serviceFactory, 
        ThirdPartyFactoryContract $thirdPartyFactory        
    ) {
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository();
        $this->sessionTokenService = new SessionTokenService(thirdPartyFactory: $thirdPartyFactory, repositoryFactory: $repositoryFactory);
        $this->validationService = new RegistrationValidation();
        $this->userEstablishmentRepository = $repositoryFactory->getUserEstablishmentRepository();
        $this->validateDocument  = $serviceFactory->getValidateDocument();
    }

    public function execute(SignupValidateInput $input): SessionTokenServiceOutput
    {
        $output = null;
        $isUser = $input->isUser;
        if($isUser){
            $document = new Document($input->cpf, $this->validateDocument);        
            $cpf = $document->getValue();
            $user = $this->userRepository->findOneBy(["cpf" => $cpf], loadRelationships: false); // Todo: colocar true para carregar os documentos
            if (!$user) {
                throw new NotAcceptableException('There is no user with the specified deviceId.');
            }          
            if($user->getDeviceId() !== $input->deviceId)throw new NotAcceptableException('The reported device is different from the found user.');

            if(!empty($input->oneSignalId)){
                $userOneSignalId = $this->userRepository->findOneBy(["oneSignalId" => $input->oneSignalId]);
                if(!empty($userOneSignalId))
                throw new InvalidDataException("Onesignal id is already in use.");
            }

            $userType = $user->getUserType()->getType();
            if ($userType !== UserType::LEAD)throw new NotAcceptableException('User type is invalid.');
            
            $this->validationService->validate('user', $user); // Todo: colocar para validar os documentos

            $sessionToken = new SessionTokenServiceInput(
                user: $user,
                leadValidated: false,
                password: $input->password,
            );      
            $output = $this->sessionTokenService->generateToken($sessionToken);  

            $userType = new UserType("client");       

            $user->setUserType($userType);
            try {   
                $this->userEstablishmentRepository->create(UserEstablishment::create(
                    establishmentId: 1,
                    userId: $user->getId(),
                    userCardId: null,
                ));
            } catch (InternalException) {
                throw new InternalException("Error when associating user with BrandCard.");
            }
            
            try {
                if(!empty($input->oneSignalId))$user->setOneSignalId($input->oneSignalId);   
                $this->userRepository->update($user);
            } catch (InternalException) {
                throw new InternalException("Error updating user.");
            }   
        }else{
            $latitude = $input->latitude;
            $longitude = $input->longitude;
            $acceptedAccreditationTerms = $input->acceptedAccreditationTerms;
            $acceptedTermsOfUse = $input->acceptedTermsOfUse;

            // Verifica se o funcionário já existe com o email fornecido
            $employee = $this->employeeRepository->findOneBy(["email" => $input->email]);

            if (!$employee) {
                throw new NotAcceptableException('The email provided is not registered.');
            }

            if(!empty($input->oneSignalId)){
                $employeeOneSignalId = $this->employeeRepository->findOneBy(["oneSignalId" => $input->oneSignalId]);
                if(!empty($employeeOneSignalId))
                throw new InvalidDataException("Onesignal id is already in use.");
            }

            // Caso o funcionário já tenha um deviceId
            if ($employee->getDeviceId()) {
                // Se o deviceId informado for diferente do do funcionário e do mesmo funcionário, lance exceção
                if ($employee->getDeviceId() !== $input->deviceId) {
                    throw new NotAcceptableException('The reported device is different from the found employee.');
                }
            } else {
                // Se o funcionário não tiver um deviceId, verifique se o informado é utilizado por outro funcionário
                $existingEmployee = $this->employeeRepository->findOneBy(["deviceId" => $input->deviceId]);

                if ($existingEmployee) {
                    throw new NotAcceptableException('The provided deviceId is already in use by another employee.');
                }

                // Se não houver outro funcionário com o deviceId, setamos o deviceId no funcionário
                $employee->setDeviceId($input->deviceId);
            }

            $employeeType = $employee->getEmployeeType()->getType();

            if ($employeeType !== EmployeeType::ESTABLISHMENT_OWNER) {
                throw new NotAcceptableException("It is not possible to reset this account's password using this route.");
            }

            // Define os campos personalizados que precisam ser validados
            $customFields = [
                'latitude',
                'longitude',
                'acceptedTermsOfUse',
                'acceptedAccreditationTerms'
            ];

            // Valida os campos no serviço de validação
            $validatedFields = $this->validationService->validate(
                entityType: 'employee',
                entity: $employee,
                customFields: $customFields,
                throwException: false
            );

            // Verifica se os campos obrigatórios foram retornados
            if (empty($validatedFields)) {
                throw new NotAcceptableException("The employee cannot log in through this route.");
            }

            // Validação de campos latitude e longitude
            if (
                (isset($validatedFields['latitude']) && empty($latitude)) || 
                (isset($validatedFields['longitude']) && empty($longitude))
            ) {
                throw new NotAcceptableException("Latitude and longitude are required to log in through this route.");
            }

            // Valida os campos booleanos de termos
            if (
                (isset($validatedFields['acceptedTermsOfUse']) && $validatedFields['acceptedTermsOfUse'] !== true && $acceptedTermsOfUse !== true) ||
                (isset($validatedFields['acceptedAccreditationTerms']) && $validatedFields['acceptedAccreditationTerms'] !== true && $acceptedAccreditationTerms !== true)
            ) {
                throw new NotAcceptableException("The employee must accept the terms of use and accreditation terms to log in.");
            }

            // Verifica e valida cada campo individualmente
            if ($latitude !== null) {
                if ($employee->getLatitude() !== null) {
                    throw new NotAcceptableException("Field 'latitude' has already been set and cannot be modified.");
                }
                $employee->setLatitude($latitude);
            }

            if ($longitude !== null) {
                if ($employee->getLongitude() !== null) {
                    throw new NotAcceptableException("Field 'longitude' has already been set and cannot be modified.");
                }
                $employee->setLongitude($longitude);
            }

            if ($acceptedTermsOfUse !== null) {
                if ($employee->getAcceptedTermsOfUse() !== false) {
                    throw new NotAcceptableException("Field 'acceptedTermsOfUse' has already been set and cannot be modified.");
                }
                $employee->setAcceptedTermsOfUse(true);
            }

            if ($acceptedAccreditationTerms !== null) {
                if ($employee->getAcceptedAccreditationTerms() !== false) {
                    throw new NotAcceptableException("Field 'acceptedAccreditationTerms' has already been set and cannot be modified.");
                }
                $employee->setAcceptedAccreditationTerms(true);
            }

            // Gera um token de sessão
            $sessionToken = new SessionTokenServiceInput(
                employee: $employee,
                leadValidated: false,
                password: $input->password,
            );

            $output = $this->sessionTokenService->generateToken($sessionToken);

            try {
                // Atualiza o funcionário no repositório
                if(!empty($input->oneSignalId))$employee->setOneSignalId($input->oneSignalId);
                $this->employeeRepository->update($employee);
            } catch (InternalException $e) {
                throw new InternalException("Error updating user.");
            }
        }
        return $output;
    }
}
