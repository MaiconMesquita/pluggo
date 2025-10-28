<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AggregatorSummary\AggregatorSummary;
use App\Application\UseCase\AnticipateTransactions\AnticipateTransactions;
use App\Infra\Controller\Controller;
use App\Infra\Controller\EntitySummary\EntitySummaryController;
use App\Infra\Controller\Transaction\AnticipateTransactionsController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class EntitySummaryFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new EntitySummaryController(
                new AggregatorSummary(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
