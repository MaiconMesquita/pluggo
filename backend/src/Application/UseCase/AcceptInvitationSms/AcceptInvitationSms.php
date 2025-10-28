<?php

namespace App\Application\UseCase\AcceptInvitationSms;

use App\Domain\Entity\{
    Auth,
    UserEstablishment
};
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\ValueObject\ScoreType;
use App\Domain\Entity\ValueObject\SmsType;
use App\Domain\Exception\{
    InternalException,
    InvalidDataException,
    NotAcceptableException
};
use App\Domain\RepositoryContract\{
    CardRepositoryContract,
    EstablishmentRepositoryContract,
    ScoreLevelRepositoryContract,
    SmsHistoryRepositoryContract,
    UserCardRepositoryContract,
    UserEstablishmentRepositoryContract,
    SegmentRepositoryContract,
    UserRepositoryContract
};
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Infra\Repository\ScoreLevelRepository;

class AcceptInvitationSms
{
    private SegmentRepositoryContract $segmentRepository;
    private UserEstablishmentRepositoryContract $userEstablishmentRepository;
    private EstablishmentRepositoryContract $establishmentRepository;
    private UserCardRepositoryContract $userCardRepository;
    private CardRepositoryContract $cardRepository;
    private SmsHistoryRepositoryContract $smsHistoryRepository;
    private UserRepositoryContract $userRepository;
    private ScoreLevelRepositoryContract $scoreLevelRepository;

    public function __construct(
        private RepositoryFactoryContract $repositoryFactory,
    ) {
        $this->segmentRepository = $repositoryFactory->getSegmentRepository();
        $this->userEstablishmentRepository = $repositoryFactory->getUserEstablishmentRepository();
        $this->userCardRepository = $repositoryFactory->getUserCardRepository();
        $this->cardRepository = $repositoryFactory->getCardRepository();
        $this->smsHistoryRepository = $repositoryFactory->getSmsHistoryRepository();
        $this->establishmentRepository = $repositoryFactory->getEstablishmentRepository();
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->scoreLevelRepository = $repositoryFactory->getScoreLevelRepository();
    }

