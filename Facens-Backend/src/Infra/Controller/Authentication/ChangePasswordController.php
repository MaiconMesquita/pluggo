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
    public function __construct(private $changePassword) {}

    public function serialize(array $body): ChangePasswordInput
    {
        if (!isset($body['newPassword'])) {
            throw new InvalidDataException('newPassword is required');
        }

        $input = new ChangePasswordInput();
        $input->currentPassword = $body['currentPassword'] ?? null;
        $input->newPassword = $body['newPassword'];
        $input->targetId = isset($body['targetId']) ? (int)$body['targetId'] : null;
        $input->targetEntityType = $body['targetEntityType'] ?? null;

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
