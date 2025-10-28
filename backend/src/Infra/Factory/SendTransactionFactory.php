<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendTransaction\SendTransaction;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendTransactionController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendTransactionFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendTransactionController(
                new SendTransaction(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),             
                $thirdPartyFactory     
            ),
            $thirdPartyFactory  
        );
    }
}
