<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListSmsHistoryGeneral\ListSmsHistoryGeneral;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Sms\ListSmsHistoryGeneralController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListSmsHistoryGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListSmsHistoryGeneralController(
                new ListSmsHistoryGeneral(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
