<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateChargerSpot\UpdateChargerSpot;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Spot\UpdateChargerSpotController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class UpdateChargeSpotFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new UpdateChargerSpotController(
                new UpdateChargerSpot(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
