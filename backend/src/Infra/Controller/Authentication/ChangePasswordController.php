<?php

namespace App\Infra\Controller\Authentication;
use App\Domain\Exception\InvalidDataException;
use App\Application\UseCase\ChangePassword\ChangePasswordInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class ChangePasswordController implements Controller
{
    public function __construct(private $changePassword){}

    public function serialize(array $body): ChangePasswordInput
    {
        if (!isset($body['currentPassword'])) {
            throw new InvalidDataException('currentPassword is required');
        }
        if (!isset($body['newPassword'])) {
            throw new InvalidDataException('newPassword is required');
        }        

        $input = new ChangePasswordInput();        
        $input->currentPassword = $body["currentPassword"];
        $input->newPassword     = $body["newPassword"];
        return $input;
    }
    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->changePassword->execute(
                $this->serialize(
                    $httpRequest->body
                )
            )
        );
    }
}
