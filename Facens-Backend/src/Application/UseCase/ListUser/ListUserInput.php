<?php

namespace App\Application\UseCase\ListUser;



class ListUserInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public string $type;
    public ?array $filters = null;
}
