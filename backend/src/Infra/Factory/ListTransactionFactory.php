<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListTransaction\ListTransaction;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Transaction\ListTransactionController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListTransactionFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListTransactionController(
                new ListTransaction(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
