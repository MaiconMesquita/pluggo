<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListBrandedCardUser\ListBrandedCardUser;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Support\ListBrandedCardUserController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListBrandedCardUserFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListBrandedCardUserController(
                new ListBrandedCardUser(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
