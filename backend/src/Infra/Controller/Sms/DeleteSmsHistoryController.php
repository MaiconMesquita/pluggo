<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\DeleteSmsHistory\DeleteSmsHistoryInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class DeleteSmsHistoryController implements Controller
{
    public function __construct(
        private $deleteSms,
    ) {
    }

    public function serialize(array $args): DeleteSmsHistoryInput
    {
        $input = new DeleteSmsHistoryInput();

        if (!isset($args['smsId'])) {
            throw new InvalidDataException('smsId is required');
        }
        if (!is_numeric($args['smsId']) || intval($args['smsId']) != $args['smsId']) {
            throw new InvalidDataException('The smsId must be an integer.');
        }

        $input->smsId = (int) $args['smsId'];

        return $input;
    }


    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->deleteSms->execute(
                $this->serialize($httpRequest->args)
            )
        );
    }
}
