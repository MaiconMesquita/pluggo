<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateDriver\UpdateDriver;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Driver\UpdateDriverController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class UpdateDriverFactory implements ControllerFactoryContract
{

    public static function getController(): Controller
    {
        return new Handler(
            new UpdateDriverController(
                new UpdateDriver(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
