<?php

namespace App\Infra\Factory;

use App\Application\UseCase\GeneralSignin\GeneralSignin;
use App\Infra\Controller\Authentication\GeneralSigninController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class GeneralSigninFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new GeneralSigninController(
                new GeneralSignin(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: new ThirdPartyFactory(),                    
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
