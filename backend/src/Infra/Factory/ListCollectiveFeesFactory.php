<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListCollectiveFees\ListCollectiveFees;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Transaction\ListCollectiveFeesController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListCollectiveFeesFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListCollectiveFeesController(
                new ListCollectiveFees(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
