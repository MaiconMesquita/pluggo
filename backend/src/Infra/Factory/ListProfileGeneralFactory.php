<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListProfileGeneral\ListProfileGeneral;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Profile\ListProfileGeneralController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListProfileGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListProfileGeneralController(
                new ListProfileGeneral(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
