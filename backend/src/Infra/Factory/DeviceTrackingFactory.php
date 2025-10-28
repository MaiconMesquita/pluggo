<?php

namespace App\Infra\Factory;

use App\Application\UseCase\DeviceTracking\DeviceTracking;
use App\Infra\Controller\Controller;
use App\Infra\Controller\DeviceTracking\DeviceTrackingController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class DeviceTrackingFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new DeviceTrackingController(
                new DeviceTracking(
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
