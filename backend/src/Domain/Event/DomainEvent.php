<?php
namespace App\Domain\Event;


interface DomainEvent {
    public function getName(): string;
    public function getPayload(): array;
    public function getDelaySeconds(): int;
}
