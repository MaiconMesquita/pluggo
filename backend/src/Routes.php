<?php

use Slim\Factory\AppFactory;
use App\Infra\Database\Doctrine;
use App\Infra\Controller\HttpMethods;
use App\Infra\ThirdParty\Http\SlimHttpAdapter;
use App\Infra\Factory\{ThirdPartyFactory, RepositoryFactoryMySQL};
use App\Application\Middleware\{BearerAuth, BasicAuth, ApiKeyAuth, FlexibleAuth};

$app = new SlimHttpAdapter(AppFactory::create());

$bearerAuthForEmployee = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['employee']
);

$bearerAuthForUser = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['user']
);

$bearerAuthForEstablishment = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['establishment']
);

$bearerAuthForSupplier = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['supplier']
);

$bearerAuthForBoth = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['user', 'employee']
);

$bearerAuthForEcAndEm = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['establishment', 'employee']
);

$bearerAuthForAll = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['driver', 'host']
);


$basicAuthMiddleware = new BasicAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance())
);

$apiKeyAuthMiddleware = new ApiKeyAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance())
);

$flexibleAuth = new FlexibleAuth(
    $bearerAuthForEmployee, // BearerAuth
    $apiKeyAuthMiddleware   // ApiKeyAuth
);

$app->group(
    '/driver',
    function (SlimHttpAdapter $app) use ($apiKeyAuthMiddleware, $bearerAuthForEmployee, $flexibleAuth) {
        $app->on(
            HttpMethods::POST,
            '/create',
            new App\Infra\Factory\CreateRepresentativeEmployeeFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '/establishment',
            new App\Infra\Factory\ListEmployeeEstablishmentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '/{id}',
            new App\Infra\Factory\FindEmployeeByIdFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\CreateAnEmployeeFactory,
            [$flexibleAuth]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListEmployeeFactory,
            [$bearerAuthForEmployee]
        );
    }
);

$app->group(
    '/device',
    function (SlimHttpAdapter $app) use ($bearerAuthForAll) {
        $app->on(
            HttpMethods::POST,
            '/tracking',
            new App\Infra\Factory\DeviceTrackingFactory,
            []
        );
    }
);

$app->group(
    '/sms',
    function (SlimHttpAdapter $app) use ($bearerAuthForUser, $bearerAuthForEmployee, $bearerAuthForBoth, $bearerAuthForAll, $bearerAuthForEcAndEm) {
        $app->on(
            HttpMethods::PUT,
            '/accept/invitation/{id}',
            new App\Infra\Factory\AcceptInvitationSmsFactory,
            [$bearerAuthForUser]
        );
        $app->on(
            HttpMethods::PUT,
            '/accept/invitation/branded-card/{id}',
            new App\Infra\Factory\AcceptBrandedCardInvitationFactory,
            [$bearerAuthForUser]
        );
        $app->on(
            HttpMethods::PUT,
            '/accept/transaction/{id}',
            new App\Infra\Factory\AcceptTransactionFactory,
            [$bearerAuthForUser]
        );
        $app->on(
            HttpMethods::POST,
            '/reset/password',
            new App\Infra\Factory\ResetPasswordForAccountFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/reset/password-general',
            new App\Infra\Factory\ResetPasswordForAccountGeneralFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/reset/deviceId',
            new App\Infra\Factory\ResetDeviceIdFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/reset/deviceId-general',
            new App\Infra\Factory\ResetDeviceIdGeneralFactory,
        );
        $app->on(
            HttpMethods::PUT,
            '/new/password',
            new App\Infra\Factory\SendNewPasswordFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/code/send',
            new App\Infra\Factory\SendVerificationCodeFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/code/send-general',
            new App\Infra\Factory\SendVerificationCodeGeneralFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/code/confirm',
            new App\Infra\Factory\AcceptAndVerifyCodeFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/code/confirm-general',
            new App\Infra\Factory\AcceptAndVerifyCodeGeneralFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/invitation',
            new App\Infra\Factory\SendInvitationSmsFactory,
            [$bearerAuthForEcAndEm]
        );
        $app->on(
            HttpMethods::POST,
            '/invitation-brandedcard',
            new App\Infra\Factory\SendBrandedCardInvitationFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/transaction',
            new App\Infra\Factory\SendTransactionFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/first-password',
            new App\Infra\Factory\SendFirstPasswordFactory,
        );
        $app->on(
            HttpMethods::DELETE,
            '/{smsId}',
            new App\Infra\Factory\DeleteSmsHistoryFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\SendSmsFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListSmsHistoryGeneralFactory,
            [$bearerAuthForAll]
        );
    }
);

