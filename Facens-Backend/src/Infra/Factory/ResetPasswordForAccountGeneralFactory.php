<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ResetPasswordForAccountGeneral\ResetPasswordForAccountGeneral;
use App\Infra\Controller\Sms\ResetPasswordForAccountGeneralController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ResetPasswordForAccountGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new ResetPasswordForAccountGeneralController(
                new ResetPasswordForAccountGeneral(
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
