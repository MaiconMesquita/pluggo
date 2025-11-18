<?php

namespace App\Infra\ThirdParty\InvoiceDateHelper;

use DateTimeImmutable;
use DateInterval;
use Yasumi\Yasumi;

final class InvoiceDateAdapter implements InvoiceDate
{
    private $holidays;

    public function __construct()
    {
        // Inicializa feriados nacionais do Brasil para o ano atual
        $this->holidays = Yasumi::create('Brazil', (int)date('Y'), 'pt_BR');
    }

    public function calculateClosingDate(\DateTimeInterface $today, int $closingDay): \DateTimeInterface
    {
        $base = DateTimeImmutable::createFromInterface($today);
        if ((int)$today->format('d') >= $closingDay) {
            $base = $base->modify('+1 month');
        }


        return $base->setDate(
            (int)$base->format('Y'),
            (int)$base->format('m'),
            $closingDay
        )->setTime(0, 0, 0);
    }

    public function calculateDueDate(\DateTimeInterface $today, int $closingDay, int $dueDay): \DateTimeInterface
    {

        $closingDate = $this->calculateClosingDate($today, $closingDay);
        $dueMonth = DateTimeImmutable::createFromInterface($closingDate);
        if ($dueDay <= $closingDay) {
            $dueMonth = $dueMonth->modify('+1 month');
        }


        $dueDate = (new DateTimeImmutable())->setDate(
            (int)$dueMonth->format('Y'),
            (int)$dueMonth->format('m'),
            $dueDay
        )->setTime(23, 59, 59);

        // Ajusta para próximo dia útil (pula feriados e finais de semana)
        return $this->adjustToBusinessDay($dueDate);
    }

    public function calculateClosingDueDate(\DateTimeInterface $closingDate): \DateTimeInterface
{
    $date = \DateTimeImmutable::createFromInterface($closingDate);
    return $date->modify('+1 month')->setTime(0, 0, 0);
}


    public function format(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    public function parse(string $dateString): \DateTimeInterface
    {
        return new DateTimeImmutable($dateString);
    }

    private function adjustToBusinessDay(DateTimeImmutable $date): DateTimeImmutable
    {
        while ($this->isWeekend($date) || $this->isHoliday($date)) {
            $date = $date->add(new DateInterval('P1D'));
        }
        return $date->setTime(23, 59, 59);
    }

    private function isWeekend(DateTimeImmutable $date): bool
    {
        $dayOfWeek = (int)$date->format('N'); // 6 = sábado, 7 = domingo
        return $dayOfWeek >= 6;
    }

    private function isHoliday(DateTimeImmutable $date): bool
    {
        return $this->holidays->isHoliday($date);
    }
}
