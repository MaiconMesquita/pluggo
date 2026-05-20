<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateHost\UpdateHost;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Host\UpdateHostController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class UpdateHostFactory implements ControllerFactoryContract
{

    public static function getController(): Controller
    {
        return new Handler(
            new UpdateHostController(
                new UpdateHost(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
