<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ResetDeviceIdGeneral\ResetDeviceIdGeneral;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Authentication\ResetDeviceIdGeneralController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ResetDeviceIdGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ResetDeviceIdGeneralController(
                new ResetDeviceIdGeneral(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                    thirdPartyFactory: new ThirdPartyFactory()
                ),       
                new ThirdPartyFactory()        
            ),
            new ThirdPartyFactory()
        );
    }
}
