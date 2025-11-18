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

$bearerAuthForHost = new BearerAuth(
    new RepositoryFactoryMySQL(Doctrine::getInstance()),
    new ThirdPartyFactory(),
    ['host']
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
            '',
            new App\Infra\Factory\CreateAnEmployeeFactory,
            [$flexibleAuth]
        );
       
    }
);


$app->group(
    '/sms',
    function (SlimHttpAdapter $app) use ($bearerAuthForUser, $bearerAuthForEmployee, $bearerAuthForBoth, $bearerAuthForAll, $bearerAuthForEcAndEm) {
       
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
            HttpMethods::PUT,
            '/new/password',
            new App\Infra\Factory\SendNewPasswordFactory,
            [$bearerAuthForEmployee]
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
            '/first-password',
            new App\Infra\Factory\SendFirstPasswordFactory,
        );
        
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\SendSmsFactory,
            [$bearerAuthForEmployee]
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

$app->on(
    HttpMethods::DELETE,
    '/logout',
    new App\Infra\Factory\RevokeSessionFactory,
    [$bearerAuthForBoth]
);



$app->on(
    HttpMethods::PUT,
    '/change-password',
    new App\Infra\Factory\ChangePasswordFactory,
    [$bearerAuthForAll]
);

$app->group(
    '/host',
    function (SlimHttpAdapter $app) use ($bearerAuthForHost){
    
        $app->on(
            HttpMethods::POST,
            '/create-charge-spot',
            new App\Infra\Factory\CreateChargeSpotFactory,
            [$bearerAuthForHost]
        );
        $app->on(
            HttpMethods::GET,
            '/list-spots',
            new App\Infra\Factory\ListChargeSpotsFactory,
            [$bearerAuthForHost]
        );
        $app->on(
            HttpMethods::GET,
            '/list-all-spots',
            new App\Infra\Factory\ListAllChargeSpotsFactory,
            []
        );
    }
);


$app->group(
    '/signup',
    function (SlimHttpAdapter $app) {
    
        $app->on(
            HttpMethods::POST,
            '',
            new App\Infra\Factory\SignupFactory,
        );
    }
);


$app->run();
