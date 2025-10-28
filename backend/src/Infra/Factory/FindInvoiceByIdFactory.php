<?php

namespace App\Infra\Factory;

use App\Application\UseCase\FindInvoiceById\FindInvoiceById;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Invoice\FindInvoiceByIdController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class FindInvoiceByIdFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new FindInvoiceByIdController(
                new FindInvoiceById(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
