<?php

namespace App\Infra\Factory;

use App\Application\UseCase\DeleteChargeSpot\DeleteChargeSpot;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Spot\DeleteChargeSpotController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class DeleteChargeSpotFactory implements ControllerFactoryContract
{

    public static function getController(): Controller
    {
        return new Handler(
            new DeleteChargeSpotController(
                new DeleteChargeSpot(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
