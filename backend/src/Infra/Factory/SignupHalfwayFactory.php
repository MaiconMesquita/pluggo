<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SignupHalfway\SignupHalfway;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\User\SignupHalfwayController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SignupHalfwayFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SignupHalfwayController(
                new SignupHalfway(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                    thirdPartyFactory: $thirdPartyFactory
                ),
            ),
            $thirdPartyFactory            
        );
    }
}
