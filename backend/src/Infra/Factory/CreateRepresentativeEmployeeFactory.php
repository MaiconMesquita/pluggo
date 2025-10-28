<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateRepresentativeEmployee\CreateRepresentativeEmployee;
use App\Infra\Controller\Employee\CreateRepresentativeEmployeeController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateRepresentativeEmployeeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreateRepresentativeEmployeeController(
                new CreateRepresentativeEmployee(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
