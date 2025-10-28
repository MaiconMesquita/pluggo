<?php

namespace App\Infra\Factory;

use App\Application\UseCase\DeleteSmsHistory\DeleteSmsHistory;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Sms\DeleteSmsHistoryController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class DeleteSmsHistoryFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new DeleteSmsHistoryController(
                new DeleteSmsHistory(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
