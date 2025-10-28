<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListEmployee\ListEmployee;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Employee\ListEmployeeController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListEmployeeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListEmployeeController(
                new ListEmployee(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
