<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListUser\ListUser;
use App\Infra\Controller\Controller;
use App\Infra\Controller\User\ListUserController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListUserFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListUserController(
                new ListUser(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
