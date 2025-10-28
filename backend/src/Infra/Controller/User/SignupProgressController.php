<?php

namespace App\Infra\Controller\User;

use App\Application\UseCase\SignupProgress\SignupProgressInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;

class SignupProgressController implements Controller
{
    public function __construct(
        private $updateSignup,
    ) {
    }

    public function serialize(array $args): SignupProgressInput
    { 
        if (!isset($args['deviceId']))throw new InvalidDataException('deviceId is required');

        $input = new SignupProgressInput();
        $input->deviceId = $args['deviceId'];

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->updateSignup->execute(
                $this->serialize($httpRequest->args)
            )
        );
    }
}
