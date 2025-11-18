<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\AcceptTransaction\AcceptTransactionInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};

class AcceptTransactionController implements Controller
{
    public function __construct(
        private $acceptTransaction,
    ) {
    }

    public function serialize(array $params, array $args): AcceptTransactionInput
    {
        $input = new AcceptTransactionInput();

        if (!isset($args['id'])) {
            throw new InvalidDataException('id is required');
        }
        if (!is_numeric($args['id']) || intval($args['id']) != $args['id']) {
            throw new InvalidDataException('The id must be an integer.');
        }
        if (!isset($params['installmentCount'])) {
            throw new InvalidDataException('installmentCount is required');
        }
        if (!is_numeric($params['installmentCount']) || intval($params['installmentCount']) != $params['installmentCount']) {
            throw new InvalidDataException('The installmentCount must be an integer.');
        }

        if (!isset($params['acceptTransaction'])) {
            throw new InvalidDataException('acceptTransaction is required');
        }

        // Validação explícita para valores booleanos em string
        if (!in_array($params['acceptTransaction'], ['true', 'false'], true)) {
            throw new InvalidDataException('The acceptTransaction field must be a boolean (true or false).');
        }

        $input->smsId = (int) $args['id'];
        $input->acceptTransaction = $params['acceptTransaction'] === 'true'; 
        $input->installmentCount = $params['installmentCount']; 

        return $input;
    }


    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->acceptTransaction->execute(
                $this->serialize($httpRequest->params, $httpRequest->args)
            )
        );
    }
}
