<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ApprovalRequestInfo\ApprovalRequestInfo;
use App\Infra\Controller\ApprovalRequest\ListApprovalInfoController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListApprovalInfoFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListApprovalInfoController(
                new ApprovalRequestInfo(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
