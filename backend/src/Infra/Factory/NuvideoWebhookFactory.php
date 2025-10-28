<?php

namespace App\Infra\Factory;

use App\Application\UseCase\NuvideoWebhook\NuvideoWebhook;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\IdentityVerification\NuvideoWebhookController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\RepositoryFactoryMySQL;
use App\Infra\Factory\ThirdPartyFactory;

/**
 * @codeCoverageIgnore
 */
class NuvideoWebhookFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();

        return new Handler(
            new NuvideoWebhookController(
                new NuvideoWebhook(
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory   
                )
            ),
            $thirdPartyFactory
        );
    }
}
