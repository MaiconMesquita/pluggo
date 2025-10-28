<?php

namespace App\Domain\Entity\Service\DateAdjustment;

use App\Domain\Entity\Service\DateTimeOffset\DateTimeOffset;
use DateTime;

class DateAdjustment
{
    /**
     * Ajusta o dia para um valor válido dentro do mês e define a hora como 23:59:59.
     *
     * @param int $day O dia a ser ajustado.
     * @param DateTime|null $baseDate Data base para o cálculo. Se não fornecida, usa a data atual.
     * @return DateTime Um objeto DateTime com o dia ajustado e hora 23:59:59.
     */
    public static function adjustDayToValidDate(int $day, ?DateTime $baseDate = null, bool $midnight = false): DateTime
    {
        // Usa a data fornecida ou a data atual como padrão
        $date = $baseDate ?? DateTimeOffset::getAdjustedDateTime();

        // Calcula o último dia do mês baseado na data fornecida
        $lastDayOfMonth = (int)$date->format('t');

        // Ajusta o dia se for maior que o último dia do mês
        $adjustedDay = min($day, $lastDayOfMonth);

        // Atualiza a data com o novo dia
        $date->setDate((int)$date->format('Y'), (int)$date->format('m'), $adjustedDay);

        if($midnight)
        $date->setTime(00, 00, 00);
        else
        $date->setTime(23, 59, 59);

        return $date;
    }

}
