<?php

namespace App\Application\UseCase\AcceptChargeNew;

use App\Domain\Entity\Service\WithdrawalService\WithdrawalService;
use App\Domain\Entity\Auth;
use App\Domain\Entity\MerchantWithdrawalHistory;
use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use App\Domain\Entity\Service\FinancialSummaryAggregator\FinancialSummaryAggregatorService;
use App\Domain\Entity\Service\InvoiceGenerator\InvoiceGenerator;
use App\Domain\Entity\Service\InvoiceGenerator\InvoiceGeneratorInput;
use App\Domain\Entity\Service\TaxCalculator\TaxCalculator;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\{Amount, AnticipationType, EmployeeType, InvoiceStatusType, TransactionStatusType, TransactionType};
use App\Domain\Exception\{
    InternalException,
    InvalidDataException,
    NotAcceptableException
};
use App\Domain\RepositoryContract\{
    CardRepositoryContract,
    BaseFeesRepositoryContract,
    EmployeeEstablishmentRepositoryContract,
    EmployeeRepositoryContract,
    EstablishmentRepositoryContract,
    InvoiceRepositoryContract,
    SupplierRepositoryContract,
    TransactionRepositoryContract,
    UserCardRepositoryContract,
    UserEstablishmentRepositoryContract,
    UserRepositoryContract
};
use App\Infra\Factory\Contract\RepositoryFactoryContract;
use App\Infra\Repository\MerchantWithdrawalHistoryRepository;
use DateTime;
use Ramsey\Uuid\Uuid;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;
use Error;

class AcceptChargeNew
{

    private MerchantWithdrawalHistoryRepository $merchantWithdrawalHistoryRepository;
    private UserEstablishmentRepositoryContract $userEstablishmentRepository;
    private TransactionRepositoryContract $transactionRepository;
    private UserCardRepositoryContract $userCardRepository;
    private UserRepositoryContract $userRepository;
    private InvoiceRepositoryContract $invoiceRepository;
    private EstablishmentRepositoryContract $establishmentRepository;
    private EmployeeEstablishmentRepositoryContract $employeeEstablishmentRepository;
    private InvoiceGenerator $invoiceCreator;
    private CardRepositoryContract $cardRepository;
    private TaxCalculator $taxCalculator;
    private BaseFeesRepositoryContract $baseFeesRepository;
    private SupplierRepositoryContract $supplierRepository;
    private FinancialSummaryAggregatorService $financialsummary;
    private WithdrawalService $withdrawalService;



    public function __construct(
        private RepositoryFactoryContract $repositoryFactory,
        private ThirdPartyFactoryContract $thirdPartyFactoryContract,
    ) {
        $this->merchantWithdrawalHistoryRepository = $repositoryFactory->getMerchantWithdrawalHistoryRepository();
        $this->userEstablishmentRepository = $repositoryFactory->getUserEstablishmentRepository();
        $this->transactionRepository = $repositoryFactory->getTransactionRepository();
        $this->userCardRepository = $repositoryFactory->getUserCardRepository();
        $this->userRepository = $repositoryFactory->getUserRepository();
        $this->invoiceRepository = $repositoryFactory->getInvoiceRepository();
        $this->establishmentRepository = $repositoryFactory->getEstablishmentRepository();
        $this->employeeEstablishmentRepository = $repositoryFactory->getEmployeeEstablishmentRepository();
        $this->invoiceCreator = new InvoiceGenerator($repositoryFactory, $thirdPartyFactoryContract);
        $this->cardRepository = $repositoryFactory->getCardRepository();
        $this->taxCalculator = new TaxCalculator();
        $this->baseFeesRepository = $repositoryFactory->getBaseFeesRepository();
        $this->supplierRepository = $repositoryFactory->getSupplierRepository();
        $this->financialsummary = new FinancialSummaryAggregatorService($repositoryFactory); // Armazena a instância
        $this->withdrawalService = new WithdrawalService($repositoryFactory); // Armazena a instância

    }

