<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListProfile\ListProfile;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Profile\ListProfileController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListProfileFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListProfileController(
                new ListProfile(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory,
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
