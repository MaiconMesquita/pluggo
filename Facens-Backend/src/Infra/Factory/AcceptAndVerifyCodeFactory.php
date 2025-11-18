<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptAndVerifyCode\AcceptAndVerifyCode;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Sms\AcceptAndVerifyCodeController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class AcceptAndVerifyCodeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new AcceptAndVerifyCodeController(
                new AcceptAndVerifyCode(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),        
                new ThirdPartyFactory()
            ),
            new ThirdPartyFactory()
        );
    }
}
