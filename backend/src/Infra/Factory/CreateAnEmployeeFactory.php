<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateAnEmployee\CreateAnEmployee;
use App\Infra\Controller\Employee\CreateAnEmployeeController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateAnEmployeeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreateAnEmployeeController(
                new CreateAnEmployee(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
