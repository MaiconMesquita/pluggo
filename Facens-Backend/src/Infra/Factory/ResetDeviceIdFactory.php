<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ResetDeviceId\ResetDeviceId;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Authentication\ResetDeviceIdController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ResetDeviceIdFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ResetDeviceIdController(
                new ResetDeviceId(
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
