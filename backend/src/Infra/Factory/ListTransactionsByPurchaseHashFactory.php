<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListTransactionsByPurchaseHash\ListTransactionsByPurchaseHash;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Transaction\ListTransactionsByPurchaseHashController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListTransactionsByPurchaseHashFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListTransactionsByPurchaseHashController(
                new ListTransactionsByPurchaseHash(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
