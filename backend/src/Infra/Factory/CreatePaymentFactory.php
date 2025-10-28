<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreatePayment\CreatePayment;
use App\Helper\BaasHelper;
use App\Infra\Controller\Pix\CreatePaymentController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Partner\PixPartner;

/**
 * @codeCoverageIgnore
 */
class CreatePaymentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new CreatePaymentController(
                new CreatePayment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    pixPartner: new PixPartner(new BaasHelper($thirdPartyFactory)),
                ),
                thirdPartyFactory: $thirdPartyFactory
            ),
            new ThirdPartyFactory()
        );
    }
}
