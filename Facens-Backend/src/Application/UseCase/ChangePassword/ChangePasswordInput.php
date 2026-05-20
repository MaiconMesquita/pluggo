<?php

namespace App\Application\UseCase\ChangePassword;

/**
 * Input para trocar senha
 *
 * currentPassword: obrigatório quando trocando própria senha, ignorado quando employee troca de terceiros
 * newPassword: obrigatório
 * targetId: opcional, para employee trocar senha de driver/host (tipo é inferido por 'targetEntityType')
 * targetEntityType: 'driver' ou 'host', obrigatório se targetId for fornecido
 */
class ChangePasswordInput
{
    public ?string $currentPassword = null;
    public string $newPassword;
    public ?int $targetId = null;
    public ?string $targetEntityType = null;
}
