<?php

namespace App\Domain\Exception;

use Throwable;

class AccessDeniedByHierarchyException extends \Exception
{
    public function __construct($message = 'You dont have permission to access this feature with your current access level.', ?Throwable $previous = null)
    {
        parent::__construct($message, 1, $previous);
    }
}
