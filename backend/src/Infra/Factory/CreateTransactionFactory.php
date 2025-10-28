<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateTransaction\CreateTransaction;
use App\Infra\Controller\Transaction\CreateTransactionController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateTransactionFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new CreateTransactionController(
                new CreateTransaction(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}