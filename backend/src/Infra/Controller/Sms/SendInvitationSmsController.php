<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\SendInvitationSms\SendInvitationSmsInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class SendInvitationSmsController implements Controller
{
    public function __construct(
        private $invitationSms,
    ) {}

    public function serialize(array $params): SendInvitationSmsInput
    {
        $input = new SendInvitationSmsInput();
        // Validação: Apenas um dos campos deve ser informado
        $fields = [
            'phone' => $params['phone'] ?? null,
            'userId' => $params['userId'] ?? null,
            'cpf' => $params['cpf'] ?? null,
        ];


        $filledFields = array_filter($fields, fn($v) => $v !== null && $v !== '');

        if (count($filledFields) === 0) {
            throw new InvalidDataException('At least one of the fields (phone, userId, deviceId) must be provided.');
        }

        if (count($filledFields) > 1) {
            throw new InvalidDataException('Only one of the fields (phone, userId, deviceId) must be provided.');
        }

        // Popula o campo correspondente
        if (isset($fields['phone'])) {
            $input->phone = $fields['phone'];
        } elseif (isset($fields['userId'])) {
            if (!is_numeric($fields['userId']) || intval($fields['userId']) != $fields['userId']) {
                throw new InvalidDataException('The userId must be an integer.');
            }

            $input->userId = (int) $fields['userId'];
        } elseif (isset($fields['cpf'])) {
            $input->cpf = $fields['cpf'];
        }

        if (isset($params['establishmentId'])) {
            if (!is_numeric($params['establishmentId']) || intval($params['establishmentId']) != $params['establishmentId']) {
                throw new InvalidDataException('The establishmentId must be an integer.');
            }
            $input->establishmentId = (int) $params['establishmentId'];
        }

        if (!isset($params['entity'])) {
            throw new InvalidDataException('The entity is required.');
        }
        $input->entity = (string) $params['entity'];

        if (isset($params['isPackagePlan'])) {
            if (!is_bool($params['isPackagePlan'])) {
                throw new InvalidDataException('The isPackagePlan field must be a boolean.');
            }
            $input->isPackagePlan = (bool) $params['isPackagePlan'];
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
