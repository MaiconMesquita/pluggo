<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ChangePassword\ChangePassword;
use App\Infra\Controller\{
    Controller,
    Handler,
    Authentication\ChangePasswordController
};
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ChangePasswordFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ChangePasswordController(
                new ChangePassword(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
