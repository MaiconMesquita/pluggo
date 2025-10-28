<?php

namespace App\Application\UseCase\CreateUserCard;



class CreateUserCardInput
{
    public int        $cardId;
    public ?int        $userId = null;
    public bool        $increaseLimit = true;
}