    public function execute(AcceptInvitationSmsInput $input)
    {
        $currentUserId = (int) Auth::getLogged()->getUser();

        $user = $this->userRepository->getById($currentUserId);
        if (!$user->getCCBStatus())
            throw new NotAcceptableException('The user did not sign the document to receive the credit.');

        $smsId = (int) $input->smsId;
        $sms = $this->smsHistoryRepository->getById($smsId);

        if (!$sms)
            throw new InvalidDataException("Sms not found.");

        if (!$sms->getStatus())
            throw new NotAcceptableException('SMS has already been used');

        if ($sms->getDeactivationStatus())
            throw new NotAcceptableException('SMS disabled, cannot accept billing');

        if ($sms->getType() !== SmsType::INVITATION)
            throw new NotAcceptableException('The type of sms is incompatible.');

        if ($sms->getUserId() !== $currentUserId)
            throw new InvalidDataException("It is not possible to accept an invitation from another user.");

        $acceptInvitation = (bool) $input->acceptInvitation;

        $establishmentId = $sms->getEstablishmentId();
        $establishment = $this->establishmentRepository->getById($establishmentId);
        if (!$establishment)
            throw new InvalidDataException("Establishment not found.");

        $clients = $this->userEstablishmentRepository->countAll(params: ["establishmentId" => $establishmentId]);

        if ($clients >= $establishment->getCustomerLimit())
            throw new NotAcceptableException('The establishment the user wants to associate with has already exceeded the maximum number of customers.');

        if ($acceptInvitation && $sms->getExpirationDate() >= DateTimeOffset::getAdjustedDateTime()) {
            $numberOfAssociatedEstablishments = $this->userEstablishmentRepository->countAll(
                params: ["userId" => $currentUserId]
            );

            $numberOfSegments = $this->segmentRepository->countAll();

            $cnaeMccId = $establishment->getCnaeMccId();
            if (!$cnaeMccId)
                throw new NotAcceptableException('Cnae Mcc not found.');

            $card = $this->cardRepository->getCardByCnaeMccId($cnaeMccId);
            if (!$card)
                throw new InternalException('Card not found.');

            $cardId = $card->getId();
            $segmentId = $card->getSegmentId();

            if ($numberOfAssociatedEstablishments >= $numberOfSegments)
                throw new NotAcceptableException('The user cannot be associated with more than two stores.');

            $userCard = $this->userCardRepository->findOneBy(["userId" => $currentUserId, "cardId" => $cardId]);

            if (!$userCard) {
                throw new NotAcceptableException('Unable to create the card for the logged-in user.');
            }
            // alterar busca para filtrar por mais de um campo
            $userEstablishment = $this->userEstablishmentRepository->findOneBy([
                "establishmentId" => $establishmentId,
                "userId" => $currentUserId,
                "userCardId" => $userCard->getId()
            ]);

            if (!$userEstablishment) {
                //CRIAR LÓGICA PARA ADICIONAR SALDO NOS CARTÕES QUANDO FOR O PRIMEIRO ESTABELECIMENTO DO SEGMENTO

                $userEstablishment = $this->userEstablishmentRepository->findByUserIdAndSegmentId(
                    userId: $currentUserId,
                    segmentId: $segmentId
                );
                //LÓGICA DE LIMITE POR ESTABELECIMENTO DE UM SEGMENTO
                if (!empty($userEstablishment)) {
                    throw new NotAcceptableException('The logged in user has a link with an establishment in this segment.');
                }

                if ($sms->getDiscount()) {
                    $userCard = $this->userCardRepository->findOneBy(["userId" => $currentUserId, "cardId" => $cardId]);
                    //aqui

                    if ($establishment->getIsPackagePlan()) {
                        $scoreLevel = $this->scoreLevelRepository->getByScore($user->getFinancialScore());
                        $scoreCreditLimit = $scoreLevel->getCreditLimit();

                        $userCard->setCreditLimit($scoreCreditLimit); //limite do cartão
                        $userCard->setCreditBalance($scoreCreditLimit); //dando saldo equivalente ao limite do cartão
                        $userCard->setDebitLimit($scoreCreditLimit);
                        $user->setScoreLevel($scoreLevel->getId());
                    } else {
                        $scoreLevel = $this->scoreLevelRepository->findOneBy(['levelName' => ScoreType::LOW]);
                        $scoreCreditLimit = $scoreLevel->getCreditLimit();

                        $userCard->setCreditLimit($scoreCreditLimit); //limite do cartão
                        $userCard->setCreditBalance($scoreCreditLimit); //dando saldo equivalente ao limite do cartão
                        $userCard->setDebitLimit($scoreCreditLimit);
                        $user->setScoreLevel($scoreLevel->getId());
                    }

                    $availableCreditBalance = $establishment->getAvailableCreditBalance() - $scoreCreditLimit;

                    if ($availableCreditBalance < 0) {
                        throw new NotAcceptableException("Error the establishment does not have a balance available to create a relationship with the user plus credit");
                    }

                    $establishment->setAvailableCreditBalance($availableCreditBalance);

                    $this->establishmentRepository->update($establishment);

                    try {
                        $this->userRepository->update($user);
                        $userCard = $this->userCardRepository->update($userCard);
                    } catch (NotAcceptableException) {
                        throw new NotAcceptableException('Error creating primary card.');
                    }

                    $primaryCardId = $card->getPrimaryCardId();
                    if (!empty($primaryCardId)) {
                        $primaryUserCard = $this->userCardRepository->findOneBy(["userId" => $currentUserId, "cardId" => $primaryCardId]);


                        $creditLimit = $primaryUserCard->getCreditBalance() + $scoreCreditLimit;
                        $primaryUserCard->setCreditBalance($creditLimit);
                        $primaryUserCard->setCreditLimit($creditLimit);
                        try {
                            $this->userCardRepository->update($primaryUserCard);
                        } catch (NotAcceptableException) {
                            throw new NotAcceptableException('Error creating primary card.');
                        }
                    }
                } else {
                    $userCard = $this->userCardRepository->findOneBy(["userId" => $currentUserId, "cardId" => $cardId]);

                    // Criar o cartão com limites fixos em 250
                    $scoreLevel = $this->scoreLevelRepository->findOneBy(['levelName' => ScoreType::LOW]);
                    $scoreCreditLimit = $scoreLevel->getCreditLimit();

                    $userCard->setCreditLimit($scoreCreditLimit); //limite do cartão
                    $userCard->setCreditBalance($scoreCreditLimit); //dando saldo equivalente ao limite do cartão
                    $userCard->setDebitLimit($scoreCreditLimit);
                    $user->setScoreLevel($scoreLevel->getId());

                    try {
                        $userCard = $this->userCardRepository->update($userCard);
                        $this->userRepository->update($user);
                    } catch (NotAcceptableException) {
                        throw new NotAcceptableException('Error creating card with fixed limits.');
                    }

                    $primaryCardId = $card->getPrimaryCardId();
                    if (!empty($primaryCardId)) {
                        $primaryUserCard = $this->userCardRepository->findOneBy(["userId" => $currentUserId, "cardId" => $primaryCardId]);


                        $creditLimit = $primaryUserCard->getCreditBalance() + $scoreCreditLimit;
                        $primaryUserCard->setCreditBalance($creditLimit);
                        $primaryUserCard->setCreditLimit($creditLimit);
                        try {
                            $this->userCardRepository->update($primaryUserCard);
                        } catch (NotAcceptableException) {
                            throw new NotAcceptableException('Error creating primary card.');
                        }
                    }

                    // Atualiza saldo do estabelecimento
                    $availableCreditBalance = $establishment->getAvailableCreditBalance() - $scoreCreditLimit;
                    if ($availableCreditBalance < 0) {
                        throw new NotAcceptableException("Error: establishment does not have enough balance to create this user card.");
                    }
                    $establishment->setAvailableCreditBalance($availableCreditBalance);
                    $this->establishmentRepository->update($establishment);
                }

                $userEstablishment = $this->userEstablishmentRepository->create(UserEstablishment::create(
                    establishmentId: $establishmentId,
                    userCardId: $userCard->getId(),
                    userId: $currentUserId,
                ));
            } else {
                throw new NotAcceptableException('The logged in user is already a customer of this establishment.');
            }
        }

        $sms = $this->smsHistoryRepository->removeById($smsId);
    }
}
