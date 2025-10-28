<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendVerificationCode\SendVerificationCode;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendVerificationCodeController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendVerificationCodeFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendVerificationCodeController(
                new SendVerificationCode(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),                
                new ThirdPartyFactory()
            ),
            $thirdPartyFactory            
        );
    }
}
