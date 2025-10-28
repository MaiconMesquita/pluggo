<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListIndividualFees\ListIndividualFees;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Transaction\ListIndividualFeesController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListIndividualFeesFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListIndividualFeesController(
                new ListIndividualFees(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
