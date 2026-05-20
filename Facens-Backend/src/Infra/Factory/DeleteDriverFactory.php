<?php

namespace App\Infra\Factory;

use App\Application\UseCase\DeleteDriver\DeleteDriver;
use App\Infra\Controller\Employee\DeleteDriverController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class DeleteDriverFactory implements ControllerFactoryContract
{

    public static function getController(): Controller
    {
        return new Handler(
            new DeleteDriverController(
                new DeleteDriver(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
