<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListDiscountRate\ListDiscountRate;
use App\Infra\Controller\Controller;
use App\Infra\Controller\DiscountRate\ListDiscountRateController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListDiscountRateFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListDiscountRateController(
                new ListDiscountRate(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
