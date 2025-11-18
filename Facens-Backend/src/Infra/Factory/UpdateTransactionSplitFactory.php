<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateTransactionSplit\UpdateTransactionSplit;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Establishment\UpdateTransactionSplitController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class UpdateTransactionSplitFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new UpdateTransactionSplitController(
                new UpdateTransactionSplit(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory
                ),
            ),
            $thirdPartyFactory            
        );
    }
}
