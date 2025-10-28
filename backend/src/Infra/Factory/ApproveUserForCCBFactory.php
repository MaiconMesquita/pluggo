<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ApproveUserForCCB\ApproveUserForCCB;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Support\ApproveUserForCCBController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ApproveUserForCCBFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ApproveUserForCCBController(
                new ApproveUserForCCB(
                    thirdPartyFactory: new ThirdPartyFactory(),
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory   
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
