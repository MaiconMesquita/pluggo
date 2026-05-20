<?php

namespace App\Application\UseCase\ListChargeSpots;

/**
 * Input para ListChargeSpots
 *
 * hostId é opcional:
 * - Para HOST: ignorado (usa hostId do token)
 * - Para EMPLOYEE: se vazio exibe todos, se preenchido exibe apenas daquele host
 * - Para DRIVER: ignorado (exibe todos)
 */
class ListChargeSpotsInput
{
    public ?int $hostId = null;
}
