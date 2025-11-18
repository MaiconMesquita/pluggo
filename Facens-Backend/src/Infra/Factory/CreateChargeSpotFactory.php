<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateChargerSpot\CreateChargerSpot;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Host\CreateChargerSpotController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateChargeSpotFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new CreateChargerSpotController(
                new CreateChargerSpot(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
