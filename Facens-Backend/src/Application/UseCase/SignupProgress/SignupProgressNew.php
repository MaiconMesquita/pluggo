<?php

// namespace App\Application\UseCase\SignupProgress;

// use App\Domain\Entity\ValueObject\UserType;
// use App\Domain\Exception\DeviceNotFoundException;
// use App\Domain\RepositoryContract\UserRepositoryContract;
// use App\Infra\Factory\Contract\RepositoryFactoryContract;

// class SignupProgress
// {
//     private UserRepositoryContract $userRepository;

//     public function __construct(
//         RepositoryFactoryContract $repositoryFactory
//     ) {
//         $this->userRepository = $repositoryFactory->getUserRepository();
//     }

//     public function execute(SignupProgressInput $input): SignupProgressOutput | null
//     {        
//         $user = $this->userRepository->findOneBy(["deviceId" => $input->deviceId], loadRelationships: true);
        
//         if (!$user) {
//             throw new DeviceNotFoundException();
//         }

//         $changePassword = $user->getChangePassword();
//         $missingFields = [];
//         $step = 1;
//         $userType = $user->getUserType()->getType();

//         if ($userType !== UserType::LEAD) {
//             return $this->buildOutput($missingFields, $step = 8, $changePassword, $userType);
//         }     
//         // Etapa 1: Verifica name, cpf, rg
//         if (empty($user->getName())) $missingFields[] = 'name';
//         if (empty($user->getCpf())) $missingFields[] = 'cpf';        
//         if (empty($user->getPhone())) $missingFields[] = 'phone';
//         if (empty($user->getEmail())) $missingFields[] = 'email';     
        
//         if (empty($missingFields)) $step = 2; // Todos os campos da etapa 1 estão preenchidos

//         if (empty($user->getRg())) $missingFields[] = 'rg';
//         if (empty($user->getIssuingAuthority())) $missingFields[] = 'issuingAuthority'; //orgão emissor
//         if (empty($user->getIssuingState())) $missingFields[] = 'issuingState'; //estado emissor
//         if (empty($user->getMotherName())) $missingFields[] = 'motherName'; //nome da mãe
//         if (empty($user->getFatherName())) $missingFields[] = 'fatherName'; //nome do pai
        
//         if (empty($missingFields) && $step == 2) $step = 3;

//         if (empty($user->getGender())) $missingFields[] = 'gender'; //genero
//         if (empty($user->getBirthDate())) $missingFields[] = 'birthDate'; //data de nascimento
//         if (empty($user->getMaritalStatus())) $missingFields[] = 'maritalStatus';  //nacionalidade
//         if (empty($user->getNationality())) $missingFields[] = 'nationality'; //estado civil
        
//         if (empty($missingFields) && $step == 3) $step = 4;

//         $codeValidation = $user->getCodeValidation();
        
//         if (!$codeValidation) $missingFields[] = 'codeValidation';
        
//         if (empty($missingFields) && $step == 4) $step = 5; // Todos os campos da etapa 2 estão preenchidos

//         // Etapa 4: Verifica selfiePhoto
//         if (!$user->getSelfiePhoto()) $missingFields[] = 'selfiePhoto';

//         if (empty($missingFields) && $step == 4) $step = 5; // Todos os campos da etapa 4 estão preenchidos        

//         // Etapa 5: Verifica documentFront
//         if (!$user->getDocumentFront()) $missingFields[] = 'documentFront';

//         if (empty($missingFields) && $step == 5) $step = 6; // Todos os campos da etapa 5 estão preenchidos        

//         // Etapa 6: Verifica documentBack
//         if (!$user->getDocumentBack()) $missingFields[] = 'documentBack';

//         if (empty($missingFields) && $step == 6) $step = 7; // Todos os campos da etapa 6 estão preenchidos        

//         // Etapa 7: Verifica os campos de endereço
//         if (empty($user->getPostalCode())) $missingFields[] = 'postalCode';
//         if (empty($user->getStreet())) $missingFields[] = 'street';
//         if (empty($user->getNumber())) $missingFields[] = 'number';
//         if (empty($user->getNeighborhood())) $missingFields[] = 'neighborhood';
//         if (empty($user->getCity())) $missingFields[] = 'city';
//         if (empty($user->getState())) $missingFields[] = 'state';

//         if (empty($missingFields) && $step == 7) $step = 8; // Todos os campos da etapa 7 estão preenchidos   
        
//         // Etapa 8: Verifica acceptedTermsOfUse e acceptedCardTerms
//         $acceptedTermsOfUse = $user->getAcceptedTermsOfUse();
//         if (!$acceptedTermsOfUse) $missingFields[] = 'acceptedTermsOfUse';
//         if (empty($missingFields) && $step == 8) $step = 9; // Todos os campos da etapa 8 estão preenchidos

//         // Etapa 9: Verifica latitude e longitude
//         $acceptedCardTerms = $user->getAcceptedCardTerms();
//         if (!$acceptedCardTerms) $missingFields[] = 'acceptedCardTerms';
//         if (empty($missingFields) && $step == 9) $step = 10; // Todos os campos da etapa 9 estão preenchidos

//         // Etapa 10: Verifica latitude e longitude
//         if (empty($user->getLatitude())) $missingFields[] = 'latitude';
//         if (empty($user->getLongitude())) $missingFields[] = 'longitude';
//         if (empty($missingFields) && $step == 10) $step = 11; // Todos os campos da etapa 10 estão preenchidos        
        
//         // Se chegou até aqui, todos os campos estão preenchidos
//         return $this->buildOutput($missingFields, $step, $changePassword, $userType);
//     }

//     private function buildOutput(array $missingFields, int $step, bool $changePassword, string $userType): SignupProgressOutput
//     {
//         $output = new SignupProgressOutput();
//         $output->fields = !empty($missingFields) ? implode(', ', $missingFields) : 'All fields completed';
//         $output->step = $step;
//         $output->changePassword = $changePassword;
//         $output->userType = $userType;

//         return $output;
//     }
// }
