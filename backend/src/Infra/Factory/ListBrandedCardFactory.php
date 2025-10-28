<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListBrandedCard\ListBrandedCard;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\UserCard\ListBrandedCardController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListBrandedCardFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListBrandedCardController(
                new ListBrandedCard(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
