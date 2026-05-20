<?php

namespace App\Infra\Factory;

use App\Application\UseCase\DeleteHost\DeleteHost;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Host\DeleteHostController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class DeleteHostFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new DeleteHostController(
                new DeleteHost(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
