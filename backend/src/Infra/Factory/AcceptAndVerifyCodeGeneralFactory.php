<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptAndVerifyCodeGeneral\AcceptAndVerifyCodeGeneral;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Sms\AcceptAndVerifyCodeGeneralController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class AcceptAndVerifyCodeGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();

        return new Handler(
            new AcceptAndVerifyCodeGeneralController(
                new AcceptAndVerifyCodeGeneral(
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory
                ),
                new ThirdPartyFactory()
            ),
            new ThirdPartyFactory()
        );
    }
}
