<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ChangeProfile\ChangeProfile;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Profile\ChangeProfileController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ChangeProfileFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new ChangeProfileController(
                new ChangeProfile(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: new ThirdPartyFactory(),
                    serviceFactory: new ServiceFactory
                ),
            ),
            $thirdPartyFactory            
        );
    }
}
