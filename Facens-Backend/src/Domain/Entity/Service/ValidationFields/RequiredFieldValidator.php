<?php

namespace App\Domain\Entity\Service\ValidationFields;

class RequiredFieldValidator
{
    /**
     * Valida os campos obrigatórios de uma entidade.
     *
     * @param object $entity A entidade a ser validada.
     * @param array $requiredFields Um array de strings com os nomes dos campos obrigatórios.
     * @return array Um array de strings com os nomes dos campos faltantes.
     */
    public static function validate(object $entity, array $requiredFields): array
    {
        $missingFields = [];

        foreach ($requiredFields as $field) {
            // Utiliza o método getter para acessar o campo.
            $getter = 'get' . ucfirst($field);

            // Verifica se o método getter existe.
            if (!method_exists($entity, $getter)) {
                $missingFields[] = $field;
                continue;
            }

            // Obtém o valor do campo.
            $value = $entity->$getter();

            // Verifica se o valor está ausente ou, no caso de booleanos, se é false.
            if ($value === null || $value === '' || $value === false) {
                $missingFields[] = $field;
            }
        }

        return $missingFields;
    }
}
