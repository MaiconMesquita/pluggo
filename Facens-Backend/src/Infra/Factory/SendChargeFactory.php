<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendCharge\SendCharge;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Transaction\SendChargeController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendChargeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendChargeController(
                new SendCharge(                   
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
