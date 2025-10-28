<?php

namespace App\Infra\Controller\Authentication;

use App\Application\UseCase\RevokeSession\RevokeSessionInput;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class RevokeSessionController implements Controller
{
    public function __construct(private $RevokeSession) {}

    public function serialize(array $params): RevokeSessionInput
    {
        $input = new RevokeSessionInput;

        $input->deviceId = $params['deviceId'] ?? null;
        
        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->RevokeSession->execute(
                $this->serialize(
                    $httpRequest->params,
                )
            )
        );
    }
}
