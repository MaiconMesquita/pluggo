<?php

namespace App\Domain\Entity\Service\DateTimeOffset;

use DateTime;
use DateInterval;

class DateTimeOffset
{
    /**
     * Retorna a data e hora ajustada com base na variável de ambiente TIME_ZONE.
     *
     * @param bool $add Se verdadeiro, adiciona o tempo ao invés de subtrair (padrão: false).
     * @return DateTime A data e hora ajustada.
     */
    public static function getAdjustedDateTime(bool $add = false): DateTime
    {
        $offsetInSeconds = getenv('TIME_ZONE') ?: 10800; // Padrão: 3 horas (10.800 segundos)
        
        // Converte segundos para o formato de intervalo de tempo
        $interval = new DateInterval('PT' . abs($offsetInSeconds) . 'S');

        $dateTime = new DateTime();

        // Ajusta a data adicionando ou subtraindo o intervalo
        return $add ? $dateTime->add($interval) : $dateTime->sub($interval);
    }
}
