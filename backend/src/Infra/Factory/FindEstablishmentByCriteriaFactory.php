<?php

namespace App\Infra\Factory;

use App\Application\UseCase\FindEstablishmentByCriteria\FindEstablishmentByCriteria;
use App\Infra\Controller\Establishment\FindEstablishmentByCriteriaController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class FindEstablishmentByCriteriaFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new FindEstablishmentByCriteriaController(
                new FindEstablishmentByCriteria(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
