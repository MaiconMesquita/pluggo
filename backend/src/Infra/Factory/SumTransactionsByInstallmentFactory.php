<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SumTransactionsByInstallment\SumTransactionsByInstallment;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Support\SumTransactionsByInstallmentController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SumTransactionsByInstallmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new SumTransactionsByInstallmentController(
                new SumTransactionsByInstallment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
