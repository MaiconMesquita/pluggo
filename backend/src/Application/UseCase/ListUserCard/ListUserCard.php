<?php

namespace App\Application\UseCase\ListUserCard;

use App\Domain\Entity\Auth;
use App\Domain\Entity\ValueObject\{EmployeeType, UserType};
use App\Domain\Exception\{InvalidDataException, NotAcceptableException};
use App\Domain\RepositoryContract\{UserCardRepositoryContract, UserRepositoryContract};
use App\Infra\Factory\Contract\RepositoryFactoryContract;

class ListUserCard
{
    private UserRepositoryContract $userRepository;
    private UserCardRepositoryContract $userCardRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->userCardRepository = $repositoryFactory->getUserCardRepository();        
    }

    public function execute(ListUserCardInput $input)
    {
        $authType = Auth::getLogged()->getAuthType();
        $cardId = $input->cardId;
        $primaryUserCardId = $input->primaryUserCardId;
        $isPrimaryUserCard = $input->isPrimaryUserCard;
        $userId = null;
        $params = [];
        if($authType === "user"){
            $userType = Auth::getLogged()->getUserType();
            if ($userType === UserType::LEAD) 
            throw new NotAcceptableException('The informed user did not complete the registration.');  
            $userId = (int) Auth::getLogged()->getUser();

            if(!empty($primaryUserCardId)){
                $userCard = $this->userCardRepository->getById($primaryUserCardId);
                if($userCard->getUserId() != $userId)
                throw new NotAcceptableException('The primary card ID provided does not belong to the logged in user.');  
            }            
        }elseif($authType === "employee"){
            $employeeType = Auth::getLogged()->getEmployeeType();
            if($employeeType === EmployeeType::SUPPORT){                
                if (!empty($input->userId)) {
                    $user = $this->userRepository->getById($input->userId);
                    if(!$user)
                    throw new NotAcceptableException('User not found.');
                }
                $userId = $user ? $user->getId() : null;
                
                
            }else throw new InvalidDataException('The logged in employee must have a support role to list user cards.');
        }
        if (!empty($cardId) && !empty($userId)) {
            $userCard = $this->userCardRepository->findOneBy(["userId" => $userId, "cardId" => $cardId]);
            return $userCard->toJSON();
        }
        if (!empty($userId)) {
            $params["userId"] = $userId;
        }
        if (!empty($cardId)) {
            $params["cardId"] = $cardId;
        }
        else if(!empty($primaryUserCardId)){
            $params["primaryUserCardId"] = $primaryUserCardId;
        }
        else if(!empty($isPrimaryUserCard)){
            $params["isPrimaryUserCard"] = $isPrimaryUserCard == 1 ? true : false;
        }
        $page = $this->userCardRepository->getAllWithCardPaginated(
            $input->limit,
            $input->offset,
            params: $params
        );

        $page = $page !== null || !empty($page) ? $page->toJSON() : [];

        return $page;
    }
}
