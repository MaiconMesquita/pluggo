<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateEmployee\CreateEmployee;
use App\Infra\Controller\Employee\CreateEmployeeController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateEmployeeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreateEmployeeController(
                new CreateEmployee(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
