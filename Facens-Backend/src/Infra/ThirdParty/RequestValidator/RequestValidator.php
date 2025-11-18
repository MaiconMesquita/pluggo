<?php
namespace App\Infra\ThirdParty\RequestValidator;

interface RequestValidator {
    public function validate(array $request, array $schema): bool;
    public function getMessageError(): string;
    public function getValidatedData(): array;
    public function getParam(string $key): mixed;
}
