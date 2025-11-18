<?php

namespace App\Infra\Controller\User;

use App\Application\UseCase\Signup\SignupInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class SignupController implements Controller
{
    public function __construct(
        private $createUser,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {}

    public function serialize(array $body): SignupInput
    {
        $schema = [
            "name"    => 'required',
            "phone"       => 'required',
            "email"       => 'required',
            "userType"       => 'required',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema))
            throw new InvalidDataException($requestValidator->getMessageError());

        $input = new SignupInput;

        $phone = $requestValidator->getParam('phone');
        if (!ctype_digit($phone)) throw new InvalidDataException('Phone must contain only numbers.');

        $input->name = $requestValidator->getParam('name');
        $input->userType = $requestValidator->getParam('userType');
        $input->phone = $phone;
        $input->email = $requestValidator->getParam('email');

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->createUser->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
