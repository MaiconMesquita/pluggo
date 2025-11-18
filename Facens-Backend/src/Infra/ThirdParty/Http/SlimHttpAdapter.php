<?php

namespace App\Infra\ThirdParty\Http;

use App\Infra\Controller\{
    HttpRequest,
    HttpMethods,
    HttpResponse,
};
use App\Infra\Factory\Contract\ControllerFactoryContract;
use Closure;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;


class SlimHttpAdapter implements Http
{

    public function __construct(
        private App|RouteCollectorProxy $app,
        private string $basePath = ''
    ) {
    }

    public function getHandleFunction(
        ControllerFactoryContract $controllerFactory,
        string $path,
        array $middlewares
    ) {
        return function (Request $request, Response $response, $args) use ($controllerFactory, $path, $middlewares) {
            $httpRequest = new HttpRequest(
                json_decode(
                    $request->getBody(),
                    true
                ),
                $request->getQueryParams(),
                $request->getHeaders(),
                $this->basePath . $path,
                $request->getMethod(),
                $args,
            );

            try {
                foreach ($middlewares as $middleware) {
                    $middleware->execute($httpRequest);
                }
                $controller = $controllerFactory::getController();
                $httpResponse = $controller->handle($httpRequest);
                
            } catch (Exception $e) {
                $httpResponse = new HttpResponse(                    
                    HttpResponse::HTTP_ACCESS_FORBIDDEN,                    
                    ['message' => 'Unauthorized']
                );
            }

            $response->getBody()->write(
                json_encode($httpResponse->body, JSON_UNESCAPED_SLASHES)
            );
            $newResponse = $response
                ->withStatus($httpResponse->statusCode)
                ->withHeader('Content-Type', 'Application/json');

            return $newResponse;
        };
    }

    public function on(
        HttpMethods $method,
        string $path,
        ControllerFactoryContract $controllerFactory,
        array $middlewares = []
    ) {
        $this->app->{$method->value}(
            $path,
            $this->getHandleFunction(
                $controllerFactory,
                $path,
                $middlewares
            )
        );
    }

    public function group(string $path, Closure $callable)
    {
        return $this->app->group(
            $path,
            function (RouteCollectorProxy $group) use ($callable, $path) {
                $callable(new SlimHttpAdapter($group, $path));
            }
        );
    }

    public function run()
    {
        $this->app->run();
    }
}
