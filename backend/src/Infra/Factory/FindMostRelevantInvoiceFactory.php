<?php

namespace App\Infra\Factory;

use App\Application\UseCase\FindMostRelevantInvoice\FindMostRelevantInvoice;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Invoice\FindMostRelevantInvoiceController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class FindMostRelevantInvoiceFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new FindMostRelevantInvoiceController(
                new FindMostRelevantInvoice(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
