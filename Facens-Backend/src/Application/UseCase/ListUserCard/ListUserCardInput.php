<?php

namespace App\Application\UseCase\ListUserCard;



class ListUserCardInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public ?int       $cardId = null;
    public ?int       $userId = null;
    public ?int       $isPrimaryUserCard = null;
    public ?int       $primaryUserCardId = null;
}
