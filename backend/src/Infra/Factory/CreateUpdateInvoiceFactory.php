<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateAndUpdateInvoices\CreateAndUpdateInvoices;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Invoice\CreateUpdateInvoiceController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateUpdateInvoiceFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new CreateUpdateInvoiceController(
                new CreateAndUpdateInvoices(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    thirdPartyFactory: new ThirdPartyFactory
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
