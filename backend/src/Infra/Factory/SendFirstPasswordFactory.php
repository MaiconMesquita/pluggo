<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendFirstPassword\SendFirstPassword;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendFirstPasswordController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendFirstPasswordFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendFirstPasswordController(
                new SendFirstPassword(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
