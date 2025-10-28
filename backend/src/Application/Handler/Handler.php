<?php

namespace App\Application\Handler;

use App\Domain\Event\DomainEvent;

interface Handler
{
    public function handle();
    public function setEvent(DomainEvent $event): void;
}
