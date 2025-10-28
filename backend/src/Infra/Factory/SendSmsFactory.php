<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendSms\SendSms;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendSmsController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendSmsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendSmsController(
                new SendSms(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
