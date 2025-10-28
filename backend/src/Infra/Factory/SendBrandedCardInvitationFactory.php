<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SendBrandedCardInvitation\SendBrandedCardInvitation;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\SendBrandedCardInvitationController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class SendBrandedCardInvitationFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SendBrandedCardInvitationController(
                new SendBrandedCardInvitation(                   
                    thirdPartyFactory: $thirdPartyFactory,
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    serviceFactory: new ServiceFactory                    
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
