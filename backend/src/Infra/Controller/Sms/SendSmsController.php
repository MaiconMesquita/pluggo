<?php

namespace App\Infra\Controller\Sms;

use App\Application\UseCase\SendSms\SendSmsInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;

class SendSmsController implements Controller
{
    public function __construct(
        private $createSms,
    ) {
    }

    public function serialize(array $body): SendSmsInput
    {
        // Validação: description é obrigatório
        if (!isset($body['description']) || empty(trim($body['description']))) {
            throw new InvalidDataException('The "description" field is required.');
        }

        // Validação: description é obrigatório
        if (!isset($body['title']) || empty(trim($body['title']))) {
            throw new InvalidDataException('The "title" field is required.');
        }

        // Extrair os campos opcionais
        $contacts = $body['contacts'] ?? null;
        $phone = $body['phone'] ?? null;
        $email = $body['email'] ?? null;

        // Verificar se mais de um campo foi enviado
        $fields = array_filter([
            'contacts' => $contacts,
            'phone' => $phone,
            'email' => $email,
        ]);

        if (count($fields) > 1) {
            throw new InvalidDataException('Only one of "contacts", "phone", or "email" can be provided.');
        }

        // Verificar se pelo menos um dos campos foi enviado
        if (empty($fields)) {
            throw new InvalidDataException('At least one of "contacts", "phone", or "email" must be provided.');
        }

        // Verificar se "contacts" é um array (caso seja enviado)
        if (!empty($contacts) && !is_array($contacts)) {
            throw new InvalidDataException('The "contacts" field must be an array.');
        }

        $input = new SendSmsInput();
        $input->contacts = $contacts;
        $input->phone = $phone;
        $input->email = $email;
        $input->description = $body['description'];        
        $input->title = $body['title'];

        return $input;
    }

    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->createSms->execute(
                $this->serialize($httpRequest->body)
            )
        );
    }
}
