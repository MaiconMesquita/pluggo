<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateCardsClosingAndDueDates\UpdateCardsClosingAndDueDates;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Card\UpdateCardsClosingAndDueDatesController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class UpdateCardsClosingAndDueDatesFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new UpdateCardsClosingAndDueDatesController(
                new UpdateCardsClosingAndDueDates(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}