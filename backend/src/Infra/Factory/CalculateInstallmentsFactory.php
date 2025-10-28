<?php

namespace App\Infra\Factory;

use App\Application\UseCase\Installments\CalculateInstallments;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Installments\CalculateInstallmentsController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Factory\RepositoryFactoryMySQL;
use App\Infra\Factory\ThirdPartyFactory;

/**
 * @codeCoverageIgnore
 */
class CalculateInstallmentsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new CalculateInstallmentsController(
                new CalculateInstallments(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
