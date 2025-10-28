<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendInvitationSms\SendInvitationSms;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendInvitationSmsController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendInvitationSmsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendInvitationSmsController(
                new SendInvitationSms(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
