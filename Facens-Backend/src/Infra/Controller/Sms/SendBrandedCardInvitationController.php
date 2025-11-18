<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\SendBrandedCardInvitation\SendBrandedCardInvitationInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class SendBrandedCardInvitationController implements Controller
{
    public function __construct(
        private $invitationSms,
    ) {}

    public function serialize(array $params): SendBrandedCardInvitationInput
    {
        $input = new SendBrandedCardInvitationInput();

        // CPF é obrigatório
        if (empty($params['cpf'])) {
            throw new InvalidDataException('The cpf field is required.');
        }
        $input->cpf = (string) $params['cpf'];

        // ownerId é opcional
        if (isset($params['ownerId'])) {
            if (!is_numeric($params['ownerId']) || intval($params['ownerId']) != $params['ownerId']) {
                throw new InvalidDataException('The ownerId must be an integer.');
            }
            $input->ownerId = (int) $params['ownerId'];
        }

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->invitationSms->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
