<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateUserCard\CreateUserCard;
use App\Infra\Controller\UserCard\CreateUserCardController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateUserCardFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreateUserCardController(
                new CreateUserCard(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
