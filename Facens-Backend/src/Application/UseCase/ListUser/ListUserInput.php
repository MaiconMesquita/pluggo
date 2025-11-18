<?php

namespace App\Application\UseCase\ListUser;



class ListUserInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public ?string $filter = null;
    public ?string $field = null;
}
