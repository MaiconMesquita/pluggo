<?php

namespace App\Infra\ThirdParty\RequestValidator;

use Rakit\Validation\Validator;

final class RakitValidatorAdapter implements RequestValidator
{

    public string $errors;
    public array $validatedData;

    public function __construct(private Validator $validator)
    {
    }

    public function validate(array $request, array $schema): bool
    {
        $validation = $this->validator->validate($request, $schema);
        if ($validation->fails()) {
            $errors = $validation->errors()->firstOfAll();
            foreach ($errors as &$error) {
                if (is_array($error)) $error = json_encode($error);
            }
            $this->errors = strtolower(implode(', ', $errors));
            return false;
        }
        $this->validatedData = $validation->getValidatedData();
        return true;
    }

    public function getMessageError(): string
    {
        return $this->errors;
    }

    public function getValidatedData(): array
    {
        return $this->validatedData;
    }

    public function getParam(string $key): mixed
    {
        $bool = null;
        if (isset($this->validatedData[$key])) $bool = false;
        return !empty($this->validatedData[$key]) ? $this->validatedData[$key] : $bool;
    }
}
