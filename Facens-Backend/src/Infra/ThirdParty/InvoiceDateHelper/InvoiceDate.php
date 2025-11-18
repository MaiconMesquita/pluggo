<?php

namespace App\Infra\ThirdParty\InvoiceDateHelper;

interface InvoiceDate
{
    /**
     * Retorna a data de fechamento da fatura.
     *
     * @param \DateTimeInterface $today
     * @param int $closingDay
     * @return \DateTimeInterface
     */
    public function calculateClosingDate(\DateTimeInterface $today, int $closingDay): \DateTimeInterface;

    /**
     * Retorna a data de vencimento da fatura, considerando feriados/finais de semana.
     *
     * @param \DateTimeInterface $today
     * @param int $closingDay
     * @param int $dueDay
     * @return \DateTimeInterface
     */
    public function calculateDueDate(\DateTimeInterface $today, int $closingDay, int $dueDay): \DateTimeInterface;

    /**
     * Retorna a data de fechamento do próximo mês (closingDueDate).
     *
     * @param \DateTimeInterface $closingDate
     * @return \DateTimeInterface
     */
    public function calculateClosingDueDate(\DateTimeInterface $closingDate): \DateTimeInterface;

    /**
     * Formata uma data em string (opcional, caso precise exibir datas em formato padrão).
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    public function format(\DateTimeInterface $date): string;

    /**
     * Converte uma string em DateTimeInterface.
     *
     * @param string $dateString
     * @return \DateTimeInterface
     */
    public function parse(string $dateString): \DateTimeInterface;
}
