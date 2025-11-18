<?php

namespace App\Infra\Factory;

use App\Application\UseCase\BrandedCardRequest\BrandedCardRequest as BrandedCardRequestUseCase;
use App\Infra\Controller\ApprovalRequest\BrandedCardRequestController;
use App\Infra\Controller\{
    Controller,
    Handler
};
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class BrandedCardRequestFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new BrandedCardRequestController(
                new BrandedCardRequestUseCase(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
