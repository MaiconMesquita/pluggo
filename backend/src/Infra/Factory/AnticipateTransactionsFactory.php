<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AnticipateTransactions\AnticipateTransactions;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Transaction\AnticipateTransactionsController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class AnticipateTransactionsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new AnticipateTransactionsController(
                new AnticipateTransactions(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
