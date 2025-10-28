<?php

namespace App\Infra\Factory;

use App\Application\UseCase\AcceptBrandedCardInvitation\AcceptBrandedCardInvitation;

use App\Infra\Controller\{Handler, Controller};
use App\Infra\Controller\Sms\AcceptBrandedCardInvitationController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;

/**
 * @codeCoverageIgnore
 */
class AcceptBrandedCardInvitationFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new AcceptBrandedCardInvitationController(
                new AcceptBrandedCardInvitation(                  
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),            
                ),                
            ),
            $thirdPartyFactory            
        );
    }
}
