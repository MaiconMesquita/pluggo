<?php

namespace App\Infra\Factory;

use App\Application\UseCase\BrandedCardDetails\BrandedCardDetails;
use App\Application\UseCase\ListBrandedCard\ListBrandedCard;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\UserCard\BrandedCardDetailsController;
use App\Infra\Controller\UserCard\ListBrandedCardController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class BrandedCardDetailsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new BrandedCardDetailsController(
                new BrandedCardDetails(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,

                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
