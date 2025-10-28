<?php

namespace App\Infra\Factory;

use App\Application\UseCase\GetBrandedCardUserByUserId\GetBrandedCardUserByUserId;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Support\GetBrandedCardUserByUserIdController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class GetBrandedCardUserByUserIdFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new GetBrandedCardUserByUserIdController(
                new GetBrandedCardUserByUserId(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