    public function execute(AcceptChargeNewInput $input)
    {


        $currentUserId = (int) Auth::getLogged()->getUser();

        $user = $this->userRepository->getById($currentUserId);
        if (!$user->getCCBStatus())
            throw new NotAcceptableException('The user did not sign the document to receive the credit.');

        if ($user->getDeviceId() != $input->deviceId)
            throw new NotAcceptableException('The user deviceId is not valid.');

        $acceptTransaction = (bool) $input->acceptTransaction;

        $currentDate = DateTimeOffset::getAdjustedDateTime(); // Data e hora atual

        if ($acceptTransaction) {
            $alias = $input->establishmentId; // aqui vem o alias
            if (!$alias || trim($alias) === '') {
                throw new InvalidDataException("Alias do estabelecimento não está definido.");
            }

            $establishment = $this->establishmentRepository->getByAlias($alias);
            if (!$establishment) {
                throw new InvalidDataException("Estabelecimento não encontrado para o alias informado.");
            }

            $transactionValue = $input->amount;
            $signature = $input->signature;
            $establishmentId = $establishment->getId();
            $employeeId = $establishment->getOwnerId();

            $payload = [
                "amount" => $input->amount,
                "description" => $input->description,
                "establishmentId" => $alias,
                "businessName" => $establishment->getBusinessName(),
                "installmentCount" => $establishment->getMaximumNumberInstallments(),
                "isPackagePlan" => $input->isPackagePlan,
                "uniqueId" => $input->uniqueId,
            ];

            $calculatedSignature = hash_hmac('sha256', json_encode($payload), $establishment->getSecretKeyForSells());
            if (!hash_equals($calculatedSignature, $signature)) {
                throw new InvalidDataException("QR Code data is invalid or has been tampered with.");
            }

            $card = $this->cardRepository->getCardByCnaeMccId($establishment->getCnaeMccId());
            if (!$card)
                throw new InternalException('Card not found.');

            $cardId = $card->getId();

            $userCard = $this->userCardRepository->findOneBy(["userId" => $currentUserId, "cardId" => $cardId]);

            if (!$userCard)
                throw new InvalidDataException("User card was not found");

            $userCardId = $userCard->getId();

            $existingTransaction = $this->transactionRepository->findOneBy([
                "userCardId" => $userCard?->getId(),
                "signature" => $signature
            ]);

            if ($existingTransaction) {
                throw new NotAcceptableException(
                    "Já existe uma transação registrada com este signature. Possível tentativa de pagamento duplicado."
                );
            }

            $brands = $this->establishmentRepository->findOneBy(["cnpj" => "43312654000163"]);
            if (!$brands) {
                throw new InvalidDataException('BrandsCard registration via cnpj was not found.');
            }

            if (!$establishment)
                throw new InvalidDataException("Establishment not found.");

            if (!$establishment->getStatus())
                throw new NotAcceptableException('The establishment responsible for creating the transaction is deactivated');



            //parte de split
            $split = $establishment->getSplitPercentage();
            $splitStatus = $establishment->getSplitStatus();

            //vendo se o estabelecimento tem um vinculo com o usuário em questão
            $userEstablishment = $this->userEstablishmentRepository->findOneBy([
                "establishmentId" => $establishmentId,
                "userId" => $currentUserId,
                "userCardId" => $userCard->getId()
            ]);

            if (!$userEstablishment)
                throw new NotAcceptableException('The logged in user was not correctly linked to their card and the establishment');

            if (!$userEstablishment->getStatus())
                throw new NotAcceptableException('The user associated with the establishment is deactivated.');

            //adicionando valor da transação no amouttoreceive
            $pendingBalance = $establishment->getAmountToReceive() + $transactionValue;
            if ($pendingBalance < 0) {
                throw new NotAcceptableException("Error the establishment's outstanding balance cannot be negative");
            }

            //encontrando o cartão brands do usuário
            $primaryUserCard = null;
            $primaryUserCardId = $userCard->getPrimaryUserCardId();


            if (!empty($primaryUserCardId)) {
                $primaryUserCard = $this->userCardRepository->getById($primaryUserCardId);
            } else {
                $isPrimaryUserCard = $userCard->getIsPrimaryUserCard();
                if (!$isPrimaryUserCard) {
                    throw new NotAcceptableException('The card provided is not a primary card.');
                }
                $primaryUserCard = $userCard;
            }

            // Validação do status
            if (!$primaryUserCard->getStatus()) {
                throw new NotAcceptableException("User's card is blocked.");
            }

            $invoiceInput = new InvoiceGeneratorInput(
                userCard: $primaryUserCard,
            );

            //definindo o fonecedor que vai fazer o split
            $supplierEstablishment = null;
            $supplier = null;
            $supplierRelationship = null;

            if ($split && $splitStatus) {
                $supplierRelationship = $this->employeeEstablishmentRepository->findOneBy(
                    [
                        "employeeId" => $establishmentId,
                        "isSupplierEmployee" => true,
                        "establishmentOwnerStatus" => false,
                        "status" => true
                    ]
                );

                if (!$supplierRelationship) {
                    throw new InvalidDataException('The supplier-type establishment does not have an active relationship with an employee.');
                }
                $supplierEstablishment = $this->supplierRepository->getById($supplierRelationship->getEstablishmentId()); //usar aqui o supplier
                if (!$supplierEstablishment) {
                    throw new InvalidDataException('Supplier establishment not found.');
                }
                if (!$supplierEstablishment->getStatus()) {
                    throw new NotAcceptableException('The supplier establishment found is deactivated.');
                }

                $isCpf = $supplierEstablishment->getCpf();
                $isCnpj = $supplierEstablishment->getCnpj();

                if (!$isCpf && !$isCnpj) {
                    throw new InvalidDataException('Invalid document length. Must be either CPF (11 digits) or CNPJ (14 digits).');
                }

                $supplier = $supplierEstablishment;
            }

            $invoice = $this->invoiceCreator->getOrCreateMostRecentInvoice($invoiceInput);

            if ($invoice->getStatus() == InvoiceStatusType::BLOCKED) {
                throw new NotAcceptableException("User's invoice is blocked and negotiation is required.");
            }

            //adicionando no cartão do usuário o valor da transação
            $userCardPendingBalance = $userCard->getPendingBalance() + $transactionValue;

            if ($invoice->getCreditLimit() < $userCardPendingBalance) {
                throw new NotAcceptableException("The invoice limit amount has been exceeded by the outstanding credit amount plus the transaction..");
            }

            if ($invoice->getCreditBalance() < $transactionValue) {
                throw new NotAcceptableException("The transaction amount exceeds the available invoice credit amount.");
            }

            if ($userCard->getCreditBalance() < $transactionValue) {
                throw new NotAcceptableException("The transaction amount exceeds the credit available on the card.");
            }

            // Calcula o novo saldo pendente do invoice
            $totalInvoiceCredit = $invoice->getCreditBalance() - $transactionValue;
            $totalUserCardCredit = $userCard->getCreditBalance() - $transactionValue;

            //resgatando o dia de fechamento da fatura
            $closingDate = $invoice->getClosingDate(); // DateTimeInterface
            if (!$closingDate instanceof \DateTime) {
                $closingDate = \DateTime::createFromInterface($closingDate);
            }

            $referenceDate = (clone $closingDate)->modify("first day of this month");            // Gerar um hash único para identificar a compra
            $purchaseHash = substr(hash('sha256', Uuid::uuid4() . $userCard->getId() . microtime()), 0, 16);

            $description = $input->description;
            $installment = $input->installmentCount;

            $pending = new TransactionStatusType("pending");
            $transactionType = new TransactionType("credit");

            $invoiceId = $invoice->getId();

            $invoiceOriginalInstallment = 0;
            $invoiceBillingFeeAmountToPay = 0;
            //total de taxa de captura
            $totalCaptureFee = 0;
            //total de taxa de split
            $totalSplitDiscount = 0;
            //total de taxa de split
            $totalDiscountedByAnticipationFee = 0;
            //total de taxa do cartão
            $totalBillingFeeAmountToPay = 0;
            //taxa do cartão fatura atual
            $currentInvoiceCardFee = 0;
            $totalAutomaticAdvance = 0;
            $totalAvailableWithdrawalAmount = 0;

            //aqui ve se o estabelecimento é automatico ou pontual
            $automaticStatusAnticipation = $establishment->getAutomaticStatusAnticipation();

            //taxas bases
            $baseCatchFee = $this->baseFeesRepository->findOneBy(["type" => "CAPTURE_FEE"]);
            $baseCardFee = $this->baseFeesRepository->findOneBy(["type" => "CARD_FEE"]);
            $baseAutomaticAdvanceRates = $this->baseFeesRepository->findOneBy(["type" => "AUTOMATIC_ADVANCE"]);

            $baseCardFeePercentage = $baseCardFee->getBasePercentage();

            // Recupera as taxas para a parcela atual das entidades taxas
            $establishmentCatchFee = $establishment->getCaptureCardRate();
            $establishmentCatchFee = ($establishmentCatchFee == 0.0) ? null : $establishmentCatchFee; //se for 0.00 considera null

            $establishmentAdvanceFee = $establishment->getAdvanceRate();
            $establishmentAdvanceFee = ($establishmentAdvanceFee == 0.0) ? null : $establishmentAdvanceFee;

            $baseAutomaticAdvancePercentage = $establishmentAdvanceFee ?? ($baseAutomaticAdvanceRates ? $baseAutomaticAdvanceRates->getBasePercentage() : 0);
            $catchRate = $establishmentCatchFee ?? ($baseCatchFee ? $baseCatchFee->getBasePercentage() : 0);
            $cardFeeRate = $this->taxCalculator->calculateInstallmentAverageRate($baseCardFeePercentage, $installment); // Taxa do cartão
            $automaticAdvanceRate = $this->taxCalculator->calculateInstallmentAverageRate($baseAutomaticAdvancePercentage, $installment); // Taxa de antecipação
            $totalCardFee = 0;

            $totalCatchRate = $this->taxCalculator->calculateFee( //valor do desconto
                $input->amount,
                $catchRate,
                false // retorna o valor do desconto
            );

            // Calcula o valor base por parcela, aqui ele define como vai ficar a divisão de valores de cada parcela pra depois calcular o valor com as taxas
            $netAmount = $transactionValue - $totalCatchRate;

            $baseInstallmentValue = floor(($transactionValue * 100) / $installment) / 100;
            $remainingValue = $transactionValue - ($baseInstallmentValue * $installment); // Diferença restante

            $baseInstallmentValueForManagement = floor(($netAmount * 100) / $installment) / 100;

            $totalGrossAvailable = 0; // vai para grossAvailable
            $totalFutureReceivables = 0;
            try {
                $entityManager = Doctrine::getInstance()->getEntityManager();

                $entityManager->getConnection()->beginTransaction();

                $halfwayInstallment = (int) ceil($installment / 2);

                for ($i = 0; $i < $installment; $i++) {
                    $originalInstallment = $baseInstallmentValue;
                    $isAnticipated = false;
                    $anticipationDate = null;
                    // Adiciona a diferença restante à primeira parcela
                    if ($i === 0) {
                        $originalInstallment += $remainingValue;
                    }

                    $installmentCatchRate = $this->taxCalculator->calculateFee( //valor do desconto
                        $originalInstallment,
                        $catchRate,
                        false // retorna o valor do desconto
                    );

                    //valor pós desconto
                    $installmentAfterCatchRate = round($originalInstallment - $installmentCatchRate, 2);


                    // Passo 2: Calcula e aplica a taxa de cartão no valor original
                    $installmentCardFee = $this->taxCalculator->calculateFee(
                        $originalInstallment,
                        $cardFeeRate,
                        false // retorna o valor ja calculado
                    );

                    $installmentWithCardFee = round($originalInstallment + $installmentCardFee, 2);

                    // Passo 3: Subtrai a taxa de antecipação automática (se habilitado)
                    //OBS: só existe automática aqui porque se não for automática vai acontecer em outro momento com o EC solicitando
                    $automaticAdvance = 0;
                    $installmentSplit = 0;

                    if ($establishment->getIsPackagePlan() && $input->isPackagePlan) {
                        if ($i < $halfwayInstallment) {
                            $totalGrossAvailable += $installmentAfterCatchRate;
                        } else {
                            $totalFutureReceivables += $installmentAfterCatchRate;
                        }
                    } else {
                        $totalGrossAvailable += $installmentAfterCatchRate;
                    }

                    if ($automaticStatusAnticipation) { //aqui ele ta aplicando só quando é automático, no caso joariamos pra fora dessa validação só para atualizar o grossavailable e o future

                        if ($establishment->getIsPackagePlan() && $input->isPackagePlan) {
                            if ($i < $halfwayInstallment) {
                                $isAnticipated = true;
                                $anticipationDate = $currentDate;
                            }
                        } else {
                            $isAnticipated = true;
                            $anticipationDate = $currentDate;
                        }

                        $automaticAdvance = $this->taxCalculator->calculateFee(
                            $installmentAfterCatchRate,
                            $automaticAdvanceRate,
                            false
                        );


                        $installmentAfterCatchRate -= $automaticAdvance;
                        // Passo 4: Calcula e aplica a taxa de split no valor descontado

                        if ($split && $splitStatus) {
                            //calculando o valor da taxa de split sobre o valor pós taxa de captura e antecipação
                            $installmentSplit = $this->taxCalculator->calculateFee(
                                $installmentAfterCatchRate,
                                $split,
                                false
                            );
                        }
                        $installmentAfterCatchRate = round($installmentAfterCatchRate - $installmentSplit, 2);
                    }

                    if ($isAnticipated) {
                        $totalAvailableWithdrawalAmount += $installmentAfterCatchRate;
                        $totalAutomaticAdvance += $automaticAdvance;
                        $totalSplitDiscount += $installmentSplit;
                        $totalDiscountedByAnticipationFee += $automaticAdvance;
                    }

                    $totalBillingFeeAmountToPay += $installmentWithCardFee;
                    //$totalSplitDiscount += $installmentSplit;


                    //captura apenas da primeira parcela do loop
                    if ($i === 0) {
                        $invoiceOriginalInstallment = $originalInstallment;
                        $invoiceBillingFeeAmountToPay = $installmentWithCardFee;
                        $currentInvoiceCardFee = $installmentCardFee;
                    }

                    // Calcula o mês e ano para esta parcela com base na referência
                    $installmentDate = (clone $referenceDate)->modify("+$i month");
                    $month = (int)$installmentDate->format('m');
                    $year = (int)$installmentDate->format('Y');
                    $totalCardFee += $installmentCardFee;
                    // Cria a parcela com o valor ajustado
                    $this->transactionRepository->create(Transaction::create(
                        status: $pending,
                        userCardId: $userCardId,
                        description: $description,
                        signature: $signature,
                        transactionType: $transactionType,
                        originalAmount: $transactionValue,
                        originalInstallment: $originalInstallment,
                        advanceFee: $automaticAdvance,
                        invoiceNumber: $i + 1, // Corrige o número da parcela para começar em 1
                        availableWithdrawalAmount: $installmentAfterCatchRate,
                        installmentCount: $installment,
                        captureFee: $totalCatchRate,
                        splitAmount: $installmentSplit,
                        billingFeeAmountToPay: $installmentWithCardFee,
                        anticipationDate: $anticipationDate,
                        anticipationStatus: $isAnticipated,
                        cardFee: $installmentCardFee,
                        purchaseHash: $purchaseHash, // Adicionado o hash da compra
                        establishmentId: $establishmentId,
                        invoiceId: $i === 0 ? $invoiceId : null, // Vincula apenas a primeira parcela à fatura
                        month: $month,
                        year: $year,
                        cnaeMccId: $establishment->getCnaeMccId(),
                        isPackagePlan: $input->isPackagePlan ?? false
                    ));
                }

                if ($automaticStatusAnticipation) {
                    $supplierRelationship = $split && $establishment->getSplitStatus()
                        ? $this->employeeEstablishmentRepository->findOneBy([
                            "employeeId" => $establishmentId,
                            "isSupplierEmployee" => true,
                            "establishmentOwnerStatus" => false,
                            "status" => true
                        ])
                        : null;

                    $supplierEstablishment = $supplierRelationship
                        ? $this->supplierRepository->getById($supplierRelationship->getEstablishmentId())
                        : null;

                    $supplier = $supplierEstablishment;

                    $this->withdrawalService->handleAnticipation(
                        currentDate: $currentDate,
                        establishmentId: $establishmentId,
                        employeeId: $employeeId,
                        transactionValue: $transactionValue,
                        totalAvailableWithdrawalAmount: $totalAvailableWithdrawalAmount,
                        totalDiscountedByAnticipationFee: $totalDiscountedByAnticipationFee,
                        totalCaptureFee: $totalCatchRate,
                        totalSplitDiscount: $totalSplitDiscount,
                        pixKey: $establishment->getCnpj() ?: $establishment->getCpf(),
                        anticipationType: AnticipationType::AUTOMATIC_ADVANCE,
                        split: $split,
                        splitStatus: $establishment->getSplitStatus(),
                        supplier: $supplier,
                        supplierEstablishment: $supplierEstablishment,
                        supplierRelationship: $supplierRelationship
                    );
                } else {
                    //se caso não houver antecipaçãoa automática só atuaiza as informações do EC

                    //$amountToReceive = $establishment->getAmountToReceive() + $transactionValue;

                    $establishment->setCaptureFee($establishment->getCaptureFee() + $totalCatchRate);
                    $establishment->setAmountToReceive($establishment->getAmountToReceive() + $netAmount); //inputa o valor total - a taxa de captura

                    $this->establishmentRepository->update($establishment);
                }

                // Atualiza o invoice e o cartão do usuário
                $userCardPendingBalanceFee = $totalBillingFeeAmountToPay + $userCard->getPendingBalanceFee();
                $userCard->setPendingBalance($userCardPendingBalance);
                $userCard->setPendingBalanceFee($userCardPendingBalanceFee);
                $userCard->setCreditBalance($totalUserCardCredit);

                $this->userCardRepository->update($userCard);

                if (!empty($primaryUserCard)) {
                    //se o houver o cartão primário ele atualiza as informações desse cartão também
                    $primaryUserCardPendingBalance = $primaryUserCard->getPendingBalance() + $transactionValue;
                    $primaryUserCard->setPendingBalance($primaryUserCardPendingBalance);

                    $primaryUserCardPendingBalanceFee = $primaryUserCard->getPendingBalanceFee() + $totalBillingFeeAmountToPay;
                    $primaryUserCard->setPendingBalanceFee($primaryUserCardPendingBalanceFee);

                    $totalPrimaryUserCardCredit = $primaryUserCard->getCreditBalance() - $transactionValue;
                    $primaryUserCard->setCreditBalance($totalPrimaryUserCardCredit);

                    $this->userCardRepository->update($primaryUserCard);
                }
                //atualiza a fatura
                $invoice->setOutstandingBalance($invoice->getOutstandingBalance() + $invoiceOriginalInstallment);
                $invoice->setBalanceWithFee($invoice->getBalanceWithFee() + $invoiceBillingFeeAmountToPay);
                $invoice->setCardFeeRate($invoice->getCardFeeRate() + $currentInvoiceCardFee);
                $invoice->setBilledAmount($invoice->getBilledAmount() + $invoiceBillingFeeAmountToPay);

                $invoice->setCreditBalance($totalInvoiceCredit);
                $this->invoiceRepository->update($invoice);

                // Atualiza o estabelecimento
                $brands->setAmountToReceive($brands->getAmountToReceive() + $totalCaptureFee + $totalAutomaticAdvance);

                $this->establishmentRepository->update($brands);


                $values = [
                    'grossToReceive'    => $netAmount,
                    'grossAmount'    => $input->amount,
                    'grossAvailable'    => $totalGrossAvailable,
                    'futureReceivables' => $totalFutureReceivables,
                    'cardFee'           => $totalCardFee,
                    'captureFee'        => $totalCatchRate,
                    'anticipationFee'   => $totalAutomaticAdvance, // nome correto
                    'installmentCount'  => $installment,
                    'systemRevenue'     => $totalCardFee + $totalCatchRate + $totalAutomaticAdvance,
                    'establishmentRevenue'     => $totalCardFee + $totalCatchRate + $totalAutomaticAdvance,
                    'transactionCount'  => 1,
                ];

                $this->financialsummary->updateSummaries($values, $establishmentId);


                //eu penso em aqui buscar o summario só do dono do estabelecimento que esta logado e atualizar os 3 campos faltantes só dele

                $entityManager->flush(); // garante que todas as entidades persistidas sejam salvas
                $entityManager->getConnection()->commit();
                return [

                    "installmentCount" => $installment,
                    "amountPerInstallment" => $installmentWithCardFee,
                    "createdAt" => $currentDate->format('Y-m-d H:i:s')
                ];
            } catch (InternalException) {
                $entityManager->getConnection()->rollBack();
                throw new InternalException();
            }
        }
    }
}
