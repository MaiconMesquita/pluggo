<?php

namespace App\Infra\Factory;

use App\Application\UseCase\BrandedCardStatus\BrandedCardStatus;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\UserCard\BrandedCardStatusController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class BrandedCardStatusFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new BrandedCardStatusController(
                new BrandedCardStatus(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,

                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
