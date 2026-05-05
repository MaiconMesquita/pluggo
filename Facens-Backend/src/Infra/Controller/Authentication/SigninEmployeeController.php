<?php

namespace App\Infra\Controller\Authentication;

use App\Application\UseCase\SigninEmployee\SigninEmployeeInput;
use App\Domain\Exception\{InvalidAuthException, InvalidDataException};
use App\Infra\Controller\Controller;
use App\Infra\Controller\HttpRequest;
use App\Infra\Controller\HttpResponse;


class SigninEmployeeController implements Controller
{
    public function __construct(private $signinEmployee){}
    
    public function serialize(array $headers, array $body): SigninEmployeeInput
    {
        if (!isset($headers['Authorization']))
            throw new InvalidAuthException();

        $authorization = $headers['Authorization'][0];

        list($authType, $credentials) = explode(" ", $authorization);

        if (strtolower($authType) !== "basic")
            throw new InvalidAuthException();

        if (!$credentials)
            throw new InvalidDataException("invalid credentials");

        list($email, $password) = explode(":", base64_decode($credentials));

        if (!$email)
            throw new InvalidDataException("email is required");

        if (!$password)
            throw new InvalidDataException("password is required");

        $input = new SigninEmployeeInput();
        $input->email = $email;
        $input->deviceId = $body['deviceId'] ?? null;
        $input->oneSignalId = $body['oneSignalId'] ?? null;
        $input->password = $password;

        return $input;
    }


    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_CREATED,
            $this->signinEmployee->execute(
                $this->serialize($httpRequest->headers, $httpRequest->body)
            )
        );
    }
}
