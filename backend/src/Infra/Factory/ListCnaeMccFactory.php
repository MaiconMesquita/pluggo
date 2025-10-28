<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListCnaeMcc\ListCnaeMcc;
use App\Infra\Controller\Controller;
use App\Infra\Controller\CnaeMcc\ListCnaeMccController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListCnaeMccFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListCnaeMccController(
                new ListCnaeMcc(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
