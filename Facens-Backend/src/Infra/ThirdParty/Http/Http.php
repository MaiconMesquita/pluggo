<?php

namespace App\Infra\ThirdParty\Http;

use App\Infra\Factory\Contract\ControllerFactoryContract;
use App\Infra\Controller\HttpMethods;

interface Http
{

    public function on(
        HttpMethods $method,
        string $path,
        ControllerFactoryContract $controllerFactory,
        array $middlewares = []
    );
    public function run();
}
