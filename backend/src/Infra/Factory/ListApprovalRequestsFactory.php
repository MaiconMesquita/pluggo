<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListApprovalRequests\ListApprovalRequests;
use App\Infra\Controller\ApprovalRequest\ListApprovalRequestsController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListApprovalRequestsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListApprovalRequestsController(
                new ListApprovalRequests(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
