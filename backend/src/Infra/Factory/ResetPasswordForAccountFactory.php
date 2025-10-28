<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ResetPasswordForAccount\ResetPasswordForAccount;
use App\Infra\Controller\Sms\ResetPasswordForAccountController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ResetPasswordForAccountFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new ResetPasswordForAccountController(
                new ResetPasswordForAccount(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                    thirdPartyFactory: $thirdPartyFactory
                ),
                new ThirdPartyFactory()
            ),
            new ThirdPartyFactory()
        );
    }
}
