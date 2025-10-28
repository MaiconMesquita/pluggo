<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListCard\ListCard;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Card\ListCardController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListCardFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListCardController(
                new ListCard(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
