<?php

namespace App\Application\UseCase\ListApprovalRequests;



class ListApprovalRequestsInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public ?int        $ownerId = null;
}
