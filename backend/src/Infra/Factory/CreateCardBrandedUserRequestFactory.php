<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateCardBrandedUserRequest\CreateCardBrandedUserRequest;
use App\Infra\Controller\Support\CreateCardBrandedUserRequestController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

/**
 * @codeCoverageIgnore
 */
class CreateCardBrandedUserRequestFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $repositoryFactory = new RepositoryFactoryMySQL(Doctrine::getInstance());
        $serviceFactory = new ServiceFactory();
        return new Handler(
            new CreateCardBrandedUserRequestController(
                new CreateCardBrandedUserRequest(
                    repositoryFactory: $repositoryFactory,
                    serviceFactory: $serviceFactory,
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
