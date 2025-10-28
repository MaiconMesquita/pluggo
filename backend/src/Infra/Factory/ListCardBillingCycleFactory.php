<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListCardBillingCycle\ListCardBillingCycle;
use App\Infra\Controller\Controller;
use App\Infra\Controller\CardBillingCycle\ListCardBillingCycleController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListCardBillingCycleFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListCardBillingCycleController(
                new ListCardBillingCycle(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
