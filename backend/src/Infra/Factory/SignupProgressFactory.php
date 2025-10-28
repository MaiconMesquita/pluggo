<?php

namespace App\Infra\Factory;

use App\Application\UseCase\SignupProgress\SignupProgress;

use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\User\SignupProgressController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class SignupProgressFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        return new Handler(
            new SignupProgressController(
                new SignupProgress(                   
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),                    
                ),
            ),
            $thirdPartyFactory            
        );
    }
}
