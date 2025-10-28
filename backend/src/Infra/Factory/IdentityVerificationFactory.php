<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CustomerBureau\CustomerBureau;
use App\Application\UseCase\NuvideoLinkGenerator\NuvideoLinkGenerator;
use App\Domain\Entity\Service\CreditService\CreditServiceRequests;
use App\Domain\Entity\Service\NuvideoService\NuvideoServiceRequests;
use App\Helper\CreditServiceHelper;
use App\Helper\NuvideoHelper;
use App\Infra\Controller\Bureau\CustomerBureauController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\IdentityVerification\IdentityVerificationController;
use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\RepositoryFactoryMySQL;
use App\Infra\Factory\ThirdPartyFactory;

/**
 * @codeCoverageIgnore
 */
class IdentityVerificationFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        $thirdPartyFactory = new ThirdPartyFactory();
        $nuvideHelper = new NuvideoHelper($thirdPartyFactory);

        return new Handler(
            new IdentityVerificationController(
                new NuvideoLinkGenerator(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                    nuvideoServiceRequests: new NuvideoServiceRequests($nuvideHelper)
                )
            ),
            $thirdPartyFactory
        );
    }
}
