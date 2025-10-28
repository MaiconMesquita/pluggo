<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListEmployeeEstablishment\ListEmployeeEstablishment;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Employee\ListEmployeeEstablishmentController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListEmployeeEstablishmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListEmployeeEstablishmentController(
                new ListEmployeeEstablishment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
