<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListSmsHistory\ListSmsHistory;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Sms\ListSmsHistoryController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListSmsHistoryFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListSmsHistoryController(
                new ListSmsHistory(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
