<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ApproveBrandedCardRequest\ApproveBrandedCardRequest;
use App\Infra\Controller\{
    Controller,
    Handler
};
use App\Infra\Controller\ApprovalRequest\ApproveBrandedCardController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class ApproveBrandedCardFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ApproveBrandedCardController(
                new ApproveBrandedCardRequest(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
