<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendNewPassword\SendNewPassword;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Support\SendNewPasswordController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendNewPasswordFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendNewPasswordController(
                new SendNewPassword(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
