<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateEstablishment\CreateEstablishment;
use App\Infra\Controller\Establishment\CreateEstablishmentController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateEstablishmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreateEstablishmentController(
                new CreateEstablishment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                    thirdPartyFactory: $thirdPartyFactory
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
