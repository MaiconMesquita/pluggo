<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListUserDocuments\ListUserDocuments;
use App\Infra\Controller\Controller;
use App\Infra\Controller\User\ListUserDocumentsController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListUserDocumentsFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListUserDocumentsController(
                new ListUserDocuments(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: new ThirdPartyFactory()
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
