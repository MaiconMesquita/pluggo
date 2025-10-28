<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListInvoice\ListInvoice;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Invoice\ListInvoiceController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListInvoiceFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListInvoiceController(
                new ListInvoice(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
