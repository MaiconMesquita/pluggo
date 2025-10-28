<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListCustomersByEstablishment\ListCustomersByEstablishment;
use App\Infra\Controller\Establishment\ListCustomersByEstablishmentController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListCustomersByEstablishmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListCustomersByEstablishmentController(
                new ListCustomersByEstablishment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
