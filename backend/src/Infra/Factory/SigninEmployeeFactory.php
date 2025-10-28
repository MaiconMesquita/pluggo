<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SigninEmployee\SigninEmployee;
use App\Infra\Controller\Authentication\SigninEmployeeController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SigninEmployeeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new SigninEmployeeController(
                new SigninEmployee(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: new ThirdPartyFactory(),                    
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
