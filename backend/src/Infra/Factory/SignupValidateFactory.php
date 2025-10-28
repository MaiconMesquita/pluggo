<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SignupValidate\SignupValidate;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Authentication\SignupValidateController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SignupValidateFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SignupValidateController(
                new SignupValidate(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: $thirdPartyFactory,
                    serviceFactory: new ServiceFactory
                ),
                $thirdPartyFactory 
            ),
            $thirdPartyFactory            
        );
    }
}
