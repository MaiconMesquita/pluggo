<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListUserCard\ListUserCard;
use App\Infra\Controller\Controller;
use App\Infra\Controller\UserCard\ListUserCardController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListUserCardFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListUserCardController(
                new ListUserCard(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
