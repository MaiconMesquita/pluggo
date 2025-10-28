<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ApproveEstablishment\ApproveEstablishment;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Support\ApproveEstablishmentController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ApproveEstablishmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ApproveEstablishmentController(
                new ApproveEstablishment(
                    thirdPartyFactory: new ThirdPartyFactory(),
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory   
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
