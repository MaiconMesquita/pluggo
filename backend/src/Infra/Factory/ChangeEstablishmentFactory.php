<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ChangeEstablishment\ChangeEstablishment;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Establishment\ChangeEstablishmentController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ChangeEstablishmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new ChangeEstablishmentController(
                new ChangeEstablishment(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory
                ),
            ),
            $thirdPartyFactory            
        );
    }
}
