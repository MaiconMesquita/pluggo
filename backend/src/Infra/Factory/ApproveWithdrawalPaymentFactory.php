<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ApproveWithdrawalPayment\ApproveWithdrawalPayment;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Support\ApproveWithdrawalPaymentController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ApproveWithdrawalPaymentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ApproveWithdrawalPaymentController(
                new ApproveWithdrawalPayment(
                    thirdPartyFactory: new ThirdPartyFactory(),
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory   
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
