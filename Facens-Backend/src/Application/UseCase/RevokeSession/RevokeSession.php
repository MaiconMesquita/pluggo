<?php

namespace App\Application\UseCase\RevokeSession;

use App\Domain\Entity\Auth;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Exception\{InternalException, InvalidDataException, NotAcceptableException};
use App\Domain\RepositoryContract\EmployeeRepositoryContract;
use App\Domain\RepositoryContract\UserRepositoryContract;
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Infra\Repository\LoginHistoryRepository;
use App\Infra\Repository\TokenRepository;
use DateTime;

class RevokeSession
{
    private LoginHistoryRepository $loginHistoryRepository;
    private TokenRepository $tokenRepository;
    private UserRepositoryContract $userRepository;
    private EmployeeRepositoryContract $employeeRepository;

    public function __construct(
        private RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->tokenRepository = $repositoryFactory->getTokenRepository();
        $this->loginHistoryRepository = $repositoryFactory->getLoginHistoryRepository();  
        $this->employeeRepository = $repositoryFactory->getEmployeeRepository(); 
        $this->userRepository = $repositoryFactory->getUserRepository();  
    }

    public function execute(RevokeSessionInput $input)
    {
        $authType = Auth::getLogged()->getAuthType();
        $id = null;
        if($authType === "user"){        
            if (!isset($input->deviceId)) {
                throw new InvalidDataException('deviceId is required');
            }
            $id = Auth::getLogged()->getUser();

            $user = $this->userRepository->getById($id);
            if (!$user) {
                throw new InvalidDataException('User not found');
            }
            if($user->getDeviceId() !== $input->deviceId)throw new NotAcceptableException('The reported device is different from the found user.');
            $realUser = true;            
        }else if($authType === "employee"){

            $id = Auth::getLogged()->getEmployee();

            $employee = $this->employeeRepository->getById($id);
            if (!$employee) {
                throw new InvalidDataException('Employee not found');
            }

            if ($employee->getEmployeeType() === EmployeeType::ESTABLISHMENT_OWNER) {
                $deviceId = $input->deviceId;
                if (empty($deviceId)) {
                    throw new InvalidDataException('deviceId is required');
                }

                if ($employee->getDeviceId() !== $deviceId) {
                    throw new NotAcceptableException('The reported device is different from the found user.');
                }
            }
            $realUser = false;
        }else{
            throw new InvalidDataException("Unauthorized.");
        }
        if (!$id) {
            throw new InvalidDataException('Error finding ID');
        }

        $loginHistory = $this->loginHistoryRepository->findMostRecent(
             id: $id, realUser: $realUser   
        );
        $loginHistory->setExpiresAt(DateTimeOffset::getAdjustedDateTime());
        
        try {
            $this->loginHistoryRepository->update(
                $loginHistory
            );
        } catch (InternalException) {
            throw new InternalException("Error upadting user log.");
        }

        try {
            $this->tokenRepository->revokeTokenByUserId(id: $id, realUser: $realUser);
        } catch (InternalException) {
            throw new InternalException("Error deleting user session.");
        }
    
    }
}
