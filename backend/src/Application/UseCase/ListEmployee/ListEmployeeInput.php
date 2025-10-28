<?php

namespace App\Application\UseCase\ListEmployee;



class ListEmployeeInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public ?int        $employeeId = null;
    public ?string     $employeeType = null;
    public ?string     $field = null;
    public ?string     $filter = null;
}
