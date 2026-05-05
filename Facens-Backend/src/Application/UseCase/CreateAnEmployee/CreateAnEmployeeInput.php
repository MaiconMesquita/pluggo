<?php

namespace App\Application\UseCase\CreateAnEmployee;



class CreateAnEmployeeInput
{
    public string        $name;
    public string        $email;
    public string        $phone;
    public ?string        $cpf;
    public ?string        $password = null;
}
