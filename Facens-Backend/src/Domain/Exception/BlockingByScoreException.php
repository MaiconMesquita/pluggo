<?php

namespace App\Domain\Exception;

use Throwable;

class BlockingByScoreException extends \Exception
{
    public function __construct($message = 'This user has been inactive for a period of time determined by the score.', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
