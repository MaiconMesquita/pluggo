<?php

namespace App\Infra\Factory;

use App\Application\UseCase\FindEmployeeById\FindEmployeeById;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Employee\FindEmployeeByIdController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class FindEmployeeByIdFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new FindEmployeeByIdController(
                new FindEmployeeById(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
