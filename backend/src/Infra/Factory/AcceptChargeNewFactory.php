<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptChargeNew\AcceptChargeNew;
use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Transaction\AcceptChargeNewController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\RepositoryFactoryMySQL;
use App\Infra\Factory\ThirdPartyFactory;

/**
 * @codeCoverageIgnore
 */
class AcceptChargeNewFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $repositoryFactory = new RepositoryFactoryMySQL(Doctrine::getInstance()); // sua implementação do contrato
        $thirdPartyFactory = new ThirdPartyFactory(); // idem
        return new Handler(
            new AcceptChargeNewController(
                new AcceptChargeNew(
                    $repositoryFactory,
                    $thirdPartyFactory
                )
            ),
            $thirdPartyFactory
        );
    }
}
