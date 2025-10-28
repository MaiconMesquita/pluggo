<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateMostRecentInvoice\CreateMostRecentInvoice;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Invoice\CreateMostRecentInvoiceController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateMostRecentInvoiceFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new CreateMostRecentInvoiceController(
                new CreateMostRecentInvoice(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}