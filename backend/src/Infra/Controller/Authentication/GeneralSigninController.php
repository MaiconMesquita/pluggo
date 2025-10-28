<?php

namespace App\Infra\Controller\Authentication;

use App\Application\UseCase\GeneralSignin\GeneralSigninInput;
use App\Domain\Exception\{InvalidAuthException, InvalidDataException};
use App\Infra\Controller\Controller;
use App\Infra\Controller\HttpRequest;
use App\Infra\Controller\HttpResponse;

class GeneralSigninController implements Controller
{
    public function __construct(private $signin) {}

    public function serialize(array $headers, array $body): GeneralSigninInput
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

        if (!$password)
            throw new InvalidDataException("password is required");

        if (!$email)
            throw new InvalidDataException("email is required");

        $input = new GeneralSigninInput();

        // Não é obrigatório na controller
        $input->email      = $email;
        $input->entityType = $body['entityType'];
        $input->password    = $password;

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_CREATED,
            $this->signin->execute(
                $this->serialize($httpRequest->headers, $httpRequest->body)
            )
        );
    }
}
