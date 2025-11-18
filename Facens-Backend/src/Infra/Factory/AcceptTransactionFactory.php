<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptTransaction\AcceptTransaction;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\AcceptTransactionController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class AcceptTransactionFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new AcceptTransactionController(
                new AcceptTransaction(                  
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
