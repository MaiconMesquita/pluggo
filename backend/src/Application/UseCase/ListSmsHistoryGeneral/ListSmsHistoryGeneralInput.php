<?php

namespace App\Application\UseCase\ListSmsHistoryGeneral;



class ListSmsHistoryGeneralInput
{
    public int        $limit = 20;
    public int        $offset = 0;
    public ?int       $establishmentId = null;
    public ?bool      $passwordReset = false;
    public ?bool      $firstPassword = false;
    public ?bool      $codeGeneration = false;
    public ?bool      $invitation = false;
    public ?bool      $billingTransaction = false;
    public ?bool      $withdrawalNotification = false;
    public ?bool      $cardRequestConfirmation = false;
    public ?int       $userId = null;
    public ?bool      $employeePersonalSms = false;
}
