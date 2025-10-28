<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CustomerBureau\CustomerBureau;
use App\Domain\Entity\Service\CreditService\CreditServiceRequests;
use App\Helper\CreditServiceHelper;
use App\Infra\Controller\Bureau\CustomerBureauController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\RepositoryFactoryMySQL;
use App\Infra\Factory\ThirdPartyFactory;

/**
 * @codeCoverageIgnore
 */
class CustomerBureauFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        $creditServiceHelper = new CreditServiceHelper(
            clientRequest: $thirdPartyFactory->getClientRequest(),
            logging: $thirdPartyFactory->getLogging()
        );

        $creditServiceRequests = new CreditServiceRequests($creditServiceHelper);

        return new Handler(
            new CustomerBureauController(
                new CustomerBureau(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    creditServiceRequests: $creditServiceRequests
                )
            ),
            $thirdPartyFactory
        );
    }
}
