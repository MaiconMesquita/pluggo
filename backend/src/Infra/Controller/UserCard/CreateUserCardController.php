<?php

namespace App\Infra\Controller\UserCard;

use App\Application\UseCase\CreateUserCard\CreateUserCardInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;

class CreateUserCardController implements Controller
{
    public function __construct(
        private $createUserCard,
    ) {}

    public function serialize(array $body): CreateUserCardInput
    {
        $cardId = $body['cardId'];
        $userId = $body['userId'];
        $increaseLimit = $body['increaseLimit'];

        if (!isset($cardId)) throw new InvalidDataException('cardId is required');   

        $input = new CreateUserCardInput;   

        if (!is_numeric($cardId) || intval($cardId) != $cardId) {
            throw new InvalidDataException('The cardId must be an integer.');
        }
        $input->cardId = (int) $cardId;
        
        if (!empty($userId)){   
            if (!is_numeric($userId) || intval($userId) != $userId) {
                throw new InvalidDataException('The userId must be an integer.');
            }
            $input->userId = (int) $userId;
        }
        
        if (!empty($increaseLimit)) {            
            if ($increaseLimit != 'true' && $increaseLimit != 'false') {
                throw new InvalidDataException('The login status field must be a boolean (true or false).');
            }
            $input->increaseLimit = $increaseLimit == 'true' ? 1 : 0;
        }
        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->createUserCard->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