$app->group(
    '/establishment',
    function (SlimHttpAdapter $app) use ($bearerAuthForEmployee, $bearerAuthForEcAndEm, $bearerAuthForAll) {
        $app->on(
            HttpMethods::PUT,
            '/withdrawals/approve/{id}',
            new App\Infra\Factory\ApproveWithdrawalPaymentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::PUT,
            '/approve/{id}',
            new App\Infra\Factory\ApproveEstablishmentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::PUT,
            '/split',
            new App\Infra\Factory\UpdateTransactionSplitFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::PUT,
            '/split-update',
            new App\Infra\Factory\UpdateSplitFactory,
            [$bearerAuthForEcAndEm]
        );
        $app->on(
            HttpMethods::GET,
            '/customers',
            new App\Infra\Factory\ListEstablishmentCustomersFactory,
            [$bearerAuthForEcAndEm]
        );
        $app->on(
            HttpMethods::GET,
            '/find',
            new App\Infra\Factory\FindEstablishmentByCriteriaFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '/withdrawals',
            new App\Infra\Factory\ListMerchantWithdrawalHistoryFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::PUT,
            '',
            new App\Infra\Factory\ChangeEstablishmentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\CreateEstablishmentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/create',
            new App\Infra\Factory\CreateAnEstablishmentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListEstablishmentFactory,
            [$bearerAuthForEcAndEm]
        );
        $app->on(
            HttpMethods::GET,
            '/general',
            new App\Infra\Factory\ListEstablishmentGeneralFactory,
            [$bearerAuthForEcAndEm]
        );
    }
);

$app->group(
    '/supplier',
    function (SlimHttpAdapter $app) use ($bearerAuthForEmployee) {
        $app->on(
            HttpMethods::POST,
            '/create',
            new App\Infra\Factory\CreateSupplierFactory,
            [$bearerAuthForEmployee]
        );
    }
);

$app->group(
    '/signin',
    function (SlimHttpAdapter $app) {
        $app->on(
            HttpMethods::POST,
            '/employee',
            new App\Infra\Factory\SigninEmployeeFactory,
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\SigninFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/general',
            new App\Infra\Factory\GeneralSigninFactory,
        );
    }
);

$app->group(
    '/payment',
    function (SlimHttpAdapter $app) use ($bearerAuthForUser, $bearerAuthForBoth, $apiKeyAuthMiddleware) {
        $app->on(
            HttpMethods::POST,
            '/confirm',
            new App\Infra\Factory\ConfirmPaymentFactory,
            [$apiKeyAuthMiddleware]
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\CreatePaymentFactory,
            [$bearerAuthForUser]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListPaymentFactory,
            [$bearerAuthForBoth]
        );
    }
);

$app->on(
    HttpMethods::GET,
    '/discount/rate',
    new App\Infra\Factory\ListDiscountRateFactory,
    [$bearerAuthForBoth]
);

$app->on(
    HttpMethods::DELETE,
    '/logout',
    new App\Infra\Factory\RevokeSessionFactory,
    [$bearerAuthForBoth]
);

$app->on(
    HttpMethods::PUT,
    '/ccb/status/{userId}',
    new App\Infra\Factory\ApproveUserForCCBFactory,
    [$bearerAuthForEmployee]
);

$app->on(
    HttpMethods::PUT,
    '/change-password',
    new App\Infra\Factory\ChangePasswordFactory,
    [$bearerAuthForAll]
);

$app->group(
    '/identity',
    function (SlimHttpAdapter $app) {
        $app->on(
            HttpMethods::POST,
            '/link-generator',
            new App\Infra\Factory\IdentityVerificationFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/nuvideo-webhook',
            new App\Infra\Factory\NuvideoWebhookFactory,
        );
    }
);

$app->group(
    '/request',
    function (SlimHttpAdapter $app) use ($bearerAuthForEmployee) {
        $app->on(
            HttpMethods::POST,
            '/card',
            new App\Infra\Factory\BrandedCardRequestFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/card-approve',
            new App\Infra\Factory\ApproveBrandedCardFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '/list',
            new App\Infra\Factory\ListApprovalRequestsFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '/approvals/{requestId}',
            new App\Infra\Factory\ListApprovalInfoFactory,
            [$bearerAuthForEmployee]
        );
    }
);

$app->group(
    '/signup',
    function (SlimHttpAdapter $app) {
        $app->on(
            HttpMethods::PUT,
            '/halfway/{deviceId}',
            new App\Infra\Factory\SignupHalfwayFactory,
        );
        $app->on(
            HttpMethods::GET,
            '/progress/{deviceId}',
            new App\Infra\Factory\SignupProgressFactory,
        );
        $app->on(
            HttpMethods::POST,
            '/validate/{deviceId}',
            new App\Infra\Factory\SignupValidateFactory,
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\SignupFactory,
        );
    }
);

$app->group(
    '/profile',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth, $bearerAuthForAll) {
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListProfileGeneralFactory,
            [$bearerAuthForAll]
        );
        $app->on(
            HttpMethods::PUT,
            '',
            new App\Infra\Factory\ChangeProfileFactory,
            [$bearerAuthForBoth]
        );
    }
);

$app->group(
    '/installments',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth, $bearerAuthForEmployee) {
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\CalculateInstallmentsFactory,
            [$bearerAuthForBoth]
        );
    }
);

