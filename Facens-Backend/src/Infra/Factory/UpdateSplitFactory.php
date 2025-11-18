<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateSplit\UpdateSplit;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Establishment\UpdateSplitController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class UpdateSplitFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new UpdateSplitController(
                new UpdateSplit(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory
                ),
            ),
            $thirdPartyFactory            
        );
    }
}
