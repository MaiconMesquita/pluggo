<?php

namespace App\Infra\Factory;

use App\Application\UseCase\UpdateReview\UpdateReview;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Controller\Review\UpdateReviewController;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;


/**
 * @codeCoverageIgnore
 */
class UpdateReviewFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new UpdateReviewController(
                new UpdateReview(
                    repositoryFactory: new RepositoryFactoryMySQL(Doctrine::getInstance()),
                ),
            ),
            new ThirdPartyFactory()
        );
    }
}
