<?php

namespace App\Application\UseCase\CreateReview;

/**
 * Input para criar review
 *
 * driverId: opcional
 * - Se authType = 'driver': é inferido do token (pode ser omitido)
 * - Se authType = 'employee': deve ser fornecido no body
 *
 * chargeSpotId: obrigatório
 * rating: obrigatório (1-5)
 * comment: opcional
 */
class CreateReviewInput
{
    public ?int $rating = null;
    public ?string $comment = null;
    public ?int $driverId = null;
    public int $chargeSpotId;
}
