<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendVerificationCodeGeneral\SendVerificationCodeGeneral;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendVerificationCodeGeneralController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendVerificationCodeGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendVerificationCodeGeneralController(
                new SendVerificationCodeGeneral(                   
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
