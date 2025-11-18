<?php

namespace App\Infra\Controller\UserCard;

use App\Application\UseCase\ListUserCard\ListUserCardInput;
use App\Domain\Exception\InvalidDataException;
use App\Infra\Controller\{Controller, HttpRequest, HttpResponse};

class ListUserCardController implements Controller
{
    public function __construct(private $listUserCard) {}

    public function serialize(array $params): ListUserCardInput
    {
        $input = new ListUserCardInput;
        if (isset($params['limit'])) {
            if (!is_numeric($params['limit']) || intval($params['limit']) != $params['limit']) {
                throw new InvalidDataException('The limit must be an integer.');
            }
            $input->limit = (int) $params['limit'];
        }
        if (isset($params['offset'])) {
            if (!is_numeric($params['offset']) || intval($params['offset']) != $params['offset']) {
                throw new InvalidDataException('The offset must be an integer.');
            }   
            $input->offset = (int) $params['offset'];
        }

        if (isset($params['userId'])) {
            if (!is_numeric($params['userId']) || intval($params['userId']) != $params['userId']) {
                throw new InvalidDataException('The userId must be an integer.');
            }
        
            $input->userId = (int) $params['userId'];
        }

        if (isset($params['primaryUserCardId'])) {
            if (!is_numeric($params['primaryUserCardId']) || intval($params['primaryUserCardId']) != $params['primaryUserCardId']) {
                throw new InvalidDataException('The primaryUserCardId must be an integer.');
            }
        
            $input->primaryUserCardId = (int) $params['primaryUserCardId'];
        }

        if (!empty($params['isPrimaryUserCard'])) {
            if ($params['isPrimaryUserCard'] != 'true' && $params['isPrimaryUserCard'] != 'false') {
                throw new InvalidDataException('The isPrimaryUserCard field must be a boolean (true or false).');
            }
            if($params['isPrimaryUserCard'] == 'true')
            $input->isPrimaryUserCard = 1;
            elseif($params['isPrimaryUserCard'] == 'false')
            $input->isPrimaryUserCard = 2;
        }
        
        if (isset($params['cardId'])) {
            if (!is_numeric($params['cardId']) || intval($params['cardId']) != $params['cardId']) {
                throw new InvalidDataException('The cardId must be an integer.');
            }
        
            $input->cardId = (int) $params['cardId'];
        }
        return $input;
    }

    public function handle(
        HttpRequest $httpRequest
    ): HttpResponse {
        return new HttpResponse(
            HttpResponse::HTTP_SUCCESS_CODE,
            $this->listUserCard->execute(
                $this->serialize(
                    $httpRequest->params,
                )
            )
        );
    }
}
