<?php

namespace App\Infra\Factory;

use App\Application\UseCase\CreateReview\CreateReview;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Review\CreateReviewController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class CreateReviewFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new CreateReviewController(
                new CreateReview(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
