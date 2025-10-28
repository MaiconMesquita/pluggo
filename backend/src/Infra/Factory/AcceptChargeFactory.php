<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptCharge\AcceptCharge;
use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Transaction\AcceptChargeController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\RepositoryFactoryMySQL;
use App\Infra\Factory\ThirdPartyFactory;

/**
 * @codeCoverageIgnore
 */
class AcceptChargeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $repositoryFactory = new RepositoryFactoryMySQL(Doctrine::getInstance()); // sua implementação do contrato
        $thirdPartyFactory = new ThirdPartyFactory(); // idem
        return new Handler(
            new AcceptChargeController(
                new AcceptCharge(
                    $repositoryFactory,
                    $thirdPartyFactory
                )
            ),
            $thirdPartyFactory
        );
    }
}
