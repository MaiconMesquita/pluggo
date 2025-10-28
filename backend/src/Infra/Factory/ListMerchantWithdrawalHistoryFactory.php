<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListMerchantWithdrawalHistory\ListMerchantWithdrawalHistory;
use App\Infra\Controller\Controller;
use App\Infra\Controller\MerchantWithdrawalHistory\ListMerchantWithdrawalHistoryController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListMerchantWithdrawalHistoryFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListMerchantWithdrawalHistoryController(
                new ListMerchantWithdrawalHistory(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
