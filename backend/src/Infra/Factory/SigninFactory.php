<?php

namespace App\Infra\Factory;

use App\Application\UseCase\Signin\Signin;
use App\Infra\Controller\Authentication\SigninController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SigninFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new SigninController(
                new Signin(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: new ThirdPartyFactory(),                    
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