$app->group(
    '/summary',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth, $bearerAuthForEmployee, $bearerAuthForEcAndEm, $bearerAuthForAll) {
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\EntitySummaryFactory,
            [$bearerAuthForAll]
        );
    }
);

$app->group(
    '/transaction',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth, $bearerAuthForEmployee, $bearerAuthForEcAndEm, $bearerAuthForAll) {
        $app->on(
            HttpMethods::GET,
            '/fees/individual',
            new App\Infra\Factory\ListIndividualFeesFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::GET,
            '/fees/collective',
            new App\Infra\Factory\ListCollectiveFeesFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/fees/collective',
            new App\Infra\Factory\AnticipateTransactionsFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '/purchase',
            new App\Infra\Factory\ListTransactionsByPurchaseHashFactory,
            [$bearerAuthForAll]
        );
        $app->on(
            HttpMethods::GET,
            '/sum',
            new App\Infra\Factory\SumTransactionsByInstallmentFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListTransactionFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\CreateTransactionFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::POST,
            '/send-charge',
            new App\Infra\Factory\SendChargeFactory,
            [$bearerAuthForEcAndEm]
        );
        $app->on(
            HttpMethods::POST,
            '/accept-charge',
            new App\Infra\Factory\AcceptChargeNewFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::POST,
            '/accept-charge-new',
            new App\Infra\Factory\AcceptChargeNewFactory,
            [$bearerAuthForBoth]
        );
    }
);

$app->group(
    '/invoice',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth) {
        $app->on(
            HttpMethods::GET,
            '/relevant',
            new App\Infra\Factory\FindMostRelevantInvoiceFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::GET,
            '/{id}',
            new App\Infra\Factory\FindInvoiceByIdFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::POST,
            '/recent',
            new App\Infra\Factory\CreateMostRecentInvoiceFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::POST,
            '/create-update',
            new App\Infra\Factory\CreateUpdateInvoiceFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListInvoiceFactory,
            [$bearerAuthForBoth]
        );
    }
);

$app->group(
    '/card',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth) {
        $app->on(
            HttpMethods::GET,
            '/days',
            new App\Infra\Factory\ListCardBillingCycleFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListCardFactory,
            [$bearerAuthForBoth]
        );
    }
);

$app->group(
    '/branded-card-user',
    function (SlimHttpAdapter $app) use ($bearerAuthForEmployee, $bearerAuthForAll) {
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\CreateCardBrandedUserRequestFactory,
            []
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListBrandedCardUserFactory,
            []
        );
        $app->on(
            HttpMethods::GET,
            '/user/{userId}',
            new App\Infra\Factory\GetBrandedCardUserByUserIdFactory,
            []
        );
        $app->on(
            HttpMethods::GET,
            '/user/cards/{userId}',
            new App\Infra\Factory\ListBrandedCardFactory,
            [$bearerAuthForAll]
        );

        $app->on(
            HttpMethods::GET,
            '/user/cards/details/{cardId}',
            new App\Infra\Factory\BrandedCardDetailsFactory,
            [$bearerAuthForAll]
        );
        $app->on(
            HttpMethods::GET,
            '/user/cards/limit/{cardId}',
            new App\Infra\Factory\BrandedCardLimitFactory,
            [$bearerAuthForAll]
        );
        $app->on(
            HttpMethods::PUT,
            '/user/cards/status',
            new App\Infra\Factory\BrandedCardStatusFactory,
            [$bearerAuthForAll]
        );
    }
);

$app->group(
    '/user',
    function (SlimHttpAdapter $app) use ($bearerAuthForBoth, $bearerAuthForEmployee, $bearerAuthForUser) {
        $app->on(
            HttpMethods::PUT,
            '/card/days',
            new App\Infra\Factory\UpdateCardsClosingAndDueDatesFactory,
            [$bearerAuthForUser]
        );
        $app->on(
            HttpMethods::POST,
            '/card',
            new App\Infra\Factory\CreateUserCardFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::GET,
            '/card',
            new App\Infra\Factory\ListUserCardFactory,
            [$bearerAuthForBoth]
        );
        $app->on(
            HttpMethods::GET,
            '/{userId}/documents',
            new App\Infra\Factory\ListUserDocumentsFactory,
            [$bearerAuthForEmployee]
        );
        $app->on(
            HttpMethods::GET,
            '',
            new App\Infra\Factory\ListUserFactory,
            [$bearerAuthForEmployee]
        );
    }
);

$app->group(
    '/bureau',
    function (SlimHttpAdapter $app) use ($bearerAuthForUser, $bearerAuthForEmployee, $bearerAuthForBoth) {
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\CustomerBureauFactory
        );
    }
);

$app->on(
    HttpMethods::GET,
    '/cnae/mcc',
    new App\Infra\Factory\ListCnaeMccFactory,
    [$bearerAuthForBoth]
);

$app->on(
    HttpMethods::GET,
    '/segment',
    new App\Infra\Factory\ListSegmentFactory,
    [$bearerAuthForBoth]
);

$app->run();
