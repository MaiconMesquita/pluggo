<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\SendTransaction\SendTransactionInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;

class SendTransactionController implements Controller
{
    public function __construct(
        private $sendTransaction,
        private ThirdPartyFactoryContract $thirdPartyFactory
    ) {
    }

    public function serialize(array $body): SendTransactionInput
    {
        $schema = [            
            "phone"           => 'nullable',
            "cpf"             => 'nullable',
            "userId"          => 'nullable',
            "establishmentId" => 'nullable',
            "description"     => 'required',
            "amount"          => 'required',
        ];

        $requestValidator = $this->thirdPartyFactory->getRequestValidator();

        if (!$requestValidator->validate($body, $schema)) 
        throw new InvalidDataException($requestValidator->getMessageError());
        
        $phone = $requestValidator->getParam('phone');
        $cpf = $requestValidator->getParam('cpf');
        $userId = $requestValidator->getParam('userId');

        $filledFields = array_filter([$phone, $cpf, $userId], fn($field) => !empty($field));
        if (count($filledFields) === 0) 
        throw new InvalidDataException('At least one of the fields "phone", "cpf", or "userId" must be filled.');

        if (count($filledFields) > 1) 
        throw new InvalidDataException('Only one of the fields "phone", "cpf", or "userId" can be filled.');

        $input = new SendTransactionInput;

        if (!empty($phone) && !ctype_digit($phone)) 
        throw new InvalidDataException('Phone must contain only numbers.');
        
        $input->phone = $phone;
        $input->cpf = $cpf;
        $input->userId = $userId;
        $input->establishmentId = $requestValidator->getParam('establishmentId');
        $input->description = $requestValidator->getParam('description');
        $input->amount = $requestValidator->getParam('amount');

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->sendTransaction->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
