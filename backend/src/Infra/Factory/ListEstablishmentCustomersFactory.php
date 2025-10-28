<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListEstablishmentCustomers\ListEstablishmentCustomers;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Establishment\ListEstablishmentCustomerController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListEstablishmentCustomersFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListEstablishmentCustomerController(
                new ListEstablishmentCustomers(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
