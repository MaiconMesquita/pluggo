<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListPayment\ListPayment;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Pix\ListPaymentController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListPaymentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListPaymentController(
                new ListPayment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
