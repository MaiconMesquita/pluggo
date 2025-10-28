<?php

namespace App\Infra\Factory;

use App\Application\UseCase\RevokeSession\RevokeSession;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Authentication\RevokeSessionController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class RevokeSessionFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new RevokeSessionController(
                new RevokeSession(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
