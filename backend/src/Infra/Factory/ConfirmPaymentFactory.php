<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ConfirmPayment\ConfirmPayment;
use App\Infra\Controller\Pix\ConfirmPaymentController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

/**
 * @codeCoverageIgnore
 */
class ConfirmPaymentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new ConfirmPaymentController(
                new ConfirmPayment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
