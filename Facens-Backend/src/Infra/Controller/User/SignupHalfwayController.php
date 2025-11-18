<?php

namespace App\Infra\Controller\User;

use App\Application\UseCase\SignupHalfway\SignupHalfwayInput;
use App\Infra\Controller\{
    Controller,
    HttpRequest,
    HttpResponse,
};
use App\Domain\Exception\InvalidDataException;

class SignupHalfwayController implements Controller
{
    public function __construct(
        private $updateSignup,
    ) {
    }

    public function serialize(array $body, array $args): SignupHalfwayInput
    {
        $input = new SignupHalfwayInput();

        if (!isset($args['deviceId']))throw new InvalidDataException('deviceId is required');        

        $input->deviceId = $args['deviceId'];

        // Validação de number
        if (isset($body['number'])) {
            if (!ctype_digit($body['number']))throw new InvalidDataException('Number must contain only numbers.');
            
            $input->number = $body['number'];
        } else {
            $input->number = null;
        }

        if (isset($body['acceptedCardTerms'])) {
            if (!in_array($body['acceptedCardTerms'], ['true', 'false'], true)) {
                throw new InvalidDataException('The acceptedCardTerms field must be a boolean (true or false).');
            }
            $input->acceptedCardTerms = $body['acceptedCardTerms'] === 'true';
        }

        if (isset($body['acceptedTermsOfUse'])) {
            if (!in_array($body['acceptedTermsOfUse'], ['true', 'false'], true)) {
                throw new InvalidDataException('The acceptedTermsOfUse field must be a boolean (true or false).');
            }
            $input->acceptedTermsOfUse = $body['acceptedTermsOfUse'] === 'true';
        }

        $input->selfiePhoto = $body['selfiePhoto'] ?? null;
        $input->documentFront = $body['documentFront'] ?? null;
        $input->documentBack = $body['documentBack'] ?? null;
        $input->street = $body['street'] ?? null;
        $input->postalCode = $body['postalCode'] ?? null;
        $input->neighborhood = $body['neighborhood'] ?? null;
        $input->complement = $body['complement'] ?? null;
        $input->city = $body['city'] ?? null;
        $input->state = $body['state'] ?? null;
        $input->latitude = $body['latitude'] ?? null;
        $input->longitude = $body['longitude'] ?? null;

        $input->gender = $body['gender'] ?? null;
        $input->birthDate = $body['birthDate'] ?? null;
        $input->maritalStatus = $body['maritalStatus'] ?? null;
        $input->issuingState = $body['issuingState'] ?? null;
        $input->issuingAuthority = $body['issuingAuthority'] ?? null;
        $input->fatherName = $body['fatherName'] ?? null;
        $input->motherName = $body['motherName'] ?? null;
        $input->nationality = $body['nationality'] ?? null;
        $input->rg = $body['rg'] ?? null;

        $this->validate($input);

        return $input;
    }

    private function validate(SignupHalfwayInput $input): void
    {
        if (
            empty($input->selfiePhoto) &&
            empty($input->documentFront) &&
            empty($input->documentBack) &&
            empty($input->street) &&
            empty($input->postalCode) &&
            empty($input->neighborhood) &&
            empty($input->number) &&
            empty($input->complement) &&
            empty($input->city) &&
            empty($input->acceptedCardTerms) &&
            empty($input->acceptedTermsOfUse) &&
            empty($input->latitude) &&
            empty($input->longitude) &&
            empty($input->maritalStatus) &&
            empty($input->maritalStatus) &&
            empty($input->issuingState) &&
            empty($input->issuingAuthority) &&
            empty($input->nationality) &&
            empty($input->birthDate) &&
            empty($input->gender) &&
            empty($input->fatherName) &&
            empty($input->motherName) &&
            empty($input->state)
        ) {
            throw new InvalidDataException('At least one field must be filled in.');
        }
    }
    public function handle(HttpRequest $httpRequest): HttpResponse
    {
        return new HttpResponse(
            HttpResponse::HTTP_NO_CONTENT,
            $this->updateSignup->execute(
                $this->serialize($httpRequest->body, $httpRequest->args)
            )
        );
    }
}
