<?php

namespace App\Infra\Factory;

use App\Application\UseCase\DeleteReview\DeleteReview;
use App\Infra\Controller\Review\DeleteReviewController;
use App\Infra\Controller\Controller;
use App\Infra\Controller\Handler;
use App\Infra\Database\Doctrine;
use App\Infra\Factory\Contract\ControllerFactoryContract;

class DeleteReviewFactory implements ControllerFactoryContract
{
    public static function getController(): Controller
    {
        return new Handler(
            new DeleteReviewController(
                new DeleteReview(
                    new RepositoryFactoryMySQL(Doctrine::getInstance()),
                )
            ),
            new ThirdPartyFactory()
        );
    }
}
