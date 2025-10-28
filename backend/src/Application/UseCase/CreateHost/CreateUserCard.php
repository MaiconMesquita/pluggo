<?php

namespace App\Application\UseCase\CreateUserCard;

use App\Domain\Entity\{UserCard, Auth};
use App\Domain\Entity\Service\CardGenerator\CardGenerator;
use App\Domain\Entity\ValueObject\EmployeeType;
use App\Domain\Entity\ValueObject\UserType;
use App\Domain\Exception\{NotAcceptableException, InternalException, InvalidDataException};
use App\Domain\RepositoryContract\{CardBillingCycleRepositoryContract, CardRepositoryContract, UserCardRepositoryContract, UserRepositoryContract};
use App\Infra\Factory\Contract\{
    RepositoryFactoryContract,
};

class CreateUserCard
{
    private UserCardRepositoryContract         $userCardRepository;
    private UserRepositoryContract          $userRepository;
    private CardRepositoryContract         $cardRepository;
    private CardBillingCycleRepositoryContract         $cardBillingCycleRepository;

    public function __construct(
        RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->userCardRepository    = $repositoryFactory->getUserCardRepository();
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->cardRepository    = $repositoryFactory->getCardRepository();
        $this->cardBillingCycleRepository    = $repositoryFactory->getCardBillingCycleRepository();        
    }


    public function execute(CreateUserCardInput $input): void
    {             
        
        $authType = Auth::getLogged()->getAuthType();
        $cardId = $input->cardId;
        $increaseLimit = true;
        $userId = null;
        if($authType === "user"){        
            $userId = (int) Auth::getLogged()->getUser();
        }elseif($authType === "employee"){
            $employeeType = Auth::getLogged()->getEmployeeType();
            $increaseLimit = $input->increaseLimit;
            $userId = $input->userId;
            if($employeeType === EmployeeType::SUPPORT){                
                if (!empty($userId)) {
                    $user = $this->userRepository->getById($userId);
                    if(!$user)
                    throw new NotAcceptableException('User not found.');
                }else throw new InvalidDataException('The logged in employee needs to enter the user ID to create the card.');
                $userId = $user ? $user->getId() : null;               
            }else throw new InvalidDataException('The logged in employee must have a support role to list user cards.');
        }  

        $user = $this->userRepository->getById($userId);
        
        if ($user->getUserType()->getType() == UserType::LEAD) 
        throw new NotAcceptableException('The informed user did not complete the registration.');  

        if(!$user->getCCBStatus())
        throw new NotAcceptableException('The user did not sign the document to receive the credit.');

        if(!$user->getDueDay() || !$user->getClosingDay()){
            throw new NotAcceptableException('The user has not set an expiration and closing date for the cards.');
        }

        $cardBillingCycle = $this->cardBillingCycleRepository->findOneBy(["dueDay" => $user->getDueDay(), "closingDay" => $user->getClosingDay()]);
        if(!$cardBillingCycle)
        throw new NotAcceptableException('Closing and due dates not found.');

        $card = $this->cardRepository->getById(id: $cardId);
        if(!$card)
        throw new InternalException('Card not found.');

        if ($card->getIsPrimaryCard()) {
            throw new InvalidDataException('You cannot create a master card this way..');
        }

        if (!$card->getPrivate()) {
            throw new InvalidDataException('Only private cards can be created this way..');
        }

        if (!$card->getStatus()) {
            throw new InvalidDataException('The card has deactivated status.');
        }
        
        $userCard = $this->userCardRepository->findOneBy(["userId" => $userId, "cardId" => $cardId]);
            
        if($userCard){            
            throw new NotAcceptableException('The user already has this card.');            
        }

        $primaryUserCardId = null;
        $primaryCardId = $card->getPrimaryCardId();

        if(!empty($primaryCardId)){
            $primaryCard = $this->cardRepository->getById(id: $primaryCardId);

            if(!$primaryCard)
            throw new InternalException('BrandsCard card not found.');

            $primaryUserCard = $this->userCardRepository->findOneBy(["userId" => $userId, "cardId" => $primaryCardId]);

            if(empty($primaryUserCard))
            {          
                $newCard = new CardGenerator();
                $numberCard = $newCard->generateCard();
                try{
                    $primaryUserCard = $this->userCardRepository->create(
                        UserCard::create(
                            cardId: $primaryCardId,
                            userId: $userId,
                            creditLimit: 0,
                            debitLimit: 0,
                            creditBalance: 0,
                            debitBalance: 0,
                            numberCard: $numberCard,
                            isPrimaryUserCard: true,
                            invoiceClosingDate: $user->getClosingDay(),
                            invoiceDueDate: $user->getDueDay(),
                        )                            
                    );                    
                }catch(NotAcceptableException){
                    throw new NotAcceptableException('Error creating BrandsCard card.');
                }
            }
            // elseif($increaseLimit){                
            //     //
            //     $debitLimit = $primaryUserCard->getDebitLimit() + $card->getDebitLimit();
            //     $creditLimit = $primaryUserCard->getCreditLimit() + $card->getCreditLimit();
            //     $primaryUserCard->setCreditLimit($creditLimit);
            //     $primaryUserCard->setDebitLimit($debitLimit);
            //     try{
            //         $this->userCardRepository->update($primaryUserCard);
            //     }catch(NotAcceptableException){
            //         throw new NotAcceptableException('Error creating primary card.');
            //     }
            // }
            // $primaryUserCardId = $primaryUserCard->getId();
            $primaryUserCardId = $primaryUserCard->getId();
        }
        
        $newCard = new CardGenerator();
        $numberCard = $newCard->generateCard();

        try{
            $userCard = $this->userCardRepository->create(
                UserCard::create(
                    cardId: $cardId,
                    userId: $userId,
                    creditLimit: 0,
                    debitLimit: 0,
                    creditBalance: 0,
                    debitBalance: 0,
                    primaryUserCardId: $primaryUserCardId,
                    numberCard: $numberCard,
                    invoiceClosingDate: $user->getClosingDay(),
                    invoiceDueDate: $user->getDueDay(),
                )                            
            );
        }catch(NotAcceptableException){
            throw new NotAcceptableException('Error creating card.');
        }
    }
}
