<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptInvitationSms\AcceptInvitationSms;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\AcceptInvitationSmsController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class AcceptInvitationSmsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new AcceptInvitationSmsController(
                new AcceptInvitationSms(                  
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),            
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
