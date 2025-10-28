<?php

namespace App\Application\UseCase\ListCard;



class ListCardInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public ?int        $segmentId = null;
    public ?int        $establishmentId = null;
    public ?bool       $isPrimaryCard = null;
    public ?int       $primaryCardId = null;
}
