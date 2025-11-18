<?php

namespace App\Domain\RepositoryContract;

use App\Domain\Entity\{SmsHistory, PaginatedEntities};
use App\Domain\Entity\ValueObject\SmsType;
use DateTime;

interface SmsHistoryRepositoryContract
{
    public function create(
        SmsHistory $SmsHistory
    ): SmsHistory;

    public function removeByUserIdAndType(
        SmsType $type,
        ?int $userId = null,
        ?int $employeeId = null,
        ?int $establishmentId = null,
        ?int $supplierId = null,
    ): void;

    public function removeById(int $id): void;

    public function findMostRecentByUserIdAndType(
        SmsType $type,
        ?int $userId = null,
        ?int $employeeId = null,
        ?int $establishmentId = null,
        ?int $supplierId = null,
    ): SmsHistory | null;

    public function getAllPaginated(int $limit, int $offset, array $params = []): PaginatedEntities;

    public function getById(int $id): SmsHistory;

    public function update(SmsHistory $smsHistory): SmsHistory;
}
