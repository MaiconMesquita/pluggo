<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateSupplier\CreateSupplier;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Supplier\CreateSupplierController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateSupplierFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreateSupplierController(
                new CreateSupplier(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                    thirdPartyFactory: $thirdPartyFactory
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
