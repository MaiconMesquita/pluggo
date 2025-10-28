<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\AcceptBrandedCardInvitation\AcceptBrandedCardInvitationInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class AcceptBrandedCardInvitationController implements Controller
{
    public function __construct(
        private $acceptInvitationSms,
    ) {
    }

    public function serialize(array $params, array $args): AcceptBrandedCardInvitationInput
    {
        $input = new AcceptBrandedCardInvitationInput();

        if (!isset($args['id'])) {
            throw new InvalidDataException('id is required');
        }
        if (!is_numeric($args['id']) || intval($args['id']) != $args['id']) {
            throw new InvalidDataException('The id must be an integer.');
        }

        if (!isset($params['acceptInvitation'])) {
            throw new InvalidDataException('acceptInvitation is required');
        }

        // Validação explícita para valores booleanos em string
        if (!in_array($params['acceptInvitation'], ['true', 'false'], true)) {
            throw new InvalidDataException('The acceptInvitation field must be a boolean (true or false).');
        }

        $input->smsId = (int) $args['id'];
        $input->acceptInvitation = $params['acceptInvitation'] === 'true'; 
        

        return $input;
    }


    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->acceptInvitationSms->execute(
                $this->serialize($httpRequest->params, $httpRequest->args)
            )
        );
    }
}
