<?php

namespace App\Infra\ThirdParty\Queue;

use App\Domain\Event\DomainEvent;

interface Queue {
    public function connect();
    public function close();
    public function consume();
    public function publish(DomainEvent $domainEvent);
}
