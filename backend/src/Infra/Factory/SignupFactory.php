<?php

namespace App\Infra\Factory;

use App\Application\UseCase\Signup\Signup;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\User\SignupController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SignupFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SignupController(
                new Signup(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: $thirdPartyFactory
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            $thirdPartyFactory            
        );
    }
}
