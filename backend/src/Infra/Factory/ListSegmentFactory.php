<?php

namespace App\Infra\Factory;

use App\Application\UseCase\ListSegment\ListSegment;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Segment\ListSegmentController;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class ListSegmentFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new ListSegmentController(
                new ListSegment(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),                
            ),
            new ThirdPartyFactory()
        );
    }
}
