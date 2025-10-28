<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListEstablishmentGeneral\ListEstablishmentGeneral;
use App\Infra\Controller\Establishment\ListEstablishmentGeneralController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListEstablishmentGeneralFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListEstablishmentGeneralController(
                new ListEstablishmentGeneral(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
